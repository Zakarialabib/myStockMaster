<?php

namespace App\Providers;

use Native\Laravel\Facades\Window;
use Native\Laravel\Facades\Menu;
use Native\Laravel\Contracts\ProvidesPhpIni;

class NativeAppServiceProvider implements ProvidesPhpIni
{
    /**
     * Executed once the native application has been booted.
     * Use this method to open windows, register global shortcuts, etc.
     */
    public function boot(): void
    {
        Menu::create(
            Menu::app(),
            Menu::file(),
            Menu::edit(),
            Menu::view(),
            Menu::window(),
            Menu::help(),
        );

        Window::open()
            ->width(1200)
            ->height(800)
            ->title('MyStockMaster');
    }

    /**
     * Return an array of php.ini directives to be set.
     */
    public function phpIni(): array
    {
        return [
        ];
    }
}
