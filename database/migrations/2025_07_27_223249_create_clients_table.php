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
            $table->string('name', 250)->nullable();
            $table->string('surname', 250)->nullable();
            $table->string('full_name', 250)->nullable();
            $table->string('phone', 25)->nullable();
            $table->string('email', 250)->nullable();
            $table->tinyInteger('type_client')->unsigned()->default(1)->comment('1 es cliente normal y 2 es empresa');
            $table->string('type_document', 150)->nullable();
            $table->string('n_document', 50);
            $table->char('gender', 1)->nullable()->comment('M es masculino y F femenino');
            $table->timestamp('birth_date')->nullable()->comment('fecha de cumple');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('address', 250)->nullable();
            $table->string('ubigeo_distrito', 25)->nullable();
            $table->string('ubigeo_provincia', 25)->nullable();
            $table->string('ubigeo_region', 25)->nullable();
            $table->string('distrito', 80)->nullable();
            $table->string('provincia', 80)->nullable();
            $table->string('region', 80)->nullable();
            $table->tinyInteger('state')->unsigned()->default(1);
            $table->timestamps();
            $table->softDeletes();

            // Índices
            
            $table->index(['type_client']);
            $table->index(['n_document']);
            $table->index(['state']);
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
