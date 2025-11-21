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
        Schema::create('download_documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_id');
            $table->string('lang');
            $table->string('path');
            $table->string('session_id');
            $table->enum('status',['pending','success']);
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
        Schema::dropIfExists('download_documents');
    }
};
