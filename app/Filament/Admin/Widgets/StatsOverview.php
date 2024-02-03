<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Project;
use App\Models\ProjectStatus;
use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('All Projects', Project::count())
                ->chart([7, 3, 4, 6, 2, 5, 4, 6]),
            Stat::make(
                'Finished Projects',
                Project::whereRelation(
                    'status',
                    'name',
                    \App\Models\ProjectStatus::FINISHED
                )->count()
            ),
            Stat::make(
                'Projects In-Progress',
                Project::whereRelation(
                    'status',
                    'name',
                    \App\Models\ProjectStatus::IN_PROGRESS
                )->count()
            ),

            Stat::make('All Tasks', Task::count())
                ->chart([3, 5, 7, 3, 10, 8, 5, 11]),
            Stat::make(
                'Finished Tasks',
                Task::whereRelation(
                    'status',
                    'name',
                    \App\Models\TaskStatus::FINISHED
                )->count()
            ),
            Stat::make(
                'Tasks In-Progress',
                Task::whereRelation(
                    'status',
                    'name',
                    \App\Models\TaskStatus::IN_PROGRESS
                )->count()
            ),

            Stat::make('Employees', User::count())
                ->chart([5, 2, 4, 8, 10, 6, 10, 8]),
            Stat::make('Teams', Team::count())
                ->chart([4, 6, 3, 7, 3, 5, 2, 5]),
        ];
    }
}
