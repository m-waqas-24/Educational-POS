<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCourseCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_course_id');
            $table->unsignedBigInteger('user_id');
            $table->longText('comments');
            $table->unsignedBigInteger('is_read')->default(0)->nullable();
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
        Schema::dropIfExists('student_course_comments');
    }
}
