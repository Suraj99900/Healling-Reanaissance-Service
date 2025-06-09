<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::create('user_enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('full_name');
            $table->string('phone');
            $table->string('email');
            $table->string('address', 500);
            $table->text('additional_info')->nullable();
             $table->timestamp('added_on')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');
            $table->tinyInteger('deleted')->default(0)->comment('1 = Deleted, 0 = Not Deleted');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_enrollments');
    }
}