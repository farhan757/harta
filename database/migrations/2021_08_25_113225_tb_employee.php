<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TbEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tb_employee', function(Blueprint $table){
            $table->string('nik')->unique();
            $table->string('emplyoyee_name',50);
            $table->date('birth_date');
            $table->string('email')->unique();
            $table->string('phone');            
            $table->string('photo');            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
