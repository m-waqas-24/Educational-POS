<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('is_distributed')->default(0)->nullable();  
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
        Schema::dropIfExists('import_students');
    }
}
