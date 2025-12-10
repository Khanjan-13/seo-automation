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
        Schema::table('chats', function (Blueprint $table) {
            $table->string('model')->nullable()->after('prompt');
            $table->unsignedInteger('input_tokens')->default(0)->after('model');
            $table->unsignedInteger('output_tokens')->default(0)->after('input_tokens');
            $table->decimal('cost', 10, 6)->default(0)->after('output_tokens');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropColumn(['model', 'input_tokens', 'output_tokens', 'cost']);
        });
    }
};
