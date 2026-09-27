<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {

            $table->enum('discount_type', [
                'none',
                'percentage',
                'fixed'
            ])
                ->default('none')
                ->after('price');

            $table->decimal('discount_value', 10, 2)
                ->default(0)
                ->after('discount_type');

            $table->timestamp('discount_start_at')
                ->nullable()
                ->after('discount_value');

            $table->timestamp('discount_end_at')
                ->nullable()
                ->after('discount_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {

            $table->dropColumn([
                'discount_type',
                'discount_value',
                'discount_start_at',
                'discount_end_at',
            ]);
        });
    }
};