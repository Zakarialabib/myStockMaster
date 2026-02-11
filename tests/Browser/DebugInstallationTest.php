<?php

declare(strict_types=1);

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Exception;

class DebugInstallationTest extends DuskTestCase
{
    /** @test */
    public function it_shows_what_is_rendered_on_install_page()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/install');

            // Wait a bit for page to load
            sleep(2);

            // Get the page title
            try {
                $title = $browser->driver->getTitle();
                echo "\n\nPage Title: ".$title."\n";
            } catch (Exception $e) {
                echo "\nError getting title: ".$e->getMessage()."\n";
            }

            // Get current URL
            try {
                $currentUrl = $browser->driver->getCurrentURL();
                echo 'Current URL: '.$currentUrl."\n";
            } catch (Exception $e) {
                echo "\nError getting URL: ".$e->getMessage()."\n";
            }

            // Try to get page source
            try {
                $pageSource = $browser->driver->getPageSource();
                echo "\nPage Source (first 2000 chars):\n";
                echo substr($pageSource, 0, 2000)."...\n";
            } catch (Exception $e) {
                echo "\nError getting page source: ".$e->getMessage()."\n";
            }
        });
    }
}
