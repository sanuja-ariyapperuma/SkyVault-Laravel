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
        Schema::create('passports', function (Blueprint $table) {
        $table->uuid('id')->primary();

        $table->string('passport_number')->unique()->index();
        $table->string('last_name')->index();
        $table->string('other_names')->index();

        $table->enum('gender', ['male', 'female', 'other']);
        $table->date('date_of_birth');
        $table->date('issue_date');
        $table->date('expiry_date');

        $table->string('place_of_birth');
        $table->string('place_of_issue');

        $table->foreignUuid('customer_id')
              ->constrained('customers')
              ->cascadeOnDelete();

        $table->foreignUuid('nationality_id')
              ->constrained('nationalities')
              ->restrictOnDelete();

        $table->foreignUuid('country_of_passport_id')
              ->constrained('countries')
              ->restrictOnDelete();

        $table->boolean('is_primary')->default(false);

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
