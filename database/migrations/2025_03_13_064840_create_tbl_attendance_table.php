<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create('tbl_attendance', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 50)->nullable();
            $table->integer('employee_id')->nullable();
            $table->date('date')->nullable();
            $table->time('in_time')->nullable();
            $table->time('out_time')->nullable();
            $table->string('note', 200)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('del_status', 50)->default('Live');
            $table->integer('is_closed')->default(2);
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_attendance');
    }
};
