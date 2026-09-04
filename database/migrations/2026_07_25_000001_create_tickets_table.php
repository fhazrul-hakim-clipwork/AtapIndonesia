<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('user_email');
            $table->string('user_name');
            $table->string('subject');
            $table->text('message');
            $table->string('status')->default('open');
            $table->timestamps();

            $table->index('user_email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
