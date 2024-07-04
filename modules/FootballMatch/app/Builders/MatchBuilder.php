<?php

declare(strict_types=1);

namespace Modules\FootballMatch\Builders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\FootballMatch\database\factories\FootballMatchFactory;
use Modules\FootballMatch\Enums\Match\MatchEventType;
use Modules\FootballMatch\Enums\Match\MatchScoreType;
use Modules\FootballMatch\Enums\MatchEvents\Goal\BodyPartType;
use Modules\FootballMatch\Enums\MatchEvents\Goal\GoalType;
use Modules\FootballMatch\Enums\Winner\WinType;
use Modules\FootballMatch\Models\FootballMatch;
use Modules\FootballMatch\Models\MatchEvent;
use Modules\FootballMatch\Models\MatchEvents\GoalEvent;
use Modules\FootballMatch\Models\MatchEvents\RedCard;
use Modules\FootballMatch\Models\MatchEvents\YellowCard;
use Modules\FootballMatch\Models\Player;
use Modules\FootballMatch\Models\Team;

/**
 * NOTE: This class was purely written for sake of seeding and testing, code quality is pretty low.
 *
 * MatchBuilder class for constructing FootballMatch objects with associated events and statistics.
 *
 * This class provides a fluent interface for building complex FootballMatch objects,
 * including goals, yellow cards, red cards, scores, winners, and player of the match.
 *
 * Usage example:
 * $match = MatchBuilder::create()
 *     ->withHomeTeam($homeTeam)
 *     ->withAwayTeam($awayTeam)
 *     ->withGoalBy($player1)
 *     ->withGoalBy($player2)
 *     ->withYellowCardTo($player3)
 *     ->build();
 */
class MatchBuilder
{
    private FootballMatchFactory $match_factory;

    private array $goals = [];
    private ?int $home_desired_score = null;
    private ?int $away_desired_score = null;

    private array $yellow_cards = [];
    private ?int $home_desired_yellow_cards = null;
    private ?int $away_desired_yellow_cards = null;

    private array $red_cards = [];
    private ?int $home_desired_red_cards = null;
    private ?int $away_desired_red_cards = null;


    private ?Player $player_of_the_match = null;

    public static function create(): self
    {
        return new self();
    }

    private function __construct()
    {
        $this->match_factory = FootballMatch::factory();
    }

    public function withHomeTeam(Team $team): static
    {
        $this->match_factory = $this->match_factory
            ->state(fn () => ['home_team_id' => $team->id]);

        return $this;
    }

    public function withAwayTeam(mixed $team): static
    {
        $this->match_factory = $this->match_factory
            ->state(fn () => ['away_team_id' => $team->id]);

        return $this;
    }

    public function withScore(int $home, int $away): static
    {
        $this->home_desired_score = $home;
        $this->away_desired_score = $away;

        return $this;
    }

    public function withGoalBy(
        Player $player,
        ?GoalType $goal_type = null,
        ?BodyPartType $body_part = null
    ): static {
        $this->goals[] = [
            'player' => $player,
            'goal_type' => $goal_type,
            'body_part' => $body_part,
        ];

        return $this;
    }

    public function withYellowCardTo(Player $player): static
    {
        $this->yellow_cards[] = [
            'player' => $player,
        ];

        return $this;
    }

    public function withYellowCards(int $home, int $away): static
    {
        $this->home_desired_yellow_cards = $home;
        $this->away_desired_yellow_cards = $away;

        return $this;
    }

    public function withRedCardTo(Player $player): static
    {
        $this->red_cards[] = [
            'player' => $player,
        ];

        return $this;
    }

    public function withRedCards(int $home, int $away): static
    {
        $this->home_desired_red_cards = $home;
        $this->away_desired_red_cards = $away;

        return $this;
    }

    public function withPlayerOfTheMatch(Player $player): static
    {
        $this->player_of_the_match = $player;

        return $this;
    }

    public function build(): FootballMatch
    {
        $match = null;

        DB::transaction(function () use (&$match) {
            $match = $this->match_factory->create();

            $this->createGoalsTillDesiredScore($match);
            $this->createGoals($match);

            $this->createYellowCardsTillDesired($match);
            $this->createYellowCards($match);

            $this->createRedCardsTillDesired($match);
            $this->createRedCards($match);

            $this->createScore($match);
            $this->createWinner($match);
            $this->createPlayerOfTheMatch($match);
        });

        return $match;
    }

    private function createGoalsTillDesiredScore(FootballMatch $match): void
    {
        $home_team_players = Player::whereTeamId($match->home_team_id)->get();
        for ($i = 0; $i < $this->home_desired_score ?? 0; $i++) {
            $player = $home_team_players->random();

            $this->withGoalBy($player);
        }

        $away_team_players = Player::whereTeamId($match->away_team_id)->get();
        for ($j = 0; $j < $this->away_desired_score ?? 0; $j++) {
            $player = $away_team_players->random();

            $this->withGoalBy($player);
        }
    }

    private function createGoals(FootballMatch $match): void
    {
        $goal_events = [];
        $match_events = [];

        foreach ($this->goals as $goal) {
            /** @var Player $player */
            $player = $goal['player'];
            $goal_type = $goal['goal_type'] ?? null;
            $body_part = $goal['body_part'] ?? null;

            if (false === in_array($player->team_id, [$match->home_team_id, $match->away_team_id])) {
                throw new InvalidArgumentException("Player's '$player->id' team '$player->team_id' doesn't play in this match '$match->id'.");
            }

            $goal_event = GoalEvent::factory()
                ->state(fn () => ['player_id' => $player->id, 'team_id' => $player->team_id])
                ->state(fn (array $attributes) => ['goal_type' => $goal_type ?? $attributes['goal_type'], 'body_part' => $body_part ?? $attributes['body_part']])
                ->make();

            $match_event = MatchEvent::factory()
                ->state(fn () => ['id' => $goal_event->id, 'type' => MatchEventType::Goal->morphKey()])
                ->state(fn () => ['match_id' => $match->id])
                ->make();

            $match_event_array = $match_event->toArray();
            $match_event_array['timestamp'] = Carbon::parse($match_event['timestamp'])->format($match_event->getDateFormat());

            $goal_events[] = $goal_event->toArray();
            $match_events[] = $match_event_array;
        }

        DB::transaction(function () use ($goal_events, $match_events) {
            GoalEvent::insert($goal_events);
            MatchEvent::insert($match_events);
        });
    }

    private function createYellowCardsTillDesired(FootballMatch $match): void
    {
        $home_team_players = Player::whereTeamId($match->home_team_id)->get();
        for ($i = 0; $i < $this->home_desired_yellow_cards ?? 0; $i++) {
            $player = $home_team_players->random();

            $this->withYellowCardTo($player);
        }

        $away_team_players = Player::whereTeamId($match->away_team_id)->get();
        for ($j = 0; $j < $this->away_desired_yellow_cards ?? 0; $j++) {
            $player = $away_team_players->random();

            $this->withYellowCardTo($player);
        }
    }

    private function createYellowCards(FootballMatch $match): void
    {
        $yellow_card_events = [];
        $match_events = [];

        foreach ($this->yellow_cards as $goal) {
            /** @var Player $player */
            $player = $goal['player'];

            if (false === in_array($player->team_id, [$match->home_team_id, $match->away_team_id])) {
                throw new InvalidArgumentException("Player's '$player->id' team '$player->team_id' doesn't play in this match '$match->id'.");
            }

            $yellow_card_event = YellowCard::factory()
                ->state(fn () => ['player_id' => $player->id])
                ->make();

            $match_event = MatchEvent::factory()
                ->state(fn () => ['id' => $yellow_card_event->id, 'type' => MatchEventType::YellowCard->morphKey()])
                ->state(fn () => ['match_id' => $match->id])
                ->make();

            $match_event_array = $match_event->toArray();
            $match_event_array['timestamp'] = Carbon::parse($match_event['timestamp'])->format($match_event->getDateFormat());

            $yellow_card_events[] = $yellow_card_event->toArray();
            $match_events[] = $match_event_array;
        }

        DB::transaction(function () use ($yellow_card_events, $match_events) {
            YellowCard::insert($yellow_card_events);
            MatchEvent::insert($match_events);
        });
    }

    private function createRedCardsTillDesired(FootballMatch $match): void
    {
        $home_team_players = Player::whereTeamId($match->home_team_id)->get();
        for ($i = 0; $i < $this->home_desired_red_cards ?? 0; $i++) {
            $player = $home_team_players->random();

            $this->withRedCardTo($player);
        }

        $away_team_players = Player::whereTeamId($match->away_team_id)->get();
        for ($j = 0; $j < $this->away_desired_red_cards ?? 0; $j++) {
            $player = $away_team_players->random();

            $this->withRedCardTo($player);
        }
    }

    private function createRedCards(FootballMatch $match): void
    {
        $red_card_events = [];
        $match_events = [];

        foreach ($this->red_cards as $goal) {
            /** @var Player $player */
            $player = $goal['player'];

            if (false === in_array($player->team_id, [$match->home_team_id, $match->away_team_id])) {
                throw new InvalidArgumentException("Player's '$player->id' team '$player->team_id' doesn't play in this match '$match->id'.");
            }

            $red_card_event = RedCard::factory()
                ->state(fn () => ['player_id' => $player->id])
                ->make();

            $match_event = MatchEvent::factory()
                ->state(fn () => ['id' => $red_card_event->id, 'type' => MatchEventType::RedCard->morphKey()])
                ->state(fn () => ['match_id' => $match->id])
                ->make();

            $match_event_array = $match_event->toArray();
            $match_event_array['timestamp'] = Carbon::parse($match_event['timestamp'])->format($match_event->getDateFormat());

            $red_card_events[] = $red_card_event->toArray();
            $match_events[] = $match_event_array;
        }

        DB::transaction(function () use ($red_card_events, $match_events) {
            RedCard::insert($red_card_events);
            MatchEvent::insert($match_events);
        });
    }

    private function createScore(FootballMatch $match): void
    {
        $scores = [
            $match->home_team_id => 0,
            $match->away_team_id => 0,
        ];

        foreach ($this->goals as $goal) {
            /** @var Player $player */
            $player = $goal['player'];
            $scores[$player->team_id]++;
        }

        $match->scores()->create([
            'type' => MatchScoreType::Regular,
            'home' => $scores[$match->home_team_id],
            'away' => $scores[$match->away_team_id],
        ]);
    }

    public function createWinner(FootballMatch $match): void
    {
        $score = $match->scores()->first();

        // TODO handle draw
        if ($score->home === $score->away) {
            return;
        }

        $winner_team_id = $score->home > $score->away
            ? $match->home_team_id
            : $match->away_team_id;

        $match->winners()->create([
            'type' => WinType::Regular,
            'team_id' => $winner_team_id
        ]);
    }

    public function createPlayerOfTheMatch(FootballMatch $match): void
    {
        if (null === $this->player_of_the_match) {
            return;
        }

        $match->playerOfTheMatch()->create([
            'player_id' => $this->player_of_the_match->id
        ]);
    }
}
