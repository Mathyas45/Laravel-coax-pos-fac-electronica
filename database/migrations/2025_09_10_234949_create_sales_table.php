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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('serie', 50)->nullable();
            $table->unsignedBigInteger('correlativo')->nullable();
            $table->string('n_operacion', 125)->unique()->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('client_id');
            $table->smallInteger('type_client')->default(1)->comment('1 es cliente final, 2 es cliente empresa');
            $table->string('currency', 15)->default('S/.');
            $table->double('subtotal')->default(0);
            $table->double('total')->default(0);
            $table->boolean('is_exportacion')->unsigned()->default(0);
            $table->double('discount')->nullable()->default(0);
            $table->double('discount_global')->default(0);
            $table->string('n_comprobante_anticipo', 150)->nullable();
            $table->double('amount_anticipo')->nullable();
            $table->double('igv');
            $table->double('igv_discount_general')->unsigned()->default(0);
            $table->tinyInteger('type_payment')->unsigned()->default(1)->comment('1 es al contado, 2 crédito');
            $table->smallInteger('state_sale')->default(1)->comment('1 es venta, 2 es cotización');
            $table->smallInteger('state_payment')->default(1)->comment('1 es pendiente, 2 es parcial, 3 es completo');
            $table->double('debt')->default(0)->comment('deuda');
            $table->double('paid_out')->default(0)->comment('pagado o cancelado');
            $table->tinyInteger('retencion_igv')->unsigned()->default(0)->comment('1 Retención, 2 Detracción, 3 Percepción');
            $table->text('description')->nullable();
            $table->string('cdr', 250)->nullable();
            $table->string('xml', 250)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->unique(['serie', 'correlativo'], 'sale_serie_correlativo');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
