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
        Schema::table('communications', function (Blueprint $table) {
            $table->foreignIdFor(Sector::class)->nullable()->after('market_id')->constrained();
            $table->unsignedBigInteger('market_id')->nullable()->change();
            $table->unsignedBigInteger('auth_prod_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('communications', function (Blueprint $table) {
            $table->dropForeign(['sector_id']);
            $table->dropColumn('sector_id');
            $table->unsignedBigInteger('market_id')->change();
            $table->unsignedBigInteger('auth_prod_id')->change();
        });
    }
};
