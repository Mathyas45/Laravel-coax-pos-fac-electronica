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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('batch_code')->unique(); // Código del lote (ej: LOT001, A001, etc.)
            $table->integer('initial_stock')->default(0); // Stock inicial del lote
            $table->integer('current_stock')->default(0); // Stock actual del lote
            $table->date('expiration_date')->nullable(); // Fecha de vencimiento
            $table->date('manufacture_date')->nullable(); // Fecha de fabricación
            $table->decimal('cost_price', 10, 2); // Costo/precio de compra de este lote
            $table->boolean('is_active')->default(true); // Si el lote está activo
            $table->text('notes')->nullable(); // Notas adicionales del lote
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['product_id', 'expiration_date']);
            $table->index(['expiration_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
