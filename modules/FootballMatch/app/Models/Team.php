<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\FootballMatch\database\factories\TeamFactory;

class Team extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    protected $table = 'football_match__teams';

    protected static function newFactory(): TeamFactory
    {
        return TeamFactory::new();
    }

    public function players(): HasMany
    {
        return $this->hasMany(Player::class, 'team_id');
    }
}
