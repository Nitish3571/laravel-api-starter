<?php

use App\Models\LoginHistory;
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
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId(LoginHistory::USER_ID)->constrained()->onDelete('cascade');
            $table->string(LoginHistory::IP_ADDRESS)->nullable();
            $table->text(LoginHistory::USER_AGENT)->nullable();
            $table->timestamp(LoginHistory::LOGIN_AT);
            $table->timestamp(LoginHistory::LOGOUT_AT)->nullable();
            $table->string(LoginHistory::DEVICE_TYPE)->nullable();
            $table->string(LoginHistory::LOCATION)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_histories');
    }
};
