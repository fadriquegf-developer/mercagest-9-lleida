<?php

use App\Models\AuthProd;
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
        Schema::table('sectors', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('auth_prods', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('markets', function (Blueprint $table) {
            $table->text('name')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sectors', function (Blueprint $table) {
            $table->json('name')->change();
        });

        Schema::table('auth_prods', function (Blueprint $table) {
            $table->json('name')->change();
        });

        Schema::table('markets', function (Blueprint $table) {
            $table->json('name')->change();
        });
    }
};
