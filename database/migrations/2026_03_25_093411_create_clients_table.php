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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Añadimos campos
            $table->foreignId('user_id')
                ->constrained('users')
                // Si borro un usuario, que se borre el paciente también
                ->onDelete('cascade');

            $table->foreignId('client_category_id')
                ->nullable()
                ->constrained('client_categories')
                ->onDelete('set null');

            $table->string('allergies')
                ->nullable();

            $table->string('chronic_conditions')
                ->nullable();

            $table->string('surgical_history')
                ->nullable();

            $table->string('family_history')
                ->nullable();

            $table->string('observations')
                ->nullable();

            $table->string('emergency_contact_name')
                ->nullable();

            $table->string('emergency_contact_phone')
                ->nullable();

            $table->string('emergency_contact_relationship')
                ->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
