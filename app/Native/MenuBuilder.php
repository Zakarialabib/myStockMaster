<?php

declare(strict_types=1);

namespace App\Native;

use Native\Desktop\Facades\Menu;

class MenuBuilder
{
    /**
     * Build the NativePHP application menu.
     *
     * @return array<\Native\Desktop\Contracts\MenuItem>
     */
    public static function build(): array
    {
        return [
            Menu::app(),
            // Menu::make(
            //     Menu::route('sales.create', 'New Sale', 'CmdOrCtrl+N'),
            //     Menu::route('products.create', 'New Product', 'CmdOrCtrl+P'),
            //     Menu::separator(),
            //     Menu::route('products.import', 'Import Products', 'CmdOrCtrl+I'),
            //     Menu::route('exports.index', 'Export Data', 'CmdOrCtrl+E'),
            //     Menu::separator(),
            //     Menu::label('Sync with Online', 'CmdOrCtrl+S')->event('native.sync.online'),
            //     Menu::separator(),
            //     Menu::quit()
            // )->label('File'),

            Menu::make(
                // Menu::route('dashboard', 'Dashboard', 'CmdOrCtrl+1'),
                // Menu::route('products.index', 'Products', 'CmdOrCtrl+2'),
                // Menu::route('sales.index', 'Sales', 'CmdOrCtrl+3'),
                // Menu::route('pos.index', 'POS', 'CmdOrCtrl+4'),
                // Menu::route('reports.index', 'Reports', 'CmdOrCtrl+5'),
                Menu::separator(),
                Menu::fullscreen(),
                Menu::reload(),
                Menu::devTools()
            )->label('View'),

            Menu::make(
                // Menu::route('settings.index', 'Settings', 'CmdOrCtrl+,'),
                // Menu::route('admin.database-sync', 'Database Sync'),
                Menu::label('Toggle Offline Mode')->event('native.toggle.offline'),
                Menu::separator(),
                Menu::label('Clear Cache')->event('native.cache.clear')
            )->label('Tools'),

            Menu::make(
                Menu::about('About MyStockMaster'),
                Menu::label('Check for Updates')->event('native.check.updates')
            )->label('Help'),
        ];
    }
}
