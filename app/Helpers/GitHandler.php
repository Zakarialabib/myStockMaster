<?php

declare(strict_types=1);

namespace App\Helpers;

class GitHandler
{
    private $message;

    public function checkForUpdates()
    {
        $branch = env('GIT_BRANCH', 'master');
        exec("git fetch origin $branch", $output, $return);

        if ($return === 0) {
            exec('git rev-parse HEAD', $localHead, $return);
            exec('git rev-parse FETCH_HEAD', $remoteHead, $return);

            if ($localHead !== $remoteHead) {
                $this->message = "Updates available on origin/$branch.";

                return true;
            } else {
                $this->message = 'No updates available.';

                return false;
            }
        } else {
            $this->message = "Error fetching updates from origin/$branch.";

            return false;
        }
    }

    public function fetchAndPull()
    {
        $branch = env('GIT_BRANCH', 'master');
        exec("git fetch origin $branch", $output, $return);

        if ($return === 0) {
            $this->message = "Fetched updates from origin/$branch.";
            exec("git merge origin/$branch", $output, $return);

            if ($return === 0) {
                $this->message = "Merged updates from origin/$branch.";
            } else {
                $this->message = "Error merging updates from origin/$branch.";
            }
        } else {
            $this->message = "Error fetching updates from origin/$branch.";
        }

        return $this->message;
    }
}
