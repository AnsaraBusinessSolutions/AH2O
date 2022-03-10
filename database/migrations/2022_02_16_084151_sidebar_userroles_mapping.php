<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SidebarUserrolesMapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	Schema::create("sidebar_userroles_mapping",function(BluePrint $table){
		$table->increments("id");
		$table->integer("menu_id");
		$table->integer("role");	
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
    		Schema::dropIfExists("sidebar_userroles_mapping");
    }
}
