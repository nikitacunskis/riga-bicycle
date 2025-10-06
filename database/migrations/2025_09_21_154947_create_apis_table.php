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
        Schema::create('apis', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['personal', 'organisation']);
            $table->string('key');
            $table->string('owner');
            $table->string('email');
            $table->string('phone');
            $table->string('purpose_of_use');
            $table->string('reg_number');
            $table->string('org_contact');
            $table->string('tos_accepted');
            $table->string('privacy_accepted');
            $table->dateTime('valid_until');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis');
    }
};
