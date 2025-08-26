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
        Schema::create('grants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->string('cover')->nullable();
            $table->string('title');
            $table->text('short_description');
            $table->text('description');
            $table->string('address')->nullable();
            $table->string('settlement')->nullable();
            $table->date('deadline');
            $table->json('docs')->nullable();
            $table->string('web')->nullable();
            $table->string('telegram')->nullable();
            $table->string('vk')->nullable();
            $table->text('conditions')->nullable();
            $table->text('requirements')->nullable();
            $table->string('reward')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grants');
    }
};
