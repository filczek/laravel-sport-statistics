<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_match__events__goals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Team::class, 'team_id')
                ->index();
            $table->foreignIdFor(Player::class, 'player_id')
                ->index();

            $table->string('goal_type');
            $table->string('body_part');

            $table->index(['player_id', 'goal_type'], 'goal_types_by_player_index');
            $table->index(['player_id', 'body_part'], 'goal_body_types_by_player_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_match__events__goals');
    }
};
