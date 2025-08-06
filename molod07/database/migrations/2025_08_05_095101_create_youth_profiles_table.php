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
        Schema::create('youth_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('bday')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('f_name')->nullable();
            $table->string('pic')->nullable();
            $table->string('sex')->nullable();
            $table->string('telegram')->nullable();
            $table->string('vk')->nullable();
            $table->string('citizenship')->nullable();
            $table->text('about')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('youth_profiles');
    }
};
