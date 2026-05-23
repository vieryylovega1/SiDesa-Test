<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('current_session_id')->nullable()->after('is_active');
            $table->timestamp('last_login_at')->nullable()->after('current_session_id');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->unsignedInteger('login_count')->default(0)->after('last_login_ip');
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('login_count');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'current_session_id',
                'last_login_at',
                'last_login_ip',
                'login_count',
                'failed_login_attempts',
                'locked_until',
            ]);
        });
    }
};
