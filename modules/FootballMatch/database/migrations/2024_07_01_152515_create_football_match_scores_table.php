<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\FootballMatch;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_match__scores', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->foreignIdFor(FootballMatch::class, 'match_id')
                ->index();

            $table->string('type')->index();

            $table->unsignedTinyInteger('home')->index();
            $table->unsignedTinyInteger('away')->index();

            $table->unique(['match_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_match__scores');
    }
};
