<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('kk', 16);
            $table->string('name');
            $table->string('gender');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('religion')->default('Islam');
            $table->string('occupation')->nullable();
            $table->string('marital_status')->default('Belum Kawin');
            $table->string('address');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('hamlet')->nullable();
            $table->string('status')->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
