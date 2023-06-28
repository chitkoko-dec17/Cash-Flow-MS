<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_type_id');
            $table->string('invoice_no', 255);
            $table->date('invoice_date');
            $table->integer('total_amount');
            $table->text('description')->nullable();
            $table->integer('upload_user_id')->nullable();
            $table->integer('appoved_manager_id')->nullable();
            $table->string('manager_status', 20)->nullable();
            $table->integer('appoved_admin_id')->nullable();
            $table->string('admin_status', 20)->nullable();
            $table->timestamps();
            $table->foreign('invoice_type_id')
              ->references('id')->on('invoice_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
