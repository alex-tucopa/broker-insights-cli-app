<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use App\Commands\ImportBrokerPolicyDataCommand;
use App\Commands\PolicyReportCommand;

beforeAll(function() {
    require __DIR__ . '/../../../bootstrap/application.php';
});

describe('PolicyReportCommand', function() {
    beforeEach(function() {
        require __DIR__ . '/../../../database/create_tables.php';
        require __DIR__ . '/../../../database/insert_initial_data.php';

        $import = new CommandTester(new ImportBrokerPolicyDataCommand());
        $import->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'format_1']);
        $import->execute(['broker_id' => 2, 'filename' => 'tests/data/broker_data_format_2.csv', 'format' => 'format_2']);
    });

    afterEach(function() {
        require __DIR__ . '/../../../database/drop_tables.php';
    });

    it('generates policy report for all brokers', function() {
        $tester = new CommandTester(new PolicyReportCommand());
        $tester->execute([]);
        expect($tester->getDisplay())->toMatch('/Active Policies[|\s]*Sum Insured[|\s]*Average Duration \(days\)[|\s]*Customers/');
        expect($tester->getDisplay())->toMatch('/8[|\s]*5,745,500[|\s]*[\d]*[|\s]*8/');
    });

    it('generates policy report for broker ID', function() {
        $tester = new CommandTester(new PolicyReportCommand());
        $tester->execute(['--broker-id' => 2]);
        expect($tester->getDisplay())->toMatch('/Active Policies[|\s]*Sum Insured[|\s]*Average Duration \(days\)[|\s]*Customers/');
        expect($tester->getDisplay())->toMatch('/3[|\s]*2,095,500[|\s]*[\d]*[|\s]*3/');
        expect($tester->getDisplay())->toMatch('/Broker Ref[|\s]*Effective Date[|\s]*Renewal Date[|\s]*Active[|\s]*Duration[|\s]*Amount[|\s]*Premium[|\s]*Insurer Name[|\s]*Product Type[|\s]*Customer Type/');
        expect($tester->getDisplay())->toMatch('/REF079[|\s]*2023-04-10[|\s]*2024-04-10[|\s]*Y[|\s]*[\d]*[|\s]*645000[|\s]*5600[|\s]*Z6A Underwriters[|\s]*Property[|\s]*Individual/');
    });

    it('generates policy report for broker name', function() {
        $tester = new CommandTester(new PolicyReportCommand());
        $tester->execute(['--broker-name' => "Broker One"]);
        expect($tester->getDisplay())->toMatch('/5[|\s]*3,650,000[|\s]*[\d]*[|\s]*5/');
        expect($tester->getDisplay())->toMatch('/POL001[|\s]*2023-01-15[|\s]*2024-01-15[|\s]*Y[|\s]*[\d]*[|\s]*1000000[|\s]*8000[|\s]*ABC Insurance[|\s]*Property[|\s]*Corporate/');
    });

    it('shows error for non-existent broker', function() {
        $tester = new CommandTester(new PolicyReportCommand());
        $tester->execute(['--broker-name' => "Not A Broker"]);
        expect($tester->getStatusCode())->toBe(Command::INVALID);
        expect($tester->getDisplay())->toBe("Input error Could not find broker: \"Not A Broker\"\n");
    });
});
