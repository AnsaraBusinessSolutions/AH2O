<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Authenticationmethod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	Schema::create("authentication_method",function(BluePrint $table){
		$table->increments("id");
		 $table->string("method" ) ;
		$table->tinyinteger("active");
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
    	Schema::dropIfExists("authentication_method");
    }
}
