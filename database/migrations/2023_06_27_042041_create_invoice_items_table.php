<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('invoice_id');
            $table->integer('item_id');
            $table->integer('qty');
            $table->integer('amount');
            $table->timestamps();
            $table->foreign('category_id')
              ->references('id')->on('item_categories')->onDelete('cascade');
            $table->foreign('invoice_id')
              ->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('item_id')
              ->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
}
