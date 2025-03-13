<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('tbl_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->nullable();
            $table->string('file')->nullable();
            $table->integer('user_id');
            $table->integer('company_id');
            $table->string('del_status', 10)->default('Live');
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_attachments');
    }
};
