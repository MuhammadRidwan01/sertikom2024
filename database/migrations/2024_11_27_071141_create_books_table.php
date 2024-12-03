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
    Schema::create('books', function (Blueprint $table) {
        $table->id();
        $table->string('judul_buku');
        $table->string('penulis');
        $table->year('tahun_terbit');
        $table->unsignedBigInteger('kategori')->nullable();
        $table->foreign('kategori')->references('id')->on('categories')->onDelete('set null');
        $table->enum('status', ['tersedia', 'dipinjam', 'pending'])->default('tersedia');
        $table->text('deskripsi')->nullable();
        $table->decimal('harga', 15, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
