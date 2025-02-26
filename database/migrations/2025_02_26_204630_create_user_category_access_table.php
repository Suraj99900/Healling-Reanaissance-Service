<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_category_access', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
            $table->timestamp('access_time')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('expiration_time');
            $table->timestamp('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('status')->index()->default(1)->comment('1 = Active, 0 = Inactive');
            $table->tinyInteger('deleted')->index()->default(0)->comment('1 = Deleted, 0 = Not Deleted');
            $table->timestamps(); // Laravel's created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_category_access');
    }
};


