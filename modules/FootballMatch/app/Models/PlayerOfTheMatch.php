<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerOfTheMatch extends Model
{
    use HasUuids, HasUuids, HasUuids, HasFactory;

    protected $fillable = [
        'match_id',
        'player_id',
    ];

    public $timestamps = false;

    protected $table = 'football_match__player_of_the_match';

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
