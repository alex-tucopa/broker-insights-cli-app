<?php

namespace App\Services;

use Exception;
use Illuminate\Database\Capsule\Manager as Capsule;
use App\Exceptions\ImportBrokerPolicyDataException;
use App\Models\Broker;
use App\Models\BusinessEventType;
use App\Models\CustomerType;
use App\Models\Insurer;
use App\Models\Policy;
use App\Models\Product;
use App\Models\ProductType;

class ImportBrokerPolicy
{
    public static function import(array $data, int $brokerId): void
    {
        $productType = ProductType::where('name', $data['productType'])->first();
        $customerType = CustomerType::where('name', $data['customerType'])->first();

        if (!$customerType) {
            throw new ImportBrokerPolicyDataException("Cannot find customer type: \"{$data['customerType']}\"");
        }

        if (!$productType) {
            throw new ImportBrokerPolicyDataException("Cannot find product type: \"{$data['productType']}\"");
        }

        try {
            Capsule::beginTransaction();

            $insurer = Insurer::firstOrCreate([
                'name' => $data['insurerName'],
                'description' => $data['insurerDescription']
            ]);

            $businessEventType = BusinessEventType::firstOrCreate([
                'name' => $data['businessEventType']
            ]);

            $product = Product::firstOrCreate([
                'insurer_id' => $insurer->id,
                'product_type_id' => $productType->id,
                'name' => $data['productName'],
            ]);

            Policy::create([
                'broker_id' => $brokerId,
                'broker_policy_ref' => $data['policyRef'],
                'product_id' => $product->id,
                'business_event_type_id' => $businessEventType->id,
                'customer_type_id' => $customerType->id,
                'broker_customer_ref' => $data['brokerCustomerRef'],
                'insurer_policy_ref' => $data['insurerPolicyRef'],
                'primary_policy_ref' => $data['primaryPolicyRef'],
                'start_date' => $data['startDate'],
                'end_date' => $data['endDate'],
                'effective_date' => $data['effectiveDate'],
                'renewal_date' => $data['renewalDate'],
                'amount_insured' => $data['amountInsured'],
                'premium' => $data['premium'],
                'premium_tax' => $data['premiumTax'],
                'policy_fee' => $data['policyFee'],
                'admin_fee' => $data['adminFee'],
                'broker_fee' => $data['brokerFee'],
            ]);


            Capsule::commit();
        } catch(Exception $e) {
            Capsule::rollback();
            throw $e;
        }
    }
}
