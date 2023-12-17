<?php

use App\Services\DataTransforms;

return [
    'format_1' => [
        'format' => 'csv',
        'map' => [
            'PolicyNumber' => 'policyRef',
            'InsuredAmount' => 'amountInsured',
            'StartDate' => [
                'name' => 'startDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'EndDate' => [
                'name' => 'endDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'AdminFee' => 'adminFee',
            'BusinessDescription' => 'insurerDescription',
            'BusinessEvent' => 'businessEventType',
            'ClientType' => 'customerType',
            'ClientRef' => 'brokerCustomerRef',
            'Commission' => 'brokerFee',
            'EffectiveDate' => [
                'name' => 'effectiveDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'InsurerPolicyNumber' => 'insurerPolicyRef',
            'IPTAmount' => 'premiumTax',
            'Premium' => 'premium',
            'PolicyFee' => 'policyFee',
            'PolicyType' => 'productType',
            'Insurer' => 'insurerName',
            'Product' => 'productName',
            'RenewalDate' => [
                'name' => 'renewalDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'RootPolicyRef' => 'primaryPolicyRef',
        ],
    ],
    'format_2' => [
        'format' => 'csv',
        'map' => [
            'PolicyRef' => 'policyRef',
            'CoverageAmount' => 'amountInsured',
            'ExpirationDate' => [
                'name' => 'endDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'AdminCharges' => 'adminFee',
            'InitiationDate' => [
                'name' => 'startDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'CompanyDescription' => 'insurerDescription',
            'ContractEvent' => 'businessEventType',
            'ConsumerID' => 'brokerCustomerRef',
            'BrokerFee' => 'brokerFee',
            'ActivationDate' => [
                'name' => 'effectiveDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'ConsumerCategory' => 'customerType',
            'InsuranceCompanyRef' => 'insurerPolicyRef',
            'TaxAmount' => 'premiumTax',
            'CoverageCost' => 'premium',
            'ContractFee' => 'policyFee',
            'ContractCategory' => 'productType',
            'Underwriter' => 'insurerName',
            'NextRenewalDate' => [
                'name' => 'renewalDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'PrimaryPolicyRef' => 'primaryPolicyRef',
            'InsurancePlan' => 'productName',
        ],
    ],
    'format_3' => [
        'format' => 'json',
        'map' => [
            'PolicyNumber' => 'policyRef',
            'InsuredAmount' => 'amountInsured',
            'StartDate' => [
                'name' => 'startDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'EndDate' => [
                'name' => 'endDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'AdminFee' => 'adminFee',
            'BusinessDescription' => 'insurerDescription',
            'BusinessEvent' => 'businessEventType',
            'ClientType' => 'customerType',
            'ClientRef' => 'brokerCustomerRef',
            'Commission' => 'brokerFee',
            'EffectiveDate' => [
                'name' => 'effectiveDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'InsurerPolicyNumber' => 'insurerPolicyRef',
            'IPTAmount' => 'premiumTax',
            'Premium' => 'premium',
            'PolicyFee' => 'policyFee',
            'PolicyType' => 'productType',
            'Insurer' => 'insurerName',
            'Product' => 'productName',
            'RenewalDate' => [
                'name' => 'renewalDate',
                'transform' => fn($date) => DataTransforms::dateFormat($date),
            ],
            'RootPolicyRef' => 'primaryPolicyRef',
        ],
    ],
];
