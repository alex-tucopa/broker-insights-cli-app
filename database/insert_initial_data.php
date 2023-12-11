<?php

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::table('broker')->insert([
    ['name' => 'Broker One'],
    ['name' => 'Broker Two'],
]);

Capsule::table('customer_type')->insert([
    ['name' => 'Individual'],
    ['name' => 'Corporate'],
]);

Capsule::table('product_type')->insert([
    ['name' => 'Auto'],
    ['name' => 'Health'],
    ['name' => 'Property'],
]);
