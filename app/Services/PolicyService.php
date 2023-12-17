<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class PolicyService
{
    public static function getActivePolicyCountAndSumInsured(?int $brokerId): array
    {
        $today = self::today();

        $query = Capsule::table('policy')
            ->selectRaw('COUNT(*) AS `count`, FLOOR(SUM(`amount_insured`)) AS `amount_insured`')
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
        $today = self::today();

        $dateDifferenceSubQuery = Capsule::table('policy')
            ->selectRaw(self::getRawSql('policy_duration.sql'))
            ->where('effective_date', '<=', $today)
            ->where('renewal_date', '>', $today);

        if ($brokerId) {
            $dateDifferenceSubQuery->where('broker_id', $brokerId);
        }
            
        $result = Capsule::select(
            "SELECT ROUND(AVG(`duration`)) AS `average_duration` FROM ({$dateDifferenceSubQuery->toSql()}) as `policy_duration`",
            $dateDifferenceSubQuery->getBindings()
        )[0];

        return $result->average_duration ?? 0;
    }

    public static function getCustomerCount(?int $brokerId): int
    {
        $today = self::today();

        $distinctCustomersSubQuery = Capsule::table('policy')
            ->distinct()
            ->select(['broker_id', 'broker_customer_ref', 'customer_type_id'])
            ->where('effective_date', '<=', $today)
            ->where('renewal_date', '>', $today);


        if ($brokerId) {
            $distinctCustomersSubQuery->where('broker_id', $brokerId);
        }

        $result = Capsule::select(
            "SELECT COUNT(*) AS `count` FROM ({$distinctCustomersSubQuery->toSql()}) AS `customers`",
            $distinctCustomersSubQuery->getBindings(),
        )[0];

        return $result->count;
    }

    public static function getPolicies(?int $brokerId): array
    {
        $querySql = self::getRawSql('select_policies.sql');

        $today = self::today();
        
        $bindings = [
            ':today_1' => $today,
            ':today_2' => $today,
            ':today_3' => $today,
            ':today_4' => $today,
        ];

        if ($brokerId) {
            $querySql .= 'WHERE `broker_id` = :broker_id';
            $bindings[':broker_id'] = $brokerId;
        }

        $querySql .= ' ORDER BY `policy`.`broker_policy_ref`';


        return Capsule::select($querySql, $bindings);
    }

    private static function today(): string
    {
        return date('Y-m-d');
    }

    private static function getRawSql($filename): string
    {
        $driver = Capsule::table('policy')->getConnection()->getDriverName();
        return file_get_contents(__DIR__ . "/raw-sql/{$driver}/{$filename}");
    }
}