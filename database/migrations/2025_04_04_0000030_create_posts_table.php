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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Blogger's user id
            $table->enum('type', ['news', 'book', 'cours']);
            $table->string('caption');
            $table->string('image')->nullable(); // Single image for books and courses
            $table->json('images')->nullable(); // JSON for multiple images (for news)
            $table->string('pdf')->nullable(); // PDF path (for book posts)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};