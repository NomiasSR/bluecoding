<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlShortenersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('url_shorteners', function (Blueprint $table) {
        $table->engine = "InnoDB";
        $table->bigIncrements('id');
        $table->string('short_code')->index();
        $table->longText('long_url');
        $table->unsignedTinyInteger('hits');
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
      Schema::dropIfExists('url_shorteners');
    }
}
