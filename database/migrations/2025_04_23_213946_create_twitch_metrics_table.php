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
        Schema::create('twitch_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_profile_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('followers')->nullable();
            $table->integer('subscribers')->nullable();
            $table->float('average_viewers')->nullable();
            $table->integer('peak_viewers')->nullable();
            $table->float('hours_streamed')->nullable();
            $table->integer('stream_count')->nullable();
            $table->integer('chat_messages')->nullable();
            $table->timestamps();
            
            // Índice compuesto para evitar duplicados
            $table->unique(['social_profile_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('twitch_metrics');
    }
};