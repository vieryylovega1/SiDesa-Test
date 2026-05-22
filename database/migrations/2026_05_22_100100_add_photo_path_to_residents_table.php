<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->string('photo_path')->nullable()->after('education');
            $table->index('kk');
            $table->index(['rt', 'rw']);
            $table->index('gender');
            $table->index('education');
            $table->index('occupation');
        });
    }

    public function down(): void
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropIndex(['kk']);
            $table->dropIndex(['rt', 'rw']);
            $table->dropIndex(['gender']);
            $table->dropIndex(['education']);
            $table->dropIndex(['occupation']);
            $table->dropColumn('photo_path');
        });
    }
};
