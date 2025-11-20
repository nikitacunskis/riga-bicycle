<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            $table->enum('type', ['personal', 'organisation'])->nullable()->change();
            $table->string('key')->nullable()->change();
            $table->string('owner')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('purpose_of_use')->nullable()->change();
            $table->string('reg_number')->nullable()->change();
            $table->string('org_contact')->nullable()->change();
            $table->string('tos_accepted')->nullable()->change();
            $table->string('privacy_accepted')->nullable()->change();
            $table->dateTime('valid_until')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('apis', function (Blueprint $table) {
            $table->enum('type', ['personal', 'organisation'])->nullable(false)->change();
            $table->string('key')->nullable(false)->change();
            $table->string('owner')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('purpose_of_use')->nullable(false)->change();
            $table->string('reg_number')->nullable(false)->change();
            $table->string('org_contact')->nullable(false)->change();
            $table->string('tos_accepted')->nullable(false)->change();
            $table->string('privacy_accepted')->nullable(false)->change();
            $table->dateTime('valid_until')->nullable(false)->change();
        });
    }
};
