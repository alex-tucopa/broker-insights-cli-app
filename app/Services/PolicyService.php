<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class PolicyService
{
    public static function getActivePolicyCountAndSumInsured(?int $brokerId): array
    {
        $today = date('Y-m-d');

        $query = Capsule::table('policy')
            ->selectRaw('COUNT(*) AS `count`, SUM(`amount_insured`) AS `amount_insured`')
            ->where('effective_date', '<=', $today)
            ->where('renewal_date', '>', $today);

        if ($brokerId) {
            $query->where('broker_id', $brokerId);
        }

        $result = $query->first();

        return [
            'activePolicyCount' => $result->count,
            'activePolicySumInsured' => $result->amount_insured,
        ];
    }

    public static function getActivePolicyAverageDuration(?int $brokerId): int
    {
        $dateDifferenceSubQuery = Capsule::table('policy')
            ->selectRaw('(FLOOR(JULIANDAY(`renewal_date`)) - FLOOR(JULIANDAY(DATE("now")))) - 1 AS `duration`')
            ->whereRaw('`effective_date` <= :today AND `renewal_date` > :today');

        $bindings = [
            ':today' => date('Y-m-d'),
        ];

        if ($brokerId) {
            $dateDifferenceSubQuery->whereRaw('broker_id = :broker_id');
            $bindings[':broker_id'] = $brokerId;
        }
            
        $result = Capsule::select(
            "SELECT ROUND(AVG(`duration`)) AS `average_duration` FROM ({$dateDifferenceSubQuery->toSql()}) as `policy_duration`",
            $bindings,
        )[0];

        return $result->average_duration ?? 0;
    }

    public static function getCustomerCount(?int $brokerId): int
    {
        $distinctCustomersSubQuery = Capsule::table('policy')
            ->distinct()
            ->select(['broker_id', 'broker_customer_ref', 'customer_type_id'])
            ->whereRaw('`effective_date` <= :today AND `renewal_date` > :today');

         $bindings = [
            ':today' => date('Y-m-d'),
        ];

        if ($brokerId) {
            $distinctCustomersSubQuery->whereRaw('broker_id = :broker_id');
            $bindings[':broker_id'] = $brokerId;
        }

        $result = Capsule::select(
            "SELECT COUNT(*) AS `count` FROM ({$distinctCustomersSubQuery->toSql()}) AS `customers`",
            $bindings,
        )[0];

        return $result->count;
    }

    public static function getPolicies(?int $brokerId): array
    {
        $querySql = file_get_contents(__DIR__ . '/queries/select_policies.sql');

        $bindings = [
            ':today' => date('Y-m-d'),
        ];

        if ($brokerId) {
            $querySql .= 'WHERE `broker_id` = :broker_id';
            $bindings[':broker_id'] = $brokerId;
        }

        $querySql .= ' ORDER BY `policy`.`broker_policy_ref`';


        return Capsule::select($querySql, $bindings);
    }
}