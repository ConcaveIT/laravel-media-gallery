<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcaveMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concave_media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',256);
            $table->string('alt_text',256)->nullable();
            $table->string('description',256)->nullable();
            $table->text('file_url')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->integer('uploaded_by')->nullable();
            $table->string('type',128)->nullable()->default('image');
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
        Schema::dropIfExists('concave_media');
    }
}
