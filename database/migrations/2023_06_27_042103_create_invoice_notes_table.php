<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_notes', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_id');
            $table->text('description')->nullable();
            $table->string('status', 20);
            $table->timestamps();
            $table->foreign('invoice_id')
              ->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_notes');
    }
}
