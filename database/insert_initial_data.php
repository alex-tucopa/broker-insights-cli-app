<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::table('broker')->insertOrIgnore([
    ['name' => 'Broker One'],
    ['name' => 'Broker Two'],
]);

Capsule::table('customer_type')->insertOrIgnore([
    ['name' => 'Individual'],
    ['name' => 'Corporate'],
]);

Capsule::table('product_type')->insertOrIgnore([
    ['name' => 'Auto'],
    ['name' => 'Health'],
    ['name' => 'Property'],
]);
