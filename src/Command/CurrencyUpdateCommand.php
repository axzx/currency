<?php

namespace App\Command;

use App\Manager\CurrencyUpdateManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CurrencyUpdateCommand extends Command
{
    protected static $defaultName = 'app:currency:update';

    private $manager;

    public function __construct(CurrencyUpdateManager $manager)
    {
        $this->manager = $manager;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Aktualizacja kursów walut');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $result = $this->manager->update();

        $io->success('Dane pobranze z '.$result);

        return 0;
    }
}
