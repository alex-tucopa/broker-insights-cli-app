<?php

use Symfony\Component\Console\Tester\CommandTester;
use App\Commands\ImportBrokerPolicyDataCommand;
use App\Services\PolicyService;
use App\Services\ImportBrokerPolicy;

beforeAll(function() {
    require __DIR__ . '/../../../bootstrap/application.php';
});

describe('PolicyService', function() {
    beforeEach(function() {
        require __DIR__ . '/../../../database/create_tables.php';
        require __DIR__ . '/../../../database/insert_initial_data.php';

        $import = new CommandTester(new ImportBrokerPolicyDataCommand());
        $import->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'format_1']);
    });

    afterEach(function() {
        require __DIR__ . '/../../../database/drop_tables.php';
    });

    it('gets active policy count and sum insured', function() {
        expect(PolicyService::getActivePolicyCountAndSumInsured(1))->toBe([
            'activePolicyCount' => 5,
            'activePolicySumInsured' => 3650000,
        ]);
    });

    it('gets active policy average duration', function() {
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';

        $data['policyRef'] = 'POL1001';
        $data['effectiveDate'] = date('Y-m-d');
        $data['renewalDate'] = date('Y-m-d', strtotime('+17 days'));
        ImportBrokerPolicy::import($data, 2);
        expect(PolicyService::getActivePolicyAverageDuration(2))->toBe(16);

        $data['policyRef'] = 'POL1002';
        $data['effectiveDate'] = date('Y-m-d');
        $data['renewalDate'] = date('Y-m-d', strtotime('+93 days'));
        ImportBrokerPolicy::import($data, 2);
        expect(PolicyService::getActivePolicyAverageDuration(2))->toBe(54);

        $data['policyRef'] = 'POL1003';
        $data['effectiveDate'] = date('Y-m-d');
        $data['renewalDate'] = date('Y-m-d', strtotime('+282 days'));
        ImportBrokerPolicy::import($data, 2);
        expect(PolicyService::getActivePolicyAverageDuration(2))->toBe(130);

        // pending policy
        $data['policyRef'] = 'POL1004';
        $data['effectiveDate'] = date('Y-m-d', strtotime('+6 months'));
        $data['renewalDate'] = date('Y-m-d', strtotime('+18 months'));
        ImportBrokerPolicy::import($data, 2);
        expect(PolicyService::getActivePolicyAverageDuration(2))->toBe(130);

        // expired policy
        $data['policyRef'] = 'POL1005';
        $data['effectiveDate'] = date('Y-m-d', strtotime('-18 months'));
        $data['renewalDate'] = date('Y-m-d', strtotime('-6 months'));
        ImportBrokerPolicy::import($data, 2);
        expect(PolicyService::getActivePolicyAverageDuration(2))->toBe(130);
    });

    it('gets active policy customer count', function() {
        expect(PolicyService::getCustomerCount(1))->toBe(5);
    });

    it('gets policies', function() {
        $policies = PolicyService::getPolicies(1);
        expect(count($policies))->toBe(8);
        expect($policies[0]->broker_policy_ref)->toBe('POL001');
        expect($policies[1]->broker_policy_ref)->toBe('POL002');
        expect($policies[2]->broker_policy_ref)->toBe('POL003');
        expect($policies[3]->broker_policy_ref)->toBe('POL004');
        expect($policies[4]->broker_policy_ref)->toBe('POL005');
        expect($policies[5]->broker_policy_ref)->toBe('POL020');
        expect($policies[6]->broker_policy_ref)->toBe('POL021');
        expect($policies[7]->broker_policy_ref)->toBe('POL022');
        expect($policies[7]->duration)->toBe(0);
    });
});
