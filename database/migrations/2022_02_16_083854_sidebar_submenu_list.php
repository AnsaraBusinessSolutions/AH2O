<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SidebarSubmenuList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	Schema::create("sidebar_submenu_list",function(BluePrint $table){
		$table->increments("id");
		$table->integer("menu_id");
		$table->string("submenu");
		$table->integer("position");
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
        //
    	Schema::dropIfExists("sidebar_submenu_list");
    }
}
