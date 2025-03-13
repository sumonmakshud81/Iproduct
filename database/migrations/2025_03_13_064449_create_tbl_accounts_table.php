<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('tbl_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->float('opening_balance', 10, 2)->nullable();
            $table->string('description', 250)->nullable();
            $table->integer('added_by')->nullable();
            $table->timestamps();
            $table->string('del_status', 10)->default('Live');
        });
    }

    public function down() {
        Schema::dropIfExists('tbl_accounts');
    }
};
