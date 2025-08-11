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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku')->unique();
            $table->string('imagen')->nullable()->comment('Product image');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->decimal('price_general', 10, 2)->default(0.00);
            $table->decimal('price_company', 10, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->bigInteger('is_discount')->default(0)->comment('0 = No discount, 1 = Discount');
            $table->decimal('max_discount', 10, 2)->default(0.00)->comment('Maximum discount amount');
            $table->tinyInteger('disponibilidad')->default(1)->comment('1 = Available, 0 = Not available');
            $table->tinyInteger('state')->default(1)->comment('1 = Active, 0 = Inactive');
            $table->string('unidad_medida')->comment('Default unit of measure');
            $table->double('stock')->default(0)->comment('Stock quantity');
            $table->double('stock_minimo')->default(0)->comment('Minimum stock quantity');
            $table->tinyInteger('include_igv')->default(0)->comment('1 = Include I.V.A, 0 = Exclude I.V.A');
            $table->tinyInteger('is_icbper')->default(0)->comment('1 = ICBPER included, 0 = ICBPER not included');
            $table->tinyInteger('is_ivap')->default(0)->comment('1 = IVAP included, 0 = IVAP not included');
            $table->tinyInteger('is_isc')->default(0)->comment('1 = ISC included, 0 = ISC not included');
            $table->double('percentage_isc')->default(0)->comment('Percentage of ISC (Impuesto Selectivo al Consumo)');
            $table->tinyInteger('is_especial_nota')->default(0)->comment('1 = Special note included, 0 = Special note not included');
            $table->date('fecha_vencimiento')->nullable()->comment('Expiration date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }



};
