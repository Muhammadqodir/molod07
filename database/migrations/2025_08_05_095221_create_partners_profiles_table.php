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
        Schema::create('partners_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('pic')->nullable();
            $table->string('org_name')->nullable();
            $table->string('org_address')->nullable();
            $table->string('person_name')->nullable();
            $table->string('person_lname')->nullable();
            $table->string('person_fname')->nullable();
            $table->string('phone')->nullable();
            $table->string('web')->nullable();
            $table->string('telegram')->nullable();
            $table->string('vk')->nullable();
            $table->text('about')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners_profiles');
    }
};
