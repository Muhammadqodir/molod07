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
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('category');
            $table->string('cover')->nullable();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->string('length')->nullable(); // Duration like "25:30"
            $table->integer('episode_numbers')->nullable();
            $table->string('link')->nullable();
            $table->string('status')->default('pending');
            $table->integer('admin_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
