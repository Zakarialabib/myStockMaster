<?php

declare(strict_types=1);

namespace App\Helpers;

class GitHandler
{
    private $message;
    
    public function fetchAndPull()
    {
        $branch = env('GIT_BRANCH', 'master');

        exec("git fetch origin $branch", $output, $return);

        if ($return === 0) {
            $this->message = "Fetched updates from origin/$branch.";
            exec("git reset --hard FETCH_HEAD", $output, $return);
            if ($return === 0) {
                $this->message = "Hard reset to latest updates from origin/$branch.";
                exec("git clean -df", $output, $return);
                if ($return === 0) {
                    $this->message = "Cleaned up untracked files and directories.";
                } else {
                    $this->message = "Error cleaning untracked files and directories.";
                }
            } else {
                $this->message = "Error resetting to latest updates from origin/$branch.";
            }
        } else {
            $this->message = "__('Error fetching updates from origin')./$branch.";
        }

        return $this->message;
    }

    public function pushChanges()       
    {
        exec("git diff --exit-code", $diff, $return);

        if ($return === 0) {
            $this->message = "No changes to push.";
            return;
        } 
        // Get the repository URL and branch from the .env file
        $repoUrl = env('REPO_URL');
        $repoBranch = env('REPO_BRANCH');
        
        // Push the changes to the remote repository
        exec("git push $repoUrl $repoBranch", $output, $return);
        if ($return=== 0) {
            $this->message = __("Changes pushed successfully.");
            return;
        } 
            
        $this->message = __("Error pushing changes.");

    }
}
