<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pembeli')->after('password');
            $table->string('google_id')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('google_id');
            $table->string('kota')->nullable()->after('avatar');
            $table->string('telepon')->nullable()->after('kota');
            $table->string('alamat')->nullable()->after('telepon');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'google_id', 'avatar', 'kota', 'telepon', 'alamat']);
        });
    }
};
