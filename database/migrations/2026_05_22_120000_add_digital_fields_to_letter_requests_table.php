<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('letter_requests', function (Blueprint $table) {
            $table->string('letter_code')->nullable()->after('letter_type');
            $table->string('verification_code')->nullable()->unique()->after('letter_number');
            $table->string('digital_signature')->nullable()->after('verification_code');
            $table->foreignId('signed_by')->nullable()->after('digital_signature')->constrained('users')->nullOnDelete();
            $table->timestamp('signed_at')->nullable()->after('signed_by');
            $table->json('template_data')->nullable()->after('purpose');
        });
    }

    public function down(): void
    {
        Schema::table('letter_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('signed_by');
            $table->dropColumn([
                'letter_code',
                'verification_code',
                'digital_signature',
                'signed_at',
                'template_data',
            ]);
        });
    }
};
