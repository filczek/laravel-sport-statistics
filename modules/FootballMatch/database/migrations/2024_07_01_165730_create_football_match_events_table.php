<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\FootballMatch\Models\FootballMatch;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('football_match__events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(FootballMatch::class, 'match_id')
                ->index('match_id_index');

            $table->string('phase')->index();
            $table->string('type')->index();

            $table->dateTime('timestamp', 3)
                ->index();

            $table->index(['type', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('football_match__events');
    }
};
