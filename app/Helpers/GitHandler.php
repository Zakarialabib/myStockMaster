<?php

declare(strict_types=1);

namespace App\Helpers;

class GitHandler
{
    private ?string $message = null;

    public function checkForUpdates(): bool
    {
        $branch = env('GIT_BRANCH', 'master');
        exec('git fetch origin ' . $branch, $output, $return);

        if ($return === 0) {
            exec('git rev-parse HEAD', $localHead, $return);
            exec('git rev-parse FETCH_HEAD', $remoteHead, $return);

            if ($localHead !== $remoteHead) {
                $this->message = sprintf('Updates available on origin/%s.', $branch);

                return true;
            } else {
                $this->message = 'No updates available.';

                return false;
            }
        } else {
            $this->message = sprintf('Error fetching updates from origin/%s.', $branch);

            return false;
        }
    }

    public function fetchAndPull(): string
    {
        $branch = env('GIT_BRANCH', 'master');
        exec('git fetch origin ' . $branch, $output, $return);

        if ($return === 0) {
            $this->message = sprintf('Fetched updates from origin/%s.', $branch);
            exec('git merge origin/' . $branch, $output, $return);

            if ($return === 0) {
                $this->message = sprintf('Merged updates from origin/%s.', $branch);
            } else {
                $this->message = sprintf('Error merging updates from origin/%s.', $branch);
            }
        } else {
            $this->message = sprintf('Error fetching updates from origin/%s.', $branch);
        }

        return $this->message;
    }
}
