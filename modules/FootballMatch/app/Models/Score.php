<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\FootballMatch\database\factories\ScoreFactory;
use Modules\FootballMatch\Enums\Match\MatchScoreType;

class Score extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'match_id',
        'type',
        'home',
        'away'
    ];

    public $timestamps = false;
    protected $table = 'football_match__scores';

    protected static function newFactory(): ScoreFactory
    {
        return ScoreFactory::new();
    }

    protected function casts(): array
    {
        return [
            'type' => MatchScoreType::class,
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }
}
