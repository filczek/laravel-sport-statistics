<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\FootballMatch\database\factories\FootballMatchFactory;
use Modules\FootballMatch\Enums\Match\MatchPhase;
use Modules\FootballMatch\Enums\Match\MatchStatus;
use Modules\FootballMatch\Enums\Match\MatchType;

class FootballMatch extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'type',
        'phase',
        'status',

        'home_team_id',
        'away_team_id',
    ];

    public $timestamps = false;
    protected $table = 'football_matches';

    protected static function newFactory(): FootballMatchFactory
    {
        return FootballMatchFactory::new();
    }

    protected function casts(): array
    {
        return [
            'type' => MatchType::class,
            'phase' => MatchPhase::class,
            'status' => MatchStatus::class,
        ];
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class, 'match_id');
    }

    public function winners(): HasMany
    {
        return $this->hasMany(Winner::class, 'match_id');
    }

    public function playerOfTheMatch(): HasOne
    {
        return $this->hasOne(PlayerOfTheMatch::class, 'match_id');
    }

    public function matchEvents(): HasMany
    {
        return $this->hasMany(MatchEvent::class, 'match_id');
    }
}
