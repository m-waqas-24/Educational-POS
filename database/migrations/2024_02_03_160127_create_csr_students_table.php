<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsrStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csr_students', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('csr_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('enroll_student_id')->nullable();
            $table->dateTime('called_at')->nullable();
            $table->unsignedBigInteger('action_status_id')->default(0);
            $table->string('comments')->nullable();
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
        Schema::dropIfExists('csr_students');
    }
}
