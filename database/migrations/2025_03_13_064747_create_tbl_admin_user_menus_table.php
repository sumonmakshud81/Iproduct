<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('tbl_admin_user_menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_name', 50)->nullable();
            $table->string('controller_name', 50)->nullable();
            $table->string('del_status', 50)->default('Live');
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_admin_user_menus');
    }
};
