<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduleStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_status', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->text('description');
            $table->double('weight');
            $table->text('comments')->nullable();
            $table->string('background_color')->default("#FFFFFF");
            $table->string('text_color')->default("#000000");
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedule_status');
    }
}
