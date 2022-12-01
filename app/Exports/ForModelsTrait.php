<?php

namespace App\Exports;

trait ForModelsTrait
{

    public function forModels($selectedModels)
    {
        $this->models = $selectedModels;

        return $this;
    }
}
