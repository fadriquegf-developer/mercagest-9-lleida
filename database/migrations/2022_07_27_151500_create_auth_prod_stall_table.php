<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\AuthProd;
use App\Models\Stall;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_prod_stall', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(AuthProd::class)->constrained();
            $table->foreignIdFor(Stall::class)->constrained();
            $table->timestamps();
        });

        Schema::table('stalls', function (Blueprint $table) {
            $table->dropForeign(['auth_prod_id']);
            $table->dropColumn('auth_prod_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('auth_prod_stall');
        Schema::table('stalls', function (Blueprint $table) {
            $table->foreignIdFor(AuthProd::class)->constrained()->after('id')->nullable();
        });
    }
};
