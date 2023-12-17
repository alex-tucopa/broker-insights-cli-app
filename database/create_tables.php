<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

$builder = Capsule::schema();

if (!$builder->hasTable('broker')) {
    $builder->create('broker', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100)->unique;
        $table->timestamps();

        $table->unique('name');
    });
}

if (!$builder->hasTable('insurer')) {
    $builder->create('insurer', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->string('description', 255);
        $table->timestamps();

        $table->unique('name');
    });
}

if (!$builder->hasTable('customer_type')) {
    $builder->create('customer_type', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->timestamps();

        $table->unique('name');
    });
}

if (!$builder->hasTable('product_type')) {
    $builder->create('product_type', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->timestamps();

        $table->unique('name');
    });
}

if (!$builder->hasTable('business_event_type')) {
    $builder->create('business_event_type', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->timestamps();

        $table->unique('name');
    });
}

if (!$builder->hasTable('product')) {
    $builder->create('product', function (Blueprint $table) {
        $table->id();
        $table->foreignId('insurer_id')->constrained(table: 'insurer');
        $table->foreignId('product_type_id')->constrainer(table: 'product_type');
        $table->string('name', 100);
        $table->timestamps();

        $table->unique(['insurer_id', 'name']);
        $table->index('insurer_id');
    });
}

if (!$builder->hasTable('policy')) {
    $builder->create('policy', function (Blueprint $table) {
        $table->string('broker_policy_ref', 50);
        $table->foreignId('broker_id')->constrained(table: 'broker');
        $table->foreignId('product_id')->constrained(table: 'product');
        $table->foreignId('customer_type_id')->constrained(table: 'customer_type');
        $table->foreignId('business_event_type_id')->constrained(table: 'business_event_type');
        $table->string('broker_customer_ref', 50);
        $table->string('insurer_policy_ref', 50);
        $table->string('primary_policy_ref', 50);
        $table->date('start_date');
        $table->date('end_date');
        $table->date('effective_date');
        $table->date('renewal_date');
        $table->decimal('amount_insured', 10, 2);
        $table->decimal('premium', 10, 2);
        $table->decimal('policy_fee', 7, 2);
        $table->decimal('admin_fee', 7, 2);
        $table->decimal('broker_fee', 7, 2);
        $table->decimal('premium_tax', 7, 2);
        $table->timestamps();

        $table->primary(['broker_id', 'broker_policy_ref']);
        $table->index('broker_id');
        $table->index('product_id');
        $table->index('insurer_policy_ref');
        $table->index('primary_policy_ref');
        $table->index('start_date');
        $table->index('end_date');
        $table->index('effective_date');
        $table->index('renewal_date');
    });
}
