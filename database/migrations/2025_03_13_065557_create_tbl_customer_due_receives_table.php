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
        Schema::create('tbl_customer_due_receives', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('reference_no', 50)->nullable();
            $table->date('only_date')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('customer_id')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->string('note', 200)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->timestamp('due_date_time')->useCurrent();
            $table->string('del_status', 50)->default('Live');
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_customer_due_receives');
    }
};
