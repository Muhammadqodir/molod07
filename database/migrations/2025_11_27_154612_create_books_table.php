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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('cover')->nullable(); // Book cover image
            $table->string('title'); // Book title
            $table->string('author'); // Book author
            $table->text('description')->nullable(); // Book annotation/description
            $table->string('link')->nullable(); // Electronic version link or "Read" button URL
            $table->string('status')->default('pending'); // pending, approved, archived
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
