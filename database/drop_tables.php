<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$builder = Capsule::schema();

$builder->dropIfExists('policy');
$builder->dropIfExists('broker');
$builder->dropIfExists('customer_type');
$builder->dropIfExists('business_event_type');
$builder->dropIfExists('product');
$builder->dropIfExists('insurer');
$builder->dropIfExists('product_type');
