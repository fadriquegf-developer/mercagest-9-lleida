<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migració ULTRA-SEGURA amb comprovació d'índexs
 * Aquesta versió comprova cada índex abans de crear-lo
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // historicals
        $this->addIndexSafely('historicals', 'ends_at', 'idx_historicals_ends_at');
        $this->addIndexSafely('historicals', 'end_vigencia', 'idx_historicals_end_vigencia');
        $this->addIndexSafely('historicals', 'start_at', 'idx_historicals_start_at');
        $this->addIndexSafely('historicals', ['stall_id', 'ends_at'], 'idx_historicals_stall_ends');
        $this->addIndexSafely('historicals', ['person_id', 'ends_at'], 'idx_historicals_person_ends');
        $this->addIndexSafely('historicals', ['start_at', 'ends_at', 'end_vigencia'], 'idx_historicals_date_range');

        // invoices
        $this->addIndexSafely('invoices', 'status', 'idx_invoices_status');
        $this->addIndexSafely('invoices', 'type', 'idx_invoices_type');
        $this->addIndexSafely('invoices', 'years', 'idx_invoices_years');
        $this->addIndexSafely('invoices', 'month', 'idx_invoices_month');
        $this->addIndexSafely('invoices', 'trimestral', 'idx_invoices_trimestral');
        $this->addIndexSafely('invoices', ['person_id', 'market_group_id', 'years'], 'idx_invoices_person_market_year');
        $this->addIndexSafely('invoices', ['status', 'person_id'], 'idx_invoices_status_person');
        $this->addIndexSafely('invoices', ['type', 'month', 'years'], 'idx_invoices_type_month_year');
        $this->addIndexSafely('invoices', ['type', 'trimestral', 'years'], 'idx_invoices_type_trim_year');

        // bonuses
        $this->addIndexSafely('bonuses', 'type', 'idx_bonuses_type');
        $this->addIndexSafely('bonuses', 'start_at', 'idx_bonuses_start_at');
        $this->addIndexSafely('bonuses', 'ends_at', 'idx_bonuses_ends_at');
        $this->addIndexSafely('bonuses', ['stall_id', 'start_at', 'ends_at'], 'idx_bonuses_stall_dates');

        // calendar
        $this->addIndexSafely('calendar', 'date', 'idx_calendar_date');
        $this->addIndexSafely('calendar', ['market_id', 'date'], 'idx_calendar_market_date');

        // invoice_concepts
        $this->addIndexSafely('invoice_concepts', 'concept', 'idx_invoice_concepts_concept');
        $this->addIndexSafely('invoice_concepts', ['invoice_id', 'concept'], 'idx_invoice_concepts_invoice_concept');

        // stalls
        $this->addIndexSafely('stalls', 'active', 'idx_stalls_active');
        $this->addIndexSafely('stalls', 'num', 'idx_stalls_num');
        $this->addIndexSafely('stalls', ['market_id', 'active'], 'idx_stalls_market_active');

        // markets
        $this->addIndexSafely('markets', 'status', 'idx_markets_status');

        // persons
        $this->addIndexSafely('persons', 'name', 'idx_persons_name');
        $this->addIndexSafely('persons', 'email', 'idx_persons_email');

        // absences
        $this->addIndexSafely('absences', 'type', 'idx_absences_type');
        $this->addIndexSafely('absences', ['person_id', 'start', 'end'], 'idx_absences_person_dates');
        $this->addIndexSafely('absences', ['stall_id', 'start', 'end'], 'idx_absences_stall_dates');

        // market_groups
        $this->addIndexSafely('market_groups', 'status', 'idx_market_groups_status');
        $this->addIndexSafely('market_groups', 'type', 'idx_market_groups_type');
        $this->addIndexSafely('market_groups', 'payment', 'idx_market_groups_payment');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropIndexSafely('historicals', 'idx_historicals_ends_at');
        $this->dropIndexSafely('historicals', 'idx_historicals_end_vigencia');
        $this->dropIndexSafely('historicals', 'idx_historicals_start_at');
        $this->dropIndexSafely('historicals', 'idx_historicals_stall_ends');
        $this->dropIndexSafely('historicals', 'idx_historicals_person_ends');
        $this->dropIndexSafely('historicals', 'idx_historicals_date_range');

        $this->dropIndexSafely('invoices', 'idx_invoices_status');
        $this->dropIndexSafely('invoices', 'idx_invoices_type');
        $this->dropIndexSafely('invoices', 'idx_invoices_years');
        $this->dropIndexSafely('invoices', 'idx_invoices_month');
        $this->dropIndexSafely('invoices', 'idx_invoices_trimestral');
        $this->dropIndexSafely('invoices', 'idx_invoices_person_market_year');
        $this->dropIndexSafely('invoices', 'idx_invoices_status_person');
        $this->dropIndexSafely('invoices', 'idx_invoices_type_month_year');
        $this->dropIndexSafely('invoices', 'idx_invoices_type_trim_year');

        $this->dropIndexSafely('bonuses', 'idx_bonuses_type');
        $this->dropIndexSafely('bonuses', 'idx_bonuses_start_at');
        $this->dropIndexSafely('bonuses', 'idx_bonuses_ends_at');
        $this->dropIndexSafely('bonuses', 'idx_bonuses_stall_dates');

        $this->dropIndexSafely('calendar', 'idx_calendar_date');
        $this->dropIndexSafely('calendar', 'idx_calendar_market_date');

        $this->dropIndexSafely('invoice_concepts', 'idx_invoice_concepts_concept');
        $this->dropIndexSafely('invoice_concepts', 'idx_invoice_concepts_invoice_concept');

        $this->dropIndexSafely('stalls', 'idx_stalls_active');
        $this->dropIndexSafely('stalls', 'idx_stalls_num');
        $this->dropIndexSafely('stalls', 'idx_stalls_market_active');

        $this->dropIndexSafely('markets', 'idx_markets_status');

        $this->dropIndexSafely('persons', 'idx_persons_name');
        $this->dropIndexSafely('persons', 'idx_persons_email');

        $this->dropIndexSafely('absences', 'idx_absences_type');
        $this->dropIndexSafely('absences', 'idx_absences_person_dates');
        $this->dropIndexSafely('absences', 'idx_absences_stall_dates');

        $this->dropIndexSafely('market_groups', 'idx_market_groups_status');
        $this->dropIndexSafely('market_groups', 'idx_market_groups_type');
        $this->dropIndexSafely('market_groups', 'idx_market_groups_payment');
    }

    /**
     * Afegeix un índex de forma segura (comprova si existeix)
     */
    private function addIndexSafely($table, $columns, $indexName)
    {
        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($columns, $indexName) {
                $table->index($columns, $indexName);
            });
            echo "✅ Creat índex: {$indexName} a taula {$table}\n";
        } else {
            echo "⏭️  Saltat (ja existeix): {$indexName} a taula {$table}\n";
        }
    }

    /**
     * Elimina un índex de forma segura (comprova si existeix)
     */
    private function dropIndexSafely($table, $indexName)
    {
        if ($this->indexExists($table, $indexName)) {
            Schema::table($table, function (Blueprint $table) use ($indexName) {
                $table->dropIndex($indexName);
            });
            echo "✅ Eliminat índex: {$indexName} de taula {$table}\n";
        } else {
            echo "⏭️  Saltat (no existeix): {$indexName} de taula {$table}\n";
        }
    }

    /**
     * Comprova si un índex existeix
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }
};
