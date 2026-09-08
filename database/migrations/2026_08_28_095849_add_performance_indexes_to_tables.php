<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->index('name');
            $table->index('generic_name');
            $table->index('barcode');
            $table->index('category_id');
            $table->index('manufacturer_id');
        });

        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->index('medicine_id');
            $table->index('expiry_date');
            $table->index('batch_number');
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->index('invoice_number');
            $table->index('customer_id');
            $table->index('created_at');
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->index('sale_id');
            $table->index('medicine_id');
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->index('invoice_number');
            $table->index('supplier_id');
            $table->index('purchase_date');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->index('purchase_id');
            $table->index('medicine_id');
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->index('name');
            $table->index('phone');
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->index('supplier_name');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['generic_name']);
            $table->dropIndex(['barcode']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['manufacturer_id']);
        });

        Schema::table('medicine_batches', function (Blueprint $table) {
            $table->dropIndex(['medicine_id']);
            $table->dropIndex(['expiry_date']);
            $table->dropIndex(['batch_number']);
        });

        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['invoice_number']);
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['created_at']);
        });

        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
            $table->dropIndex(['medicine_id']);
        });

        Schema::table('purchases', function (Blueprint $table) {
            $table->dropIndex(['invoice_number']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['purchase_date']);
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropIndex(['purchase_id']);
            $table->dropIndex(['medicine_id']);
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['phone']);
        });

        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['supplier_name']);
            $table->dropIndex(['phone']);
        });
    }
};