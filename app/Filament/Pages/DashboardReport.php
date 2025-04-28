<?php

namespace App\Filament\Pages;

use App\Models\Project;
use App\Models\User;
use Filament\Pages\Page;

class DashboardReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-report';
    protected static string $view = 'filament.pages.dashboard-report';

    public $projects;
    public $users;

    public function mount()
    {
        $this->projects = Project::withCount([
            'tasks as pending_tasks_count' => fn ($query) => $query->where('status', 'to_do'),
            'tasks as in_progress_tasks_count' => fn ($query) => $query->where('status', 'in_progress'),
            'tasks as completed_tasks_count' => fn ($query) => $query->where('status', 'done'),
        ])->get();

        $this->users = User::withCount([
            'tasks as pending_tasks_count' => fn ($query) => $query->where('status', 'to_do'),
            'tasks as in_progress_tasks_count' => fn ($query) => $query->where('status', 'in_progress'),
            'tasks as completed_tasks_count' => fn ($query) => $query->where('status', 'done'),
        ])->get();
    }
}
