<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToStudentCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->unsignedBigInteger('switch_from')->nullable()->after('is_continued');
            $table->unsignedBigInteger('balance')->default(0)->nullable()->after('is_continued');
            $table->unsignedBigInteger('refund')->default(0)->nullable()->after('is_continued');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_courses', function (Blueprint $table) {
            $table->dropColumn('switch_from');
            $table->dropColumn('refund');
        });
    }
}
