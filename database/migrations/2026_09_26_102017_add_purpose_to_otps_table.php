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
        Schema::table('otps', function (Blueprint $table) {
            $table->string('purpose')
                ->default('password_reset')
                ->after('email');

            $table->index([
                'email',
                'purpose',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            $table->dropIndex([
                'email',
                'purpose',
            ]);

            $table->dropColumn('purpose');
        });
    }
};

