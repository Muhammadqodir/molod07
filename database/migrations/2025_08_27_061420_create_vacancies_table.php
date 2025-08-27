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
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->integer('salary_from')->nullable();
            $table->integer('salary_to')->nullable();
            $table->boolean('salary_negotiable')->default(false);
            $table->string('type');
            $table->string('experience');
            $table->string('org_name');
            $table->string('org_phone');
            $table->string('org_email');
            $table->text('org_address');
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
        Schema::dropIfExists('vacancies');
    }
};
