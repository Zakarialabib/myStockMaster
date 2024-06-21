<?php

declare(strict_types=1);

namespace App\Livewire\Utils\QueueMonitor;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    public $job_id;
    public $name;
    public $queue;
    public $started_at;
    public $finished_at;
    public $failed;
    public $attempt;
    public $exception_message;
    public $aggregatedInfo;
    public $totalJobsExecuted;
    public $totalExecutionTime;
    public $averageExecutionTime;

    public function mount()
    {
        $aggregationColumns = [
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(finished_at - started_at) as total_time_elapsed'),
            DB::raw('AVG(finished_at - started_at) as average_time_elapsed'),
        ];

        $this->aggregatedInfo = DB::table('jobs')
            ->select($aggregationColumns)
            ->first();

        $this->totalJobsExecuted = $this->aggregatedInfo->count ?? 0;
        $this->totalExecutionTime = ($this->aggregatedInfo->total_time_elapsed ?? 0).'s';
        $this->averageExecutionTime = ceil((float) $this->aggregatedInfo->average_time_elapsed).'s' ?? 0;
    }

    public function delete()
    {
        $this->model->delete();

        $this->dispatch('refresh');
    }

    public function render()
    {
        $query = DB::table('jobs');

        if ($this->search) {
            $query->where('job_id', 'like', '%'.$this->search.'%');
        }

        $query->orderBy($this->sortBy, $this->sortDirection);

        $jobs = $query->paginate($this->perPage);

        return view('livewire.tools.queue-monitor.index', compact('jobs'));
    }
}
