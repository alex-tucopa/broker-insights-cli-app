<?php

use Illuminate\Database\UniqueConstraintViolationException;
use Symfony\Component\Console\Tester\CommandTester;
use App\Commands\ImportBrokerPolicyDataCommand;
use App\Exceptions\ImportBrokerPolicyDataException;
use App\Services\ImportBrokerPolicy;
use App\Models\Policy;

beforeAll(function() {
    require __DIR__ . '/../../../bootstrap/application.php';
});

describe('ImportBrokerPolicy', function() {
    beforeEach(function() {
        require __DIR__ . '/../../../database/create_tables.php';
        require __DIR__ . '/../../../database/insert_initial_data.php';

        $import = new CommandTester(new ImportBrokerPolicyDataCommand());
        $import->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'format_1']);
    });

    afterEach(function() {
        require __DIR__ . '/../../../database/drop_tables.php';
    });

    it('imports policy', function() {
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        ImportBrokerPolicy::import($data, 1);

        $insertedPolicy = Policy::where('broker_policy_ref', 'POL999')->where('broker_id', 1)->first();
        expect($insertedPolicy->broker_policy_ref)->toBe('POL999');
    });

    it('throws exception for unrecognised customer type', function() {
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        $data['customerType'] = 'NotCustomerType';
        ImportBrokerPolicy::import($data, 1);
    })->throws(ImportBrokerPolicyDataException::class, 'Cannot find customer type: "NotCustomerType"');

    it('throws exception for unrecognised product type', function() {
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        $data['productType'] = 'NotProductType';
        ImportBrokerPolicy::import($data, 1);
    })->throws(ImportBrokerPolicyDataException::class, 'Cannot find product type: "NotProductType"');

    it('throws exception for duplicate policy', function() {
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        $data['policyRef'] = 'POL001';
        ImportBrokerPolicy::import($data, 1);
    })->throws(UniqueConstraintViolationException::class);
});
