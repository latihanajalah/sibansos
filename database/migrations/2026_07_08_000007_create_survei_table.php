<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survei', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan')->onDelete('cascade');
            $table->string('status_rumah');
            $table->string('kepemilikan_rumah');
            $table->string('jenis_lantai');
            $table->string('jenis_dinding');
            $table->string('jenis_atap');
            $table->integer('jumlah_kamar');
            $table->integer('jumlah_penghuni');
            $table->string('pekerjaan');
            $table->decimal('penghasilan', 15, 2);
            $table->integer('jumlah_tanggungan');
            $table->boolean('punya_motor')->default(false);
            $table->boolean('punya_mobil')->default(false);
            $table->boolean('punya_sawah')->default(false);
            $table->boolean('punya_ternak')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei');
    }
};
