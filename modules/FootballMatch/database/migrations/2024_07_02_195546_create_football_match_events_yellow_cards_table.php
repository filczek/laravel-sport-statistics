<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\Player;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_match__events__yellow_cards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Player::class)->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_match__events__yellow_cards');
    }
};
