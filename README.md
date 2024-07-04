## Sport statistics

This example project demonstrates a sample sport statistics application, currently featuring football statistics.

**Note:** The code is still a work-in-progress, and the quality may not meet the standards at this time, and is subject to change without notice. This is a functional prototype, demonstrating the basic concepts and ideas.

## Getting started

1. Run `php artisan migrate:fresh` if necessary.
2. Run `php artisan football:seed --processes=10` to populate the database with football data. Note that each process creates 1000 football matches, so 10 processes will generate 10,000 matches. **Be patient**, as this process may take some time, as the code is not yet optimized for performance.

## Troubleshooting

Run `composer du`, when [football module](modules/FootballMatch) isn't found.

## Current Features

The application currently supports filtering and sorting of match events and statistics.

To discover available routes, run `php artisan route:list`.

## API Reference

### Filtering Match Events

You can filter specific events to retrieve targeted data. For instance, to fetch goals scored by a particular player or players, you can use the `player_id` filter within the `goal` filter.

```json
{
    "filter": {
        "goal": {
            "player_id": [...]
        }
    }
}
```

The following filters & sorters are available:

```json
{
    "filter": {
        "goal": {
            "team_id": [...],
            "player_id": [...],
            "goal_type": [...],
            "body_part": [...],
        },
        "yellow_card": {
            "player_id": [...]
        },
        "red_card": {
            "player_Id": [...]
        }
    },
    "sort_dir": "asc"|"desc"
}
```

### Match / Team statistics

_Same filtering rules apply._

The following filters & sorters are available:

```json
{
    "player_id": [...],
    "team_id": [...],
    "position": [...],
    
    "limit": <number>,
    "offset": <number>,
    
    "sort_by": "goals_total"|"goals_head"|...,
    "sort_dir": "asc"|"desc"
}
```
