<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Person;
use App\Models\Market;
use App\Models\Calendar;

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
            $table->foreignIdFor(Person::class)->nullable()->after('id')->constrained('persons');
            $table->foreignIdFor(Market::class)->nullable()->after('stall_id')->constrained();
            $table->boolean('can_mount_stall')->nullable()->after('date_solved');
            $table->unsignedBigInteger('stall_id')->nullable()->change();
            
            $table->dropForeign(['calendar_id']);
            $table->dropColumn('calendar_id');
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
            $table->foreignIdFor(Calendar::class)->nullable()->constrained('calendar');
            $table->dropForeign(['person_id']);
            $table->dropColumn('person_id');
            $table->dropForeign(['market_id']);
            $table->dropColumn('market_id');
            $table->dropColumn('can_mount_stall');
            $table->unsignedBigInteger('stall_id')->change();
        });
    }
};
