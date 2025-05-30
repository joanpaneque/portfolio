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

            $table->json('title')->comment('Title for each language');
            $table->json('slug')->comment('Slug for each language');

            $table->json('content')->comment('Markdown content for each language');
            $table->string('image')->nullable()->comment('Image URL');
            // status enum
            $table->enum('status', ['draft', 'published', 'scheduled', 'archived'])->default('draft')->comment('draft: not published, published: published, scheduled: scheduled for publication, archived: archived');

            $table->json('tags')->nullable()->comment('Tags separated by comma for each language');

            $table->foreignId('category_id')->nullable()->constrained('categories')->comment('Category ID');

            // featured
            $table->boolean('featured')->default(false)->comment('Featured post');

            // published at
            $table->dateTime('published_at')->nullable()->comment('Published at');
            // scheduled at
            $table->dateTime('scheduled_at')->nullable()->comment('Scheduled at');
            // archived at
            $table->dateTime('archived_at')->nullable()->comment('Archived at');

            // updated by
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
