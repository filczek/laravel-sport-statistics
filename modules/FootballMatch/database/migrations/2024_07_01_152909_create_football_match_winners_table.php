<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\Team;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_match__winners', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(FootballMatch::class, 'match_id')
                ->index();

            $table->string('type');
            $table->foreignIdFor(Team::class, 'team_id')
                ->index();

            $table->index(['team_id', 'type'], 'team_wins_by_type_index');

            $table->unique(['match_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_match__winners');
    }
};
