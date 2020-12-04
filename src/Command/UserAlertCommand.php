<?php

namespace App\Command;

use App\Manager\AlertManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class UserAlertCommand extends Command
{
    protected static $defaultName = 'app:user:alert';

    private $alertManager;

    public function __construct(AlertManager $alertManager)
    {
        $this->alertManager = $alertManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Wysłanie powiadomienia do usera');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->alertManager->execute();

        $io->success('done');

        return 0;
    }
}
