<?php

namespace App\Validators;

class BrokerInputValidator extends Validator
{
    protected array $rules = [
        'policyRef' => [
            'minLength|5',
            'maxLength|10',
        ],
        'insurerPolicyRef' => [
            'minLength|5',
            'maxLength|10',
        ],
        'primaryPolicyRef' => [
            'minLength|5',
            'maxLength|10',
        ],
        'productName' => 'string',
        'productType' => 'string',
        'startDate' => 'dateFormat',
        'endDate' => 'dateFormat',
        'effectiveDate' => 'dateFormat',
        'renewalDate' => 'dateFormat',
        'amountInsured' => 'numeric',
        'premium' => 'numeric',
        'premiumTax' => 'numeric',
        'brokerFee' => 'numeric',
        'policyFee' => 'numeric',
        'adminFee' => 'numeric',
        'brokerCustomerRef' => [
            'minLength|5',
            'maxLength|10',
        ],
        'customerType' => 'string',
        'insurerName' => 'string',
        'insurerDescription' => 'string',
        'businessEventType' => 'string',
    ];
}
