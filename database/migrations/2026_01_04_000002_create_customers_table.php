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
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name');
            $table->string('last_name');
            $table->foreignUuid('user_id')
                ->index()
                ->constrained('users')
                ->onDelete('restrict');
            $table->enum('salutation', ['Mr', 'Mrs', 'Miss', 'Dr', 'Prof', 'Rev', 'Hon']);
            $table->enum('communication_method', ['email', 'whatsapp', 'none'])->default('none');
            $table->foreignUuid('family_group_id')->nullable()
                ->constrained('family_groups')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
