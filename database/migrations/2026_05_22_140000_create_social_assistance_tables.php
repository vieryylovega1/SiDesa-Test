<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_assistance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('social_assistance_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id');
            $table->foreignId('social_assistance_category_id');
            $table->string('status')->default('active');
            $table->date('registered_at');
            $table->text('eligibility_note')->nullable();
            $table->foreignId('created_by')->nullable();
            $table->timestamps();

            $table->unique(['resident_id', 'social_assistance_category_id'], 'bansos_recipient_category_unique');
            $table->index('status');
            $table->foreign('resident_id', 'bansos_recipient_resident_fk')->references('id')->on('residents')->cascadeOnDelete();
            $table->foreign('social_assistance_category_id', 'bansos_recipient_category_fk')->references('id')->on('social_assistance_categories')->cascadeOnDelete();
            $table->foreign('created_by', 'bansos_recipient_creator_fk')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('social_assistance_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_assistance_recipient_id');
            $table->date('distributed_at');
            $table->string('period')->nullable();
            $table->decimal('amount', 14, 2)->nullable();
            $table->string('status')->default('disalurkan');
            $table->text('description')->nullable();
            $table->foreignId('recorded_by')->nullable();
            $table->timestamps();

            $table->index(['distributed_at', 'status']);
            $table->foreign('social_assistance_recipient_id', 'bansos_history_recipient_fk')->references('id')->on('social_assistance_recipients')->cascadeOnDelete();
            $table->foreign('recorded_by', 'bansos_history_recorder_fk')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_assistance_histories');
        Schema::dropIfExists('social_assistance_recipients');
        Schema::dropIfExists('social_assistance_categories');
    }
};
