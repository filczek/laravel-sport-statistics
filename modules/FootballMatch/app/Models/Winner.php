<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\FootballMatch\database\factories\MatchWinnerFactory;
use Modules\FootballMatch\Enums\Winner\WinType;

class Winner extends Model
{
    use HasUuids, HasFactory;

    protected $fillable = [
        'match_id',
        'type',
        'team_id',
    ];

    public $timestamps = false;

    protected $table = 'football_match__winners';

    protected static function newFactory(): MatchWinnerFactory
    {
        return MatchWinnerFactory::new();
    }

    protected function casts(): array
    {
        return [
            'type' => WinType::class,
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
