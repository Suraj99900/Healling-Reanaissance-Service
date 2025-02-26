<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_category_access', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('category_id');
            $table->timestamp('access_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('expiration_time')->nullable(); // ✅ Allow NULL values
            $table->timestamp('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');
            $table->tinyInteger('deleted')->default(0)->comment('1 = Deleted, 0 = Not Deleted');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_category_access');
    }
};


