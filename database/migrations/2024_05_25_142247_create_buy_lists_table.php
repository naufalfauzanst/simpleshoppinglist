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
        Schema::create('buy_lists', function (Blueprint $table) {
            $table->id();
            $table->longText('item');
            $table->unsignedBigInteger('quantity');
            $table->enum('status', ['Next', 'Bought', 'Not Found'])->default('Next');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buy_lists');
    }
};
