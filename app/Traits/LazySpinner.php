<?php

declare(strict_types=1);

namespace App\Traits;

trait LazySpinner
{
    public function placeholder(): string
    {
        return <<<'HTML'
            <div>
                <div class="fixed inset-0 z-[100] flex items-center justify-center bg-white dark:gray-900">
                    <div class="mx-auto my-auto">
                        <svg class="animate-spin inline-block bw-spinner h-16 w-16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span class="hidden h-16 w-16"></span>
                    </div>
                </div>
            </div>
            HTML;
    }
}
