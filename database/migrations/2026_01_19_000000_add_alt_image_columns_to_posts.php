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
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'alt_image')) {
                $table->string('alt_image')->nullable()->after('content');
            }
            if (!Schema::hasColumn('posts', 'alt_image_en')) {
                $table->string('alt_image_en')->nullable()->after('alt_image');
            }
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
            $table->dropColumn(['alt_image', 'alt_image_en']);
        });
    }
};
