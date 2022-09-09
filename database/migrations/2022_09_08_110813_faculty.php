<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Faculty extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculties', function(Blueprint $table){
            $table->id();
            $table->string('faculty_name')->nullable();
            $table->string('faculty_sname')->nullable();
            $table->string('faculty_programme')->nullable();
            $table->string('faculty_barcode')->nullable();
            $table->string('faculty_dean')->nullable();
            $table->string('faculty_registrar')->nullable();
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
        Schema::dropIfExists('faculties');
    }
}
