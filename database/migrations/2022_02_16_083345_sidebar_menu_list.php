<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SidebarMenuList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
	Schema::create("sidebar_menu_list",function(BluePrint $table){
		$table->increments("id");
		$table->string("menu");
		$table->tinyInteger("is_this_submenu");
		$table->integer("position")    ;
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
    	Schema::dropIfExists("sidebar_menu_list");
    }
}
