<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use Illuminate\Database\Eloquent\Model;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Tasks';
    protected static ?string $pluralModelLabel = 'Tasks';

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        return $user->can('view_any_task');
    }

    public static function canView(Model $record): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        return $user->can('view_task');
    }

    public static function canCreate(): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        return $user->can('create_task');
    }

    public static function canEdit(Model $record): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        return $user->can('update_task');
    }

    public static function canDelete(Model $record): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        return $user->can('delete_task');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->required(),

                Forms\Components\Select::make('user_id')
                    ->label('Assigned User')
                    ->relationship('user', 'name')
                    ->required(),

                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Textarea::make('description')
                    ->maxLength(250),

                Forms\Components\DatePicker::make('deadline')
                    ->label('Deadline')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned User')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'to_do' => 'warning',
                        'in_progress' => 'info',
                        'done' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('deadline')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'to_do' => 'To Do',
                        'in_progress' => 'In Progress',
                        'done' => 'Done',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // Action: Mark as In Progress
                Tables\Actions\Action::make('mark_in_progress')
                    ->label('Mark In Progress')
                    ->color('info')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation(false)
                    ->visible(fn($record) => $record->status === 'to_do')
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comment')
                            ->maxLength(500), // COMMENT OPSIONAL
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['status' => 'in_progress']);

                        if (!empty($data['comment'])) {
                            $record->taskComments()->create([
                                'comment' => $data['comment'],
                                'user_id' => auth()->id(),
                            ]);
                        }

                        Notification::make()
                            ->title('Task marked as In Progress.')
                            ->success()
                            ->send();
                    }),

                // Action: Mark as Complete
                Action::make('mark_complete')
                    ->label('Mark Complete')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation(false)
                    ->visible(fn($record) => $record->status === 'in_progress')
                    ->form([
                        Forms\Components\Textarea::make('comment')
                            ->label('Comment')
                            ->maxLength(500), // COMMENT OPSIONAL
                    ])
                    ->action(function ($record, array $data) {
                        $record->update(['status' => 'done']);

                        if (!empty($data['comment'])) {
                            $record->taskComments()->create([
                                'comment' => $data['comment'],
                                'user_id' => auth()->id(),
                            ]);
                        }

                        Notification::make()
                            ->title('Task marked as Completed.')
                            ->success()
                            ->send();
                    }),

                    Action::make('backToInProgress')
                    ->label('Mark Incomplete')
                    ->color('danger') 
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->visible(function ($record) {
                        return $record->status === 'done' && auth()->user()->hasRole('Admin');
                    })
                    ->form([
                        Forms\Components\Textarea::make('task_comment')
                            ->label('Reason for Marking Incomplete')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update([
                            'status' => 'in_progress',
                        ]);
                
                        $record->taskComments()->create([
                            'user_id' => auth()->id(),
                            'comment' => $data['task_comment'],
                        ]);
                    })
                    ->modalHeading('Mark Task as Incomplete')
                    ->modalSubheading('Please provide a reason for moving this task back to In Progress.')
                    ->modalButton('Confirm')
                    ->color('danger'),
                

                Tables\Actions\Action::make('view_detail')
                    ->label('View Detail')
                    ->icon('heroicon-o-eye')
                    ->url(fn($record) => route('filament.admin.resources.tasks.view', ['record' => $record]))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
            'view' => Pages\ViewTask::route('/{record}'),
        ];
    }
}
