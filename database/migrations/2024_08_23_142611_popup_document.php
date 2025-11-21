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
        Schema::table('documents', function (Blueprint $table) {
            $table->text('description_id')->nullable();
            $table->text('description_en')->nullable();
            $table->string('language')->nullable();
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->integer('release_year')->nullable();
            $table->integer('pages')->nullable();
            $table->string('format')->nullable();
        });
    }

    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'description_id',
                'description_en',
                'language',
                'author',
                'publisher',
                'release_year',
                'pages',
                'format',
            ]);
        });
    }
};
