<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_deposits', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('reference_no', 50)->nullable();
            $table->string('date', 30)->nullable();
            $table->string('type', 11)->nullable();
            $table->string('note', 255)->nullable();
            $table->float('amount')->nullable();
            $table->integer('account_id')->default(1);
            $table->integer('user_id')->nullable();
            $table->integer('company_id')->default(0);
            $table->string('del_status', 11)->default('Live');
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_deposits');
    }
};
