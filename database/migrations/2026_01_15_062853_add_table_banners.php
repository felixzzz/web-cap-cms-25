<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Table: banner_groups
        Schema::create('banner_groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('banners')->nullable();
            $table->string('bulk_position')->nullable();
            $table->timestamps();
        });

        // Table: banners
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_group_id')->constrained('banner_groups')->onDelete('cascade');
            $table->integer('order')->default(0);
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->text('html')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_gtm')->nullable();
            $table->timestamps();
        });

        // Table: banner_active
        Schema::create('banner_active', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_group_id')->constrained('banner_groups')->onDelete('cascade');
            $table->foreignId('post_id')->nullable()->constrained('posts')->onDelete('cascade');
            $table->string('location')->nullable();
            $table->dateTime('end_date')->nullable();
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
        //
        Schema::dropIfExists('banner_active');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('banner_groups');
    }
};
