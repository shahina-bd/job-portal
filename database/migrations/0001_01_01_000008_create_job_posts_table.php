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
        Schema::create('job_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('category_id');
            $table->string('title');
            $table->text('job_description');
            $table->date('publish_date');
            $table->date('end_date');
            $table->text('requirements');
            $table->decimal('salary', 15, 2)->nullable();
            $table->string('currency')->nullable();
            $table->enum('job_type', ['full-time', 'part-time', 'remote']);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_posts');
    }
};
