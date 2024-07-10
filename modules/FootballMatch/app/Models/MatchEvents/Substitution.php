<?php

namespace Modules\FootballMatch\Models\MatchEvents;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\FootballMatch\database\factories\MatchEvents\SubstitutionFactory;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

class Substitution extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'team_id',
        'player_in_id',
        'player_out_id',
    ];

    public $timestamps = false;

    protected $table = 'football_match__events__substitutions';

    protected static function newFactory(): SubstitutionFactory
    {
        return SubstitutionFactory::new();
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function playerIn(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_in_id');
    }

    public function playerOut(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'player_out_id');
    }

    public function event(): MorphOne
    {
        return $this->morphOne(MatchEvent::class, 'eventable', 'type', 'id');
    }
}
