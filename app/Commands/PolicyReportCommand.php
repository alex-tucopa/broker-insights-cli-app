<?php

namespace App\Commands;

use App\Exceptions\DataNotFoundException;
use App\Models\Broker;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;
use App\Services\PolicyService;

#[AsCommand(name: 'app:policy-report')]
class PolicyReportCommand extends Command
{
    protected function configure(): void
    {
        $this->addOption(
            'broker-id',
            null,
            InputOption::VALUE_REQUIRED,
            'Broker ID',
        );

        $this->addOption(
            'broker-name',
            null,
            InputOption::VALUE_REQUIRED,
            'Broker name',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $brokerId = $this->getBrokerId($input);

            $activePolicyStats = PolicyService::getActivePolicyCountAndSumInsured($brokerId);

            $table = new Table($output);
            $table
                ->setHeaders(['Active Policies', 'Sum Insured', 'Average Duration (days)', 'Customers'])
                ->setRows([
                    [
                        $activePolicyStats['activePolicyCount'],
                        number_format($activePolicyStats['activePolicySumInsured'] ?? 0),
                        PolicyService::getActivePolicyAverageDuration($brokerId),
                        PolicyService::getCustomerCount($brokerId),
                    ]
                ]);

            $output->writeln('Summary');
            $table->render();

            if ($brokerId) {
                $policies = PolicyService::getPolicies($brokerId);

                $rows = array_map(function($policy) {
                    return [
                        $policy->broker_policy_ref,
                        $policy->effective_date,
                        $policy->renewal_date,
                        $policy->is_active ? 'Y' : '',
                        $policy->duration > 0 ? $policy->duration : 0,
                        $policy->amount_insured,
                        $policy->premium,
                        $policy->insurer_name,
                        $policy->product_type,
                        $policy->customer_type,
                    ];
                }, $policies);
  
                $table
                    ->setHeaders([
                        'Broker Ref',
                        'Effective Date',
                        'Renewal Date',
                        'Active',
                        'Duration',
                        'Amount',
                        'Premium',
                        'Insurer Name',
                        'Product Type',
                        'Customer Type',
                    ])
                    ->setRows($rows);
                
                $output->writeln('Policies');
                $table->render();
            }

            return Command::SUCCESS;
        } catch (DataNotFoundException $e) {
            $output->writeln('Input error ' . $e->getMessage());
            return Command::INVALID;
        } catch (Exception $e) {
            $output->writeln('Processing error ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function getBrokerId(InputInterface $input): int|null
    {
        $brokerId = $input->getOption('broker-id');
        $brokerName = $input->getOption('broker-name');

        if ($brokerId || $brokerName) {
            $broker = null;
            
            if ($brokerId) {
                $broker = Broker::find((int) $brokerId);
            } else {
                $broker = Broker::where('name', $brokerName)->first();
            }

            if (!$broker) {
                $brokerInput = $brokerId ?: $brokerName;
                throw new DataNotFoundException("Could not find broker: \"$brokerInput\"");
            }

            return $broker->id;
        }

        return null;
    }
}
