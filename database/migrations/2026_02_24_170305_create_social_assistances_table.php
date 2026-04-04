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
        Schema::create('social_assistances', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('thumbnail');
            $table->string('name');
            $table->enum('category', ['staple', 'cash', 'subsidied fuel', 'health']);
            $table->decimal('amount', 12, 2);
            $table->string('provider');
            $table->longText('description');
            $table->boolean('is_available')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_assistances');
    }
};
