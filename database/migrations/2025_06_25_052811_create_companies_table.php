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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('razon_social', 250);
            $table->string('razon_social_comercial', 250);
            $table->string('phone', 25)->nullable();
            $table->string('email', 250)->nullable();
            $table->string('n_document', 50);
            $table->timestamp('birth_date')->nullable()->comment('fecha de cumple');
            $table->string('address', 250)->nullable();
            $table->string('urbanizacion', 250);
            $table->string('cod_local', 150);
            $table->string('ubigeo_distrito', 25)->nullable();
            $table->string('ubigeo_provincia', 25)->nullable();
            $table->string('ubigeo_region', 25)->nullable();
            $table->string('distrito', 80)->nullable();
            $table->string('provincia', 80)->nullable();
            $table->string('region', 80)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
