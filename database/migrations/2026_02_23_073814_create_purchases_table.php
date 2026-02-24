<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('boost_id'); // como tus boosts son mock, no hay tabla boosts
            $table->timestamps();

            $table->unique(['user_id', 'boost_id']); // evita comprar lo mismo 2 veces
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};