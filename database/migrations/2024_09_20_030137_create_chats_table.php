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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->uuid('sender_uuid');
            $table->uuid('receiver_uuid');
            $table->text('message')->nullable();
            $table->string('media_path')->nullable();
            $table->enum('type', ['text', 'image', 'audio']);
            $table->boolean('count_receiver')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
