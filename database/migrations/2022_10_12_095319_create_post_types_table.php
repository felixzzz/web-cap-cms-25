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
        Schema::create('post_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('sort');
            $table->string('name');
            $table->string('slug');
            $table->enum('type', ['post', 'page'])->default('post');
            $table->boolean('is_public')->default(false);
            $table->boolean('show_in_menu')->default(false);
            $table->boolean('is_category')->default(false);
            $table->boolean('is_tags')->default(false);
            $table->boolean('is_content')->default(false);
            $table->boolean('featured_image')->default(false);
            $table->boolean('featured')->default(false);
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('post_types');
    }
};
