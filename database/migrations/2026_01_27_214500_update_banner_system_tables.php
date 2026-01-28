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
        Schema::table('banner_groups', function (Blueprint $table) {
            $table->string('position')->default('article')->after('title');
            $table->dropColumn(['slug', 'banners', 'bulk_position']);
        });

        Schema::table('banner_active', function (Blueprint $table) {
            $table->boolean('is_hide_in_mobile')->default(false)->after('language');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('use_html')->default(false)->after('html');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banner_groups', function (Blueprint $table) {
            $table->dropColumn('position');
            $table->string('slug')->nullable();
            $table->json('banners')->nullable();
            $table->string('bulk_position')->nullable();
        });

        Schema::table('banner_active', function (Blueprint $table) {
            $table->dropColumn('is_hide_in_mobile');
        });

        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn('use_html');
        });
    }
};
