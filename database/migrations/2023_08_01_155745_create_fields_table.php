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
        Schema::create('fields', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('sort');
            $table->string('label');
            $table->string('name');
            $table->string('class')->nullable();
            $table->enum('type', ['input', 'select', 'radio', 'checkbox', 'textarea', 'editor'])->default('input');
            $table->enum('input', ['text', 'number', 'email', 'phone', 'file', 'image'])->default('text')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('placeholder')->nullable();
            $table->json('options')->nullable();
            $table->foreignIdFor(\App\Models\Form\Form::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('fields');
    }
};
