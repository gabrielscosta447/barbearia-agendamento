<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\PaymentMethods;
use App\Enums\PlanTypes;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barbearia_users', function (Blueprint $table) {
            $table->id();
            $paymentMethodValues = [];

            foreach (PaymentMethods::cases() as $case) {
                $paymentMethodValues[] = $case->value;
            }

            $planTypeValues = [];

            foreach (PlanTypes::cases() as $plan) {
                $planTypeValues[] = $plan->value;
            }
            $table->unsignedBigInteger("barbearia_id");
            $table->string("assinatura_id")->nullable();
            $table->string("payment_id")->nullable();
            $table->enum('payment_method', $paymentMethodValues)->nullable();
            $table->string("card_id")->nullable();
      
            $table->enum('price', $planTypeValues)->nullable(); // Usando array_values()
            $table->timestamp('plan_ends_at')->nullable();
            $table->time('interval')->default('01:00:00');
            $table->string('asaas_payment_url')->nullable();
            $table->string('asaas_customer_id')->nullable();
            $table->time('antecedence_time')->default('01:00:00');
            $table->datetime('maxDate')->nullable();
            $table->string('chave_pix')->nullable();
            $table->string('tipo_chave')->nullable();
            $table->unsignedBigInteger("user_id");
            $table->softDeletes();
            $table->foreign('barbearia_id')->references('id')->on('barbearias')->onDelete("cascade");
            $table->foreign('user_id')->references('id')->on('users')->onDelete("cascade");
         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barbearia_users');
    }
};
