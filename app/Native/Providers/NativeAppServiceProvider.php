<?php

declare(strict_types=1);

namespace App\Native\Providers;

use App\Native\MenuBuilder;
use Exception;
use Illuminate\Support\ServiceProvider;
use Native\Desktop\Contracts\ProvidesPhpIni;
use Native\Desktop\Facades\GlobalShortcut;
use Native\Desktop\Facades\Menu;
use Native\Desktop\Facades\Window;

class NativeAppServiceProvider extends ServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        if (app()->runningInConsole() || app()->runningUnitTests() || ! isset($_SERVER['NATIVEPHP_RUNNING'])) {
            return;
        }

        try {
            $this->configureWindow();
            $this->configureMenu();
            $this->configureShortcuts();
        } catch (Exception $exception) {
            \Illuminate\Support\Facades\Log::warning('NativePHP Desktop API not available: ' . $exception->getMessage());
        }
    }

    private function configureWindow(): void
    {
        Window::open()
            ->title(config('nativephp.window.title', 'MyStockMaster'))
            ->width(config('nativephp.window.width', 1200))
            ->height(config('nativephp.window.height', 800))
            ->minWidth(config('nativephp.window.min_width', 800))
            ->minHeight(config('nativephp.window.min_height', 600))
            ->resizable(config('nativephp.window.resizable', true))
            ->maximizable(true)
            ->minimizable(true)
            ->closable(true)
            ->alwaysOnTop(config('nativephp.window.always_on_top', false))
            ->skipTaskbar(config('nativephp.window.skip_taskbar', false))
            ->showDevTools(config('nativephp.development.show_dev_tools', false))
            ->titleBarStyle(config('nativephp.window.title_bar_style', 'default'));
    }

    private function configureMenu(): void
    {
        if (config('nativephp.menu.enabled', true)) {
            Menu::create(...MenuBuilder::build());
        }
    }

    private function configureShortcuts(): void
    {
        try {
            GlobalShortcut::key('CmdOrCtrl+Shift+M')
                ->event('shortcut:show-main-window');

            GlobalShortcut::key('CmdOrCtrl+Shift+D')
                ->event('shortcut:toggle-dev-tools');
        } catch (Exception $exception) {
            \Illuminate\Support\Facades\Log::info('Desktop shortcuts not available: ' . $exception->getMessage());
        }
    }

    /** Return an array of php.ini directives to be set. */
    public function phpIni(): array
    {
        return [
        ];
    }
}
