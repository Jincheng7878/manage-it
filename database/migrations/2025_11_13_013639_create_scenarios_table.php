<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scenarios', function (Blueprint $table) {
            $table->id(); // BIGINT UNSIGNED
            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedInteger('budget')->default(10000);
            $table->unsignedInteger('duration')->default(30);
            $table->enum('difficulty', ['easy','medium','hard'])->default('medium');
            $table->json('initial_metrics')->nullable();

            $table->foreignId('created_by')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scenarios');
    }
};
