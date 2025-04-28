<x-filament::page>

    <div style="display: flex; flex-direction: column; gap: 50px;">

        {{-- Project Summary Card --}}
        <div style="padding: 24px; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 16px;">
            <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 24px; color: orange;">Task Summary by Project</h2>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: orange; color: white;">
                        <tr>
                            <th style="padding: 12px; text-align: left;">Project Name</th>
                            <th style="padding: 12px; text-align: left;">Pending</th>
                            <th style="padding: 12px; text-align: left;">In Progress</th>
                            <th style="padding: 12px; text-align: left;">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->projects as $project)
                            <tr style="border-top: 1px solid #ddd; color: orange">
                                <td style="padding: 12px; font-weight: bold">{{ $project->name }}</td>
                                <td style="padding: 12px;">{{ $project->pending_tasks_count }}</td>
                                <td style="padding: 12px;">{{ $project->in_progress_tasks_count }}</td>
                                <td style="padding: 12px;">{{ $project->completed_tasks_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- User Summary Card --}}
        <div style="padding: 24px; background: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); border-radius: 16px;">
            <h2 style="font-size: 24px; font-weight: bold; margin-bottom: 24px; color: orange;">Task Summary by User</h2>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background-color: orange; color: white;">
                        <tr>
                            <th style="padding: 12px; text-align: left;">User Name</th>
                            <th style="padding: 12px; text-align: left;">Pending</th>
                            <th style="padding: 12px; text-align: left;">In Progress</th>
                            <th style="padding: 12px; text-align: left;">Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->users as $user)
                            <tr style="border-top: 1px solid #ddd; color: orange">
                                <td style="padding: 12px; font-weight: bold">{{ $user->name }}</td>
                                <td style="padding: 12px;">{{ $user->pending_tasks_count }}</td>
                                <td style="padding: 12px;">{{ $user->in_progress_tasks_count }}</td>
                                <td style="padding: 12px;">{{ $user->completed_tasks_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-filament::page>
