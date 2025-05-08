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
        Schema::create('reddit_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reddit_profile_id')->constrained()->onDelete('cascade');
            $table->integer('karma')->nullable();
            $table->float('engagement_rate')->nullable();
            $table->float('growth_rate')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reddit_metrics');
    }
};
