<?php

namespace Modules\FootballMatch\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\Process;
use Modules\FootballMatch\Builders\MatchBuilder;
use Modules\FootballMatch\Models\Team;
use Throwable;

class Seed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'football:seed {--matches=1000 : Matches made per process} {--processes=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick seed the football matches for testing.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $processes = (int) $this->option('processes');

        if ($processes > 1) {
            $this->spawn($processes);

            return;
        }

        $matches = (int) $this->option('matches');

        for ($i = 0; $i < $matches; $i++) {
            try {
                $this->insert();
            } catch (Throwable) {
                // nothing
            }
        }
    }

    public function spawn(int $processes): void
    {
        Process::pool(function (Pool $pool) use ($processes) {
            for ($i = 0; $i < $processes; $i++) {
                $pool
                    ->command('php artisan football:seed')
                    ->timeout(60 * 5);
            }
        })
            ->start()
            ->wait();
    }

    public function insert(): void
    {
        $home_team_goals = fake()->numberBetween(0, 10);
        $away_team_goals = fake()->numberBetween(0, 10);

        $home_yellow_cards = fake()->numberBetween(0, 2);
        $away_yellow_cards = fake()->numberBetween(0, 2);

        $home_red_cards = fake()->numberBetween(0, 2);
        $away_red_cards = fake()->numberBetween(0, 2);

        $should_create_home_team = fake()->boolean(5);

        $home_team = $should_create_home_team ? Team::factory()->create() : Team::inRandomOrder()->first();
        $home_team = $home_team ?? Team::factory()->create();

        $away_team = Team::whereNot('id', $home_team->id)->inRandomOrder()->first();
        $away_team = $away_team ?? Team::factory()->create();

        $match = MatchBuilder::create()
            ->withHomeTeam($home_team)
            ->withAwayTeam($away_team)
            ->withScore($home_team_goals, $away_team_goals)
            ->withYellowCards($home_yellow_cards, $away_yellow_cards)
            ->withRedCards($home_red_cards, $away_red_cards)
            ->build();
    }
}
