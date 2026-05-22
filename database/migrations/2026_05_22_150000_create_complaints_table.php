<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('ticket_number')->unique();
            $table->string('reporter_name');
            $table->string('phone', 30)->nullable();
            $table->string('address')->nullable();
            $table->string('category', 80);
            $table->string('title');
            $table->text('description');
            $table->string('photo_path')->nullable();
            $table->string('status')->default('baru');
            $table->text('admin_reply')->nullable();
            $table->foreignId('replied_by')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'category']);
            $table->foreign('user_id', 'complaint_user_fk')->references('id')->on('users')->nullOnDelete();
            $table->foreign('replied_by', 'complaint_replier_fk')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
