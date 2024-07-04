<?php

namespace Modules\FootballMatch\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\FootballMatch\database\factories\MatchEventFactory;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Enums\Match\MatchPhase;

class MatchEvent extends Model
{
    use HasUuids, HasFactory;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'match_id',
        'phase',
        'type',
        'timestamp'
    ];

    public $timestamps = false;

    protected $table = 'football_match__events';

    protected static function newFactory(): MatchEventFactory
    {
        return MatchEventFactory::new();
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(FootballMatch::class, 'match_id');
    }

    protected function casts(): array
    {
        return [
            'phase' => MatchPhase::class,
            'type' => MatchEventType::class,
            'timestamp' => 'datetime',
        ];
    }

    public function eventable(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, type: 'type', id: 'id');
    }
}
