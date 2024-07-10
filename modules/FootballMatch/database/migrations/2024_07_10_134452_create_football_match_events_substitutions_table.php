<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_match__events__substitutions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class, 'team_id')->index();
            $table->foreignIdFor(Player::class, 'player_in_id')->index();
            $table->foreignIdFor(Player::class, 'player_out_id')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_match__events__substitutions');
    }
};
