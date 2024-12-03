<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('fines', function (Blueprint $table) {
        $table->id();
        $table->foreignId('loan_id')->constrained()->onDelete('cascade');
        $table->decimal('jumlah_denda', 10, 2);
        $table->enum('status', ['belum_dibayar', 'sudah_dibayar'])->default('belum_dibayar');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fines');
    }
};
