<?php

declare(strict_types=1);

namespace App\Exports;

trait ForModelsTrait
{
    /** @param mixed $selectedModels */
    public function forModels($selectedModels)
    {
        $this->models = $selectedModels;

        return $this;
    }
}
