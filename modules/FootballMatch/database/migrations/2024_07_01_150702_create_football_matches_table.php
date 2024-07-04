<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_matches', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('type');
            $table->string('phase');
            $table->string('status');

            $table->foreignIdFor(Team::class, 'home_team_id')
                ->index();
            $table->foreignIdFor(Team::class, 'away_team_id')
                ->index();

            $table->index(['home_team_id', 'away_team_id'], 'home_vs_away_id_index');
            $table->index(['away_team_id', 'home_team_id'], 'away_vs_team_id_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_matches');
    }
};
