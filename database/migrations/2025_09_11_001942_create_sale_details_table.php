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
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_categorie_id');
            $table->unsignedInteger('tip_afe_igv')->default(10);
            $table->double('per_icbper')->default(0);
            $table->double('icbper')->default(0);
            $table->double('percentage_isc')->unsigned()->default(0);
            $table->double('isc')->unsigned()->default(0);
            $table->string('unidad_medida', 25);
            $table->double('quantity')->default(1);
            $table->double('price_base')->default(0);
            $table->double('price_final')->default(0);
            $table->double('discount')->nullable()->default(0);
            $table->double('subtotal')->default(0)->comment('Es el precio unitario - descuento');
            $table->double('igv')->nullable()->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign keys
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_categorie_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
