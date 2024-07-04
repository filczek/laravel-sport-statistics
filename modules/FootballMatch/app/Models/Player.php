<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\FootballMatch\database\factories\PlayerFactory;
use Modules\FootballMatch\Enums\Player\PlayerPosition;

class Player extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'team_id',
        'name',
        'position'
    ];

    public $timestamps = false;
    protected $table = 'football_match__players';

    protected static function newFactory(): PlayerFactory
    {
        return PlayerFactory::new();
    }

    protected function casts(): array
    {
        return [
            'position' => PlayerPosition::class
        ];
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function playerOfTheMatches(): HasMany
    {
        return $this->hasMany(PlayerOfTheMatch::class, 'player_id');
    }
}
