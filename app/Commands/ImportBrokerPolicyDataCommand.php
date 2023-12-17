<?php

namespace App\Commands;

use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Exceptions\DataFormatException;
use App\Exceptions\ImportBrokerPolicyDataException;
use App\Exceptions\ImportBrokerPolicyInvalidArgumentException;
use App\Models\Broker;
use App\Validators\BrokerInputValidator;
use App\Services\FileParser;
use App\Services\ImportBrokerPolicy;

#[AsCommand(name: 'app:import-broker-policy-data')]
class ImportBrokerPolicyDataCommand extends Command
{
    protected function configure(): void
    {
        $this->addArgument('broker_id', InputArgument::REQUIRED, 'Broker ID');
        $this->addArgument('filename', InputArgument::REQUIRED, 'File to import');
        $this->addArgument('format', InputArgument::REQUIRED, 'Format of the CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $brokerId = $input->getArgument('broker_id');
        $inputFilename = $input->getArgument('filename');
        $inputFormat = $input->getArgument('format');

        try {
            if (!is_readable($inputFilename)) {
                throw new ImportBrokerPolicyInvalidArgumentException("Cannot find input file: \"$inputFilename\"");
            }

            $broker = Broker::find($brokerId);
            if (!$broker) {
                throw new ImportBrokerPolicyInvalidArgumentException("Cannot find broker with ID: \"$brokerId\"");
            }

            $mappings = require __DIR__ . '/../../config/broker_csv_data_map.php';
            if (!array_key_exists($inputFormat, $mappings)) {
                throw new ImportBrokerPolicyInvalidArgumentException("Format is not configured: \"$inputFormat\"");
            }

            $fileParser = FileParser::makeParser($mappings[$inputFormat]);

            $validator = new BrokerInputValidator();

            $errors = [];

            foreach ($fileParser->parse($inputFilename) as $rowNumber => $data) {
                $lineNumber = $rowNumber + 2; // plus 1 as rows 0 indexed; plus 1 to account for headers
                if ($validator->validate($data)) {
                    try {
                        ImportBrokerPolicy::import($data, $broker->id);
                    } catch(ImportBrokerPolicyDataException $e) {
                        $errors[] = "Error on line $lineNumber - " . $e->getMessage();
                    }
                } else {
                    $errors[] = "Error on line $lineNumber - " . implode('|', $validator->getErrors());
                }
            }

            if ($errors) {
                $output->writeln("File \"$inputFilename\" processed with errors:");
                foreach ($errors as $error) {
                    $output->writeln("\t* " .$error);
                }
            } else {
                $output->writeln("File \"$inputFilename\" processed");
            }
            return Command::SUCCESS;
        } catch (ImportBrokerPolicyInvalidArgumentException|DataFormatException $e) {
            $output->writeln('Input error ' . $e->getMessage());
            return Command::INVALID;
        } catch (Exception $e) {
            $output->writeln('Processing error ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
