<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\ChecklistType;
use App\Models\ChecklistGroup;
use App\Models\ChecklistAnswer;
use App\Models\Checklist;
use App\Models\ChecklistQuestion;
use App\Models\Market;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklist_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('allowed_sector')->nullable();
			$table->string('forbidden_sector')->nullable();
            $table->timestamps();
        });

        Schema::create('checklist_allowed_market', function (Blueprint $table) {
            $table->foreignIdFor(ChecklistType::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Market::class)->constrained()->cascadeOnDelete();
        });

        Schema::create('checklist_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(ChecklistType::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('checklist_questions', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('regulation')->nullable();
            $table->string('severity')->nullable();
            $table->foreignIdFor(ChecklistGroup::class)->constrained()->cascadeOnDelete();
            $table->boolean('visible')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ChecklistType::class)->constrained()->cascadeOnDelete();
            $table->string('origin_type');
            $table->string('origin_id');
            $table->timestamps();
        });

        Schema::create('checklist_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ChecklistQuestion::class)->constrained()->cascadeOnDelete();
            $table->boolean('is_check')->default(false);
            $table->text('comment')->nullable();
            $table->string('img')->nullable();
            $table->foreignIdFor(Checklist::class)->constrained()->cascadeOnDelete();
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
        Schema::dropIfExists('checklist_answers');
        Schema::dropIfExists('checklists');
        Schema::dropIfExists('checklist_questions');
        Schema::dropIfExists('checklist_groups');
        Schema::dropIfExists('checklist_allowed_market');
        Schema::dropIfExists('checklist_types');
    }
};
