<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentPopularityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment_popularity', function (Blueprint $table) {
            $table->integer('comment_id')->unsigned();
            $table->primary('comment_id');
            $table->integer('like_count')->unsigned()->default(0);
            $table->integer('comment_count')->unsigned()->default(0);
            $table->integer('favorite_count')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comment_popularity');
    }
}
