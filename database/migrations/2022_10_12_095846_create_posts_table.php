<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('post_name');
            $table->string('post_slug');
            $table->longText('post_content');
            $table->longText('post_excerpt')->nullable();
            $table->enum('post_type', ['post', 'page', 'attachment'])->default('post');
            $table->enum('post_status', ['publish', 'draft', 'review'])->default('draft');
            $table->boolean('post_comment')->default(true);
            $table->string('post_password')->nullable();
            $table->string('post_type_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('post_type_id')->references('id')->on('post_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('posts');
    }
};
