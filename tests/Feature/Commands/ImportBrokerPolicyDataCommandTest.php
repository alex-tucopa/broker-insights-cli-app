<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Exception\RuntimeException;
use App\Commands\ImportBrokerPolicyDataCommand;
use App\Models\Insurer;
use App\Models\Policy;
use App\Models\Product;

beforeAll(function() {
    require __DIR__ . '/../../../bootstrap/application.php';
});

describe('ImportBrokerPolicyDataCommand', function() {
    beforeEach(function() {
        require __DIR__ . '/../../../database/create_tables.php';
        require __DIR__ . '/../../../database/insert_initial_data.php';
    });

    afterEach(function() {
        require __DIR__ . '/../../../database/drop_tables.php';
    });

    it('checks input paramaters', function() {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'format' => 'format_1']);
    })->throws(RuntimeException::class, 'Not enough arguments (missing: "filename").');

    it('reports error if broker does not exist', function () {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 9999, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'format_1']);
        expect($tester->getStatusCode())->toBe(Command::INVALID);
        expect($tester->getDisplay())->toBe("Input error Cannot find broker with ID: \"9999\"\n");
    });

    it('reports error if import file does not exist', function () {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/NOT_broker_data_format_1.csv', 'format' => 'format_1']);
        expect($tester->getStatusCode())->toBe(Command::INVALID);
        expect($tester->getDisplay())->toBe("Input error Cannot find input file: \"tests/data/NOT_broker_data_format_1.csv\"\n");
    });

    it('reports error if import format does not exist', function () {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'NOT_format_1']);
        expect($tester->getStatusCode())->toBe(Command::INVALID);
        expect($tester->getDisplay())->toBe("Input error Format is not configured: \"NOT_format_1\"\n");
    });

    it('reports error if import file does not match format', function () {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'format_2']);
        expect($tester->getStatusCode())->toBe(Command::INVALID);
        expect($tester->getDisplay())->toBe("Input error CSV headers do not match format, filename: tests/data/broker_data_format_1.csv\n");
    });

    it('reports an error if the data is invalid', function() {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1_errors.csv', 'format' => 'format_1']);
        expect($tester->getStatusCode())->toBe(Command::SUCCESS);

        $expectedError = "File \"tests/data/broker_data_format_1_errors.csv\" processed with errors:\n";
        $expectedError .= "\t* Error on line 2 - Invalid input for startDate: \"9999999\"\n";
        expect($tester->getDisplay())->toBe($expectedError);
    });

    it('reports error for unrecognised product type', function() {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1_unrecognised_product_type.csv', 'format' => 'format_1']);
        expect($tester->getStatusCode())->toBe(Command::SUCCESS);
        
        $expectedError = "File \"tests/data/broker_data_format_1_unrecognised_product_type.csv\" processed with errors:\n";
        $expectedError .= "\t* Error on line 3 - Cannot find product type: \"NotProductType\"\n";
        expect($tester->getDisplay())->toBe($expectedError);
    });

    it('reports error for unrecognised customer type', function() {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1_unrecognised_customer_type.csv', 'format' => 'format_1']);
        expect($tester->getStatusCode())->toBe(Command::SUCCESS);
        
        $expectedError = "File \"tests/data/broker_data_format_1_unrecognised_customer_type.csv\" processed with errors:\n";
        $expectedError .= "\t* Error on line 2 - Cannot find customer type: \"NotCustomerType\"\n";
        expect($tester->getDisplay())->toBe($expectedError);
    });

    it('imports valid data from file', function() {
        $tester = new CommandTester(new ImportBrokerPolicyDataCommand());
        $tester->execute(['broker_id' => 1, 'filename' => 'tests/data/broker_data_format_1.csv', 'format' => 'format_1']);
        expect($tester->getStatusCode())->toBe(Command::SUCCESS);
        expect($tester->getDisplay())->toBe("File \"tests/data/broker_data_format_1.csv\" processed\n");

        $insurers = Insurer::all();
        expect($insurers->count())->toBe(4);
        expect($insurers[0]->name)->toBe('ABC Insurance');
        expect($insurers[0]->id)->toBe(1);
        expect($insurers[1]->name)->toBe('XYZ Insurers');
        expect($insurers[1]->id)->toBe(2);
        expect($insurers[2]->name)->toBe('PQR Underwriters');
        expect($insurers[2]->id)->toBe(3);
        expect($insurers[3]->name)->toBe('LMN Insurance');
        expect($insurers[3]->id)->toBe(4);

        $products = Product::all();
        expect($products->count())->toBe(5);
        expect($products[0]->name)->toBe('Property Insurance');
        expect($products[0]->insurer_id)->toBe(1);
        expect($products[1]->name)->toBe('Auto Coverage');
        expect($products[1]->insurer_id)->toBe(2);
        expect($products[2]->name)->toBe('Health Insurance');
        expect($products[2]->insurer_id)->toBe(3);
        expect($products[3]->name)->toBe('Property Insurance');
        expect($products[3]->insurer_id)->toBe(4);
        expect($products[4]->name)->toBe('Auto Coverage');
        expect($products[4]->insurer_id)->toBe(3);

        $policies = Policy::all();
        expect($policies->count())->toBe(8);
        expect($policies[0]->broker_policy_ref)->toBe('POL001');
        expect($policies[1]->broker_policy_ref)->toBe('POL002');
        expect($policies[2]->broker_policy_ref)->toBe('POL003');
        expect($policies[3]->broker_policy_ref)->toBe('POL004');
        expect($policies[4]->broker_policy_ref)->toBe('POL005');
        expect($policies[5]->broker_policy_ref)->toBe('POL020');
        expect($policies[6]->broker_policy_ref)->toBe('POL021');
        expect($policies[7]->broker_policy_ref)->toBe('POL022');
    });
});
