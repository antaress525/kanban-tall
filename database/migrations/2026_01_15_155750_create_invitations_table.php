<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invited_by');
            $table->string('email');
            $table->timestamp('accepted_at')->nullable();
            $table->uuid('token')->unique();
            $table->string('status')->default('pending');
            $table->foreignUuid('board_id')->constrained('boards')->cascadeOnDelete();
            $table->timestamp('expires_at')->nullable()->index();
            $table->foreign('invited_by')->references('id')
                ->on('users')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
