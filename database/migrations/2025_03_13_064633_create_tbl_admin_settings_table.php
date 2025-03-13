<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('tbl_admin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name_company_name');
            $table->string('contact_person', 200)->nullable();
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->string('logo')->nullable();
            $table->string('base_color', 50)->nullable();
            $table->string('favicon')->nullable();
            $table->string('first_section_image')->nullable();
            $table->text('footer')->nullable();
            $table->string('date_format');
            $table->string('currency', 3);
            $table->string('currency_position', 10)->default('Before');
            $table->string('precision', 10)->nullable();
            $table->string('decimals_separator', 20)->nullable();
            $table->string('thousands_separator', 20)->nullable();
            $table->string('time_zone');
            $table->string('web_site')->nullable();
            $table->timestamps();
            $table->string('del_status', 10)->default('Live');
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_admin_settings');
    }
};
