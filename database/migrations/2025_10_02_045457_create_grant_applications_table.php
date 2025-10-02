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
        Schema::create('grant_applications', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('grant_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->string('email');
            $table->text('comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreign('grant_id')->references('id')->on('grants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grant_applications');
    }
};
