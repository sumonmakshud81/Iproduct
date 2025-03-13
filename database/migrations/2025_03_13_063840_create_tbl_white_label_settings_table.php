<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tbl_white_label_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Add relevant fields here
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_white_label_settings');
    }
};
