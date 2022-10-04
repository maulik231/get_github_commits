<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommitDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commit_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('commit_id');
            $table->bigInteger('repo_id')->unsigned();
            $table->foreign('repo_id')->references('id')->on('repo_urls');
            $table->string('committer_name');
            $table->string('committer_email');
            $table->string('committer_date');
            $table->string('commit_message');
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
        Schema::dropIfExists('commit_data');
    }
}
