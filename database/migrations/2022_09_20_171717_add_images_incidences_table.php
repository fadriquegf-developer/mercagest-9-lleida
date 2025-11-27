<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sector;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('incidences', function (Blueprint $table) {
            $table->text('image')->nullable()->change();
            $table->renameColumn('image', 'images');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('incidences', function (Blueprint $table) {
            $table->string('images')->nullable()->change();
            $table->renameColumn('images', 'image');
        });
    }
};
