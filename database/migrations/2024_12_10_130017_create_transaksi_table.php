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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->uuid('order_id');
            $table->string('name');
            $table->uuid('id_tipe_transaksi');
            $table->unsignedBigInteger('amount');
            $table->double('va_number');
            $table->string('status');
            $table->timestamps();

            $table->foreign('id_tipe_transaksi')->references('id')->on('tipe_transaksi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
