<?php

namespace App\Manager;

use App\Entity\Currency;
use App\Entity\User;
use App\Entity\UserCurrency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;

class UserManager
{
    private $em;
    private $mailer;
    private $router;

    public function __construct(
        EntityManagerInterface $em,
        CustomMailer $mailer,
        RouterInterface $router
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->router = $router;
    }

    public function create(array $input): User
    {
        $user = new User();

        $pa = PropertyAccess::createPropertyAccessor();

        $user->setEmail($pa->getValue($input, '[email]'));
        $user->setFirstname($pa->getValue($input, '[firstname]'));
        $user->setLastname($pa->getValue($input, '[lastname]'));
        $user->setPhone($pa->getValue($input, '[phone]'));

        if ($inputBirthday = $pa->getValue($input, '[birthday]')) {
            try {
                $birthday = new \DateTime($inputBirthday);
            } catch (\Exception $ex) {
                $birthday = null;
            }
            $user->setBirthday($birthday);
        }

        foreach ((array) $pa->getValue($input, '[currencies]') as $currencyItem) {
            $currency = $this->getCurrency($pa->getValue($currencyItem, '[id]'));
            $this->addCurrencyToUser($user, $currency, $currencyItem);
        }

        return $user;
    }

    public function register(User $user): bool
    {
        $this->em->beginTransaction();
        $this->em->persist($user);
        $this->em->flush();

        $confirmUrl = $this->router->generate(
            'app_v1_user_confirm',
            ['token' => $user->getToken()],
            Router::ABSOLUTE_URL
        );

        try {
            $this->mailer->send(
                $user->getEmail(),
                'Potwierdzenie',
                $confirmUrl
            );
        } catch (TransportException $ex) {
            $this->em->rollback();

            return false;
        }

        $this->em->commit();

        return true;
    }

    public function confirm(User $user): void
    {
        $user
            ->setToken(null)
            ->setConfirmedAt(new \DateTime())
        ;

        $this->em->flush();
    }

    private function addCurrencyToUser(User $user, ?Currency $currency, array $input): void
    {
        $userCurrency = new UserCurrency($currency, $user);

        if (!empty($input['min'])) {
            $userCurrency->setAlertMin((float) $input['min']);
        }

        if (!empty($input['max'])) {
            $userCurrency->setAlertMax((float) $input['max']);
        }
    }

    private function getCurrency(?int $id): ?Currency
    {
        return $this->em->getRepository(Currency::class)->find($id);
    }
}
