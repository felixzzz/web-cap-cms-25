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
            if (!Schema::hasColumn('posts', 'caption_image')) {
                $table->text('caption_image')->nullable()->after('alt_image_en');
            }
            if (!Schema::hasColumn('posts', 'caption_image_en')) {
                $table->text('caption_image_en')->nullable()->after('caption_image');
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
            $table->dropColumn(['caption_image', 'caption_image_en']);
        });
    }
};
