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
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->unsignedBigInteger('inviter_id'); // Пользователь, который отправил приглашение
            $table->unsignedBigInteger('invitee_id'); // Пользователь, которому отправили приглашение
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending'); // pending, accepted, rejected
            $table->timestamps();
        
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('inviter_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invitee_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_invitations');
    }
};
