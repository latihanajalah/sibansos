<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survei_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survei_id')->constrained('survei')->onDelete('cascade');
            $table->string('kategori');
            $table->string('file');
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survei_foto');
    }
};
