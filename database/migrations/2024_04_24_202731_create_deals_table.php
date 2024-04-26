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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('platform_id')->constrained();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->float('price');
            $table->float('else_price')->nullable();
            $table->integer('products_total')->default(100);
            $table->integer('products_left')->default(100);
            $table->string('image')->nullable();
            $table->string('url');
            $table->boolean('invalid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
