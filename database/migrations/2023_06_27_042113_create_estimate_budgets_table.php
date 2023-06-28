<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateBudgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_budgets', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->string('name', 200);
            $table->string('total_amount', 20);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->foreign('branch_id')
              ->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('project_id')
              ->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_budgets');
    }
}
