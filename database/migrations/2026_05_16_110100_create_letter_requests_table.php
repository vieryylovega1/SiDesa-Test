<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->nullable()->constrained()->nullOnDelete();
            $table->string('applicant_name');
            $table->string('letter_type');
            $table->text('purpose');
            $table->string('phone')->nullable();
            $table->string('status')->default('Diajukan');
            $table->string('letter_number')->nullable();
            $table->date('requested_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_requests');
    }
};
