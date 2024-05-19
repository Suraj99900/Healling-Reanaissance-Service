<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wellness_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_name')->index();
            $table->string('email')->index();
            $table->string('password')->index();
            $table->integer('user_type')->nullable()->index();
            $table->string('type')->index();
            $table->timestamp('added_on');
            $table->integer('status')->index()->default(1);
            $table->integer('deleted')->index()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wellness_user');
    }
};
