<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHighlightTagsTable extends Migration
{
    public function up()
    {
        Schema::create('highlight_tags', function (Blueprint $table) {
            $table->foreignId('highlight_id')->constrained('highlights')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tags')->onDelete('cascade');
            $table->primary(['highlight_id', 'tag_id']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('highlight_tags');
    }
}
