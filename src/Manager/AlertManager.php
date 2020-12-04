<?php

namespace App\Manager;

use App\Entity\UserCurrency;
use App\Repository\UserCurrencyRepository;
use Twig\Environment;

class AlertManager
{
    private $mailer;
    private $twig;
    private $repository;

    public function __construct(CustomMailer $mailer, Environment $twig, UserCurrencyRepository $repository)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->repository = $repository;
    }

    public function execute(): void
    {
        $items = $this->repository->getForAlert();
        foreach ($items as $item) {
            $this->alert($item);
            $this->repository->markAsSent($item);
        }
    }

    private function alert(UserCurrency $userCurrency): void
    {
        $this->mailer->send(
            $userCurrency->getUser()->getEmail(),
            'Zmiana ceny',
            $this->twig->render('user/email_alert.html.twig', ['user_currency' => $userCurrency])
        );
    }
}
