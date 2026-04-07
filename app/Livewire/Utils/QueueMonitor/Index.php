<?php

declare(strict_types=1);

namespace App\Livewire\Utils\QueueMonitor;

use App\Traits\WithAlert;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    use WithAlert;

    public mixed $search;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public $perPage = 10;

    public mixed $job_id;

    public mixed $name;

    public mixed $queue;

    public mixed $started_at;

    public mixed $finished_at;

    public mixed $failed;

    public mixed $attempt;

    public mixed $exception_message;

    public mixed $aggregatedInfo;

    public mixed $totalJobsExecuted;

    public mixed $totalExecutionTime;

    public mixed $averageExecutionTime;

    public function mount(): void
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
        $this->totalExecutionTime = ($this->aggregatedInfo->total_time_elapsed ?? 0) . 's';
        $this->averageExecutionTime = ceil((float) $this->aggregatedInfo->average_time_elapsed) . 's' ?? 0;
    }

    public function delete(): void
    {
        $this->model->delete();

        $this->dispatch('refresh');
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $builder = DB::table('jobs');

        if ($this->search) {
            $builder->whereLike('job_id', '%' . $this->search . '%');
        }

        $builder->orderBy($this->sortBy, $this->sortDirection);

        $jobs = $builder->paginate($this->perPage);

        return view('livewire.tools.queue-monitor.index', ['jobs' => $jobs]);
    }
}
