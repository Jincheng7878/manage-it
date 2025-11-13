<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('decisions', function (Blueprint $table) {
            $table->id();

            // 外键：引用 scenarios.id
            $table->foreignId('scenario_id')
                  ->constrained('scenarios')
                  ->onDelete('cascade');

            // 外键：引用 users.id
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');

            $table->string('strategy');
            $table->unsignedInteger('time_alloc')->default(0);
            $table->unsignedInteger('cost_alloc')->default(0);
            $table->enum('risk_level', ['low','medium','high'])->default('medium');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('decisions');
    }
};
