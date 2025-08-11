<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->string('cover')->nullable();
            $table->string('title')->nullable();
            $table->string('short_description')->nullable();
            $table->text('description')->nullable();

            $table->string('address')->nullable();
            $table->string('settlement')->nullable();

            $table->date('start')->nullable();
            $table->date('end')->nullable();

            $table->integer('supervisor_id')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->string('supervisor_l_name')->nullable();
            $table->string('supervisor_phone')->nullable();
            $table->string('supervisor_email')->nullable();

            $table->json('docs')->nullable();
            $table->json('images')->nullable();
            $table->json('videos')->nullable();

            $table->string('web')->nullable();
            $table->string('telegram')->nullable();
            $table->string('vk')->nullable();

            $table->json('roles')->nullable();

            $table->string('status')->default('pending');
            $table->integer('admin_id')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
