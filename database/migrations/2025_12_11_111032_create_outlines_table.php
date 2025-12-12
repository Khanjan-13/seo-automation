<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('outlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('content_type');
            $table->string('topic');
            $table->string('brand_name')->nullable();
            $table->string('keywords')->nullable();
            $table->string('location')->nullable();
            $table->string('model');
            $table->json('generated_outlines');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('normal_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlines');
    }
};
