<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('playerwhite');
            $table->string('playerblack');
            $table->string('gamestatefen')->nullable();
            $table->mediumText('possiblemoves')->nullable();
            $table->string('secretwhite')->nullable();
            $table->string('secretblack')->nullable();
            $table->boolean('isactive')->default(false);
            $table->string('turn')->default('white');
            $table->boolean('whitestarted')->default(false);
            $table->boolean('blackstarted')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
