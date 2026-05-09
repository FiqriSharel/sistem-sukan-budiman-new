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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code')->unique();
            $table->string('name');
            $table->unsignedTinyInteger('age');
            $table->string('phone');
            $table->string('category');
            $table->foreignId('house_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('guardian_id')->nullable()->constrained()->nullOnDelete();
            $table->string('status')->default('Aktif');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['name', 'phone']);
            $table->index(['category', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
