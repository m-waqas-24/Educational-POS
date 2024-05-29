<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentCoursePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_course_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('student_course_id')->nullable();
            $table->unsignedBigInteger('mode_first')->nullable();
            $table->unsignedBigInteger('payment_first')->nullable();
            $table->longText('payment_first_receipt')->nullable();
            $table->date('payment_date_first')->nullable();
            $table->unsignedBigInteger('mode_second')->nullable();
            $table->unsignedBigInteger('payment_second')->nullable();
            $table->longText('payment_second_receipt')->nullable();
            $table->unsignedBigInteger('is_confirmed_first')->nullable();
            $table->unsignedBigInteger('is_confirmed_second')->nullable();
            $table->unsignedBigInteger('is_edit_first')->default(0)->nullable();
            $table->unsignedBigInteger('is_edit_second')->default(0)->nullable();
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
        Schema::dropIfExists('student_course_payments');
    }
}
