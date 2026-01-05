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
        Schema::create('visas', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('visa_number')->unique();
            $table->string('visa_type');

            $table->date('issue_date');
            $table->string('issue_place')->index();
            $table->date('expiry_date');

            $table->foreignUuid('customer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignUuid('country_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignUuid('passport_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visas');
    }
};
