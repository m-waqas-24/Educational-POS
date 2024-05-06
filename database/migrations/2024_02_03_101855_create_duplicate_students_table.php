<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuplicateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('duplicate_students', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->longText('email');
            $table->longText('phone');    
            $table->longText('cnic');    
            $table->longText('city');    
            $table->longText('course');    
            $table->dateTime('datetime');    
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('duplicate_students');
    }
}
