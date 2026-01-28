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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 191);
            $table->string('email', 191)->nullable();
            $table->string('image', 191)->nullable();
            $table->string('phone', 191);
            $table->string('password', 191);
            $table->string('password', 191);
            // $table->string('city', 191);
            $table->text('token')->nullable();

            $table->timestamps();
        });
    }
// bZbCwLucIF8o9vyms5JSvtMbRCMR9S6zAnlv8nabITrcKTxbck0kwYCUTnlGlZfT
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
