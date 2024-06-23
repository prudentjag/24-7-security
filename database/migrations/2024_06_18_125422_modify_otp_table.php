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
             $table->string('email_token')->after('pin_id');
            $table->timestamp('expires_at')->nullable()->after('user_id');
            $table->integer('pin_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otps', function (Blueprint $table) {
            $table->dropColumn('email_token');
            $table->dropColumn('expires_at');
            $table->integer('pin_id')->nullable(false)->change();
        });
    }
};
