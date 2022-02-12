<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VariableForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subcourses', function(Blueprint $table){
            $table->biginteger('courses_id')->unsigned()->after('id');
            $table->foreign('courses_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('subcourses', function(Blueprint $table){
            $table->dropForeign('subcourses_courses_id_foreign');
            $table->dropColumn('courses_id');
        });
    }
}
