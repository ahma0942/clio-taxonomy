<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeesForeignKeyEmployeesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function(Blueprint $table) {
            $table->unsignedBigInteger('employees_data_id')->after("superior_id")->nullable();
            $table->foreign('employees_data_id')->references('id')->on('employees_data')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function(Blueprint $table) {
            $table->dropForeign('employees_employees_data_id_foreign');
            $table->dropColumn('employees_data_id');
        });
    }
}
