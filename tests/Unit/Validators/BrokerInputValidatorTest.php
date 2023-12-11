<?php

use App\Validators\BrokerInputValidator;

describe('BrokerInputValidator', function() {
    it('passes valid input', function() {
        $validator = new BrokerInputValidator();
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        expect($validator->validate($data))->toBe(true);
    });

    it('fails for missing fields', function() {
        $validator = new BrokerInputValidator();
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        unset($data['policyRef'], $data['insurerDescription']);
        expect($validator->validate($data))->toBe(false);
        expect($validator->getErrors())->toBe([
            "Missing required: policyRef, insurerDescription",
        ]);
    });

    it('fails for invalid data', function() {
        $validator = new BrokerInputValidator();
        $data = require __DIR__ . '/../../data/valid_parsed_broker_input.php';
        $data['startDate'] = '999999';
        $data['amountInsured'] = 'NOT_NUMERIC';
        expect($validator->validate($data))->toBe(false);
        expect($validator->getErrors())->toBe([
            "Invalid input for amountInsured: \"NOT_NUMERIC\"",
            "Invalid input for startDate: \"999999\"",
        ]);
    });
});
