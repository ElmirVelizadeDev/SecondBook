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
        Schema::table('message_replies', function (Blueprint $table) {
            $table->timestamp('read_at')
                ->nullable()
                ->after('reply');

            $table->index(['user_id', 'sender_type', 'read_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('message_replies', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'sender_type', 'read_at']);
            $table->dropColumn('read_at');
        });
    }
};
