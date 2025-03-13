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
        Schema::create('tbl_customer_orders', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('reference_no', 50)->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('order_type', 50)->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('delivery_address', 255)->nullable();
            $table->tinyInteger('order_status')->default(0);
            $table->integer('total_product')->nullable();
            $table->float('total_amount', 10, 2)->nullable();
            $table->float('total_cost', 10, 2)->nullable();
            $table->float('total_profit', 10, 2)->nullable();
            $table->text('quotation_note')->nullable();
            $table->text('internal_note')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->dateTime('updated_at')->nullable();
            $table->string('del_status', 10)->default('Live');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_customer_orders');
    }
};
