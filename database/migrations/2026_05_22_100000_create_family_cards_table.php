<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_cards', function (Blueprint $table) {
            $table->id();
            $table->string('number', 16)->unique();
            $table->string('head_name')->nullable();
            $table->string('address')->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('hamlet')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_cards');
    }
};
