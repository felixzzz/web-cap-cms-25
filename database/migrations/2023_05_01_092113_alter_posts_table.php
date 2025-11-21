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
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('post_name');
            $table->dropColumn('post_slug');
            $table->dropColumn('post_content');
            $table->dropColumn('post_excerpt');
            $table->dropColumn('post_type');
            $table->dropColumn('post_status');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->enum('type', ['page', 'promo', 'article'])->default('article')->after('id');
            $table->string('title')->after('type');
            $table->string('slug')->after('title');
            $table->string('excerpt')->after('slug')->nullable();
            $table->text('content')->after('excerpt')->nullable();
            $table->enum('status', ['publish', 'draft', 'review','schedule'])->default('draft')->after('content');
            $table->timestamp('published_at')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // $table->dropColumn('username');
        });
    }
};
