<?php

declare(strict_types=1);

namespace App\Helpers;

class GitHandler
{
    private $message;
    
    public function fetchAndPull()
    {
        $branch = env('GIT_BRANCH', 'master');

        exec("git fetch origin $branch", $output, $return_var);

        if ($return_var === 0) {
            $this->message = "Fetched updates from origin/$branch.";
            exec("git reset --hard FETCH_HEAD", $output, $return_var);
            if ($return_var === 0) {
                $this->message = "Hard reset to latest updates from origin/$branch.";
                exec("git clean -df", $output, $return_var);
                if ($return_var === 0) {
                    $this->message = "Cleaned up untracked files and directories.";
                } else {
                    $this->message = "Error cleaning untracked files and directories.";
                }
            } else {
                $this->message = "Error resetting to latest updates from origin/$branch.";
            }
        } else {
            $this->message = "Error fetching updates from origin/$branch.";
        }

        return $this->message;
    }

    public function pushChanges()
    {
        // Check if there are any changes to push
        exec("git diff --exit-code", $diff, $return_var);

        if ($return_var === 0) {
            $this->message = "No changes to push.";
        } else {
            // Get the repository URL and branch from the .env file
            $repoUrl = env('REPO_URL');
            $repoBranch = env('REPO_BRANCH');

            // Push the changes to the remote repository
            exec("git push $repoUrl $repoBranch", $output, $return_var);

            if ($return_var === 0) {
                $this->message = "Changes pushed successfully.";
            } else {
                $this->message = "Error pushing changes.";
            }
        }
    }
}
