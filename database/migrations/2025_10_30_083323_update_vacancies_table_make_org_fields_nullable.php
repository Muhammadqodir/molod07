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
        Schema::table('vacancies', function (Blueprint $table) {
            $table->string('org_name')->nullable()->default('Не указано')->change();
            $table->string('org_phone')->nullable()->default('Не указан')->change();
            $table->string('org_email')->nullable()->default('noreply@example.com')->change();
            $table->text('org_address')->nullable()->change(); // TEXT поля не могут иметь значения по умолчанию
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacancies', function (Blueprint $table) {
            $table->string('org_name')->nullable(false)->change();
            $table->string('org_phone')->nullable(false)->change();
            $table->string('org_email')->nullable(false)->change();
            $table->text('org_address')->nullable(false)->change();
        });
    }
};
