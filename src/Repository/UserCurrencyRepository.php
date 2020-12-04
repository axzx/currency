<?php

namespace App\Repository;

use App\Entity\UserCurrency;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserCurrency|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserCurrency|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserCurrency[]    findAll()
 * @method UserCurrency[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserCurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCurrency::class);
    }

    public function getForDisableAlert(string $uuid, string $code): ?UserCurrency
    {
        $qb = $this->createQueryBuilder('uc');
        $qb
            ->join('uc.user', 'user')
            ->join('uc.currency', 'currency')
            ->andWhere('user.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->andWhere('currency.code = :code')
            ->setParameter('code', $code)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array|UserCurrency[]
     */
    public function getForAlert(): array
    {
        $qb = $this->createQueryBuilder('uc');

        $qb
            ->join('uc.user', 'user')
            ->join('uc.currency', 'currency')

            // user musi mieć aktywne konto
            ->andWhere('user.confirmedAt is not null')

            // cena zmieniła się od ostatniego powiadomienia
            ->andWhere($qb->expr()->orX(
                'uc.alertSentAt is null',
                'uc.alertSentAt is not null AND uc.alertSentAt < currency.rateChangeAt'
            ))

            // cena przekroczyła poziom alertów
            ->andWhere($qb->expr()->orX(
                'currency.rate > uc.alertMax',
                'currency.rate < uc.alertMin'
            ))
        ;

        return $qb->getQuery()->getResult();
    }

    public function markAsSent(UserCurrency $item): void
    {
        $item->setAlertSentAt(new \DateTime());
        $this->_em->flush();
    }
}
