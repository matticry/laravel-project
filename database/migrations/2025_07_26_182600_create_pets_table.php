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
        Schema::create('tbl_pet', function (Blueprint $table) {
            // Primary Key
            $table->id('id_pet');

            // Campos básicos de la mascota
            $table->string('name_pet', 250)->charset('utf8mb3');
            $table->string('species_pet', 250)->charset('utf8mb3');
            $table->string('breed_pet', 250)->charset('utf8mb3');
            $table->integer('age_pet');

            // Campo imagen (como int, probablemente FK a tabla de imágenes)
            $table->integer('image_pet')->nullable();

            // Timestamp created_at personalizado
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();

            // Status con default 'A'
            $table->char('status_pet', 1)->default('A')->nullable();

            // Foreign Key hacia tabla de usuarios
            $table->integer('id_usu')->nullable();

            // Índices explícitos (como en tu script)
            $table->index('id_pet', 'tbl_pet_id_pet_index');
            $table->index('id_usu', 'tbl_pet_tbl_user_us_id_fk');

            // Foreign Key constraint
            $table->foreign('id_usu', 'tbl_pet_user_fk')
                ->references('us_id')
                ->on('tbl_user')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_pet');
    }
};
