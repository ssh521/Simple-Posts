<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create(config('simple-posts.table_name', 'simple_posts'), function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->date('date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('simple-posts.table_name', 'simple_posts'));
    }
};
