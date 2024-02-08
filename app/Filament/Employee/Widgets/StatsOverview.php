<?php

namespace App\Filament\Employee\Widgets;

use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $projects = auth()->user()->projects->unique();
        $tasks = auth()->user()->tasks;

        return [
            Stat::make('My Projects', $projects->count())
                ->chart([7, 3, 4, 6, 2, 5, 4, 6]),
            Stat::make(
                'Finished Projects',

                $projects->filter(function ($project) {
                    return $project->status->name ==
                        \App\Models\ProjectStatus::FINISHED;
                })->count()
            ),
            Stat::make(
                'Projects In-Progress',
                $projects->filter(function ($project) {
                    return $project->status->name ==
                        \App\Models\ProjectStatus::IN_PROGRESS;
                })->count()
            ),

            Stat::make('My Tasks', $tasks->count())
                ->chart([3, 5, 7, 3, 10, 8, 5, 11]),
            Stat::make(
                'Finished Tasks',

                $tasks->filter(function ($task) {
                    return $task->status->name ==
                        \App\Models\TaskStatus::FINISHED;
                })->count()
            ),
            Stat::make(
                'Tasks In-Progress',
                $tasks->filter(function ($task) {
                    return $task->status->name ==
                        \App\Models\TaskStatus::IN_PROGRESS;
                })->count()
            ),
        ];
    }
}
