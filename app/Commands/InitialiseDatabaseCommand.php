<?php

namespace App\Commands;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:initialise-database')]
class InitialiseDatabaseCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            require __DIR__ . '/../../bootstrap/application.php';
            require __DIR__ . '/../../database/create_tables.php';
            require __DIR__ . '/../../database/insert_initial_data.php';

            return Command::SUCCESS;
        } catch (Exception $e) {
            $output->writeln('Processing error ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}