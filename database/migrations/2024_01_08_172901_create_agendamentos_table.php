<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("owner_id")->nullable();
            $table->unsignedBigInteger('barbearia_user_id'); 
            $table->unsignedBigInteger("cliente_id")->nullable();
            $table->unsignedBigInteger("maquininha_id")->nullable();
            $table->float("total_price");
            $table->foreign('barbearia_user_id')->references('id')->on('barbearia_users')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('maquininha_id')->references('id')->on('maquininhas')->onDelete('cascade');
            $table->string("payment_method")->nullable();
            $table->boolean("pago")->default(0);
            $table->text("id_pix")->nullable();
            $table->text("qrcode")->nullable();
            $table->text("payload")->nullable();
            $table->dateTime("start_date");
            $table->dateTime("end_date");
            $table->softDeletes();
     
            $table->boolean("read")->default(0);
            $table->timestamps();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete("cascade");
       
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
