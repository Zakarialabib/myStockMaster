<?php

declare(strict_types=1);

namespace App\Livewire\Installation;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class PendingApproval extends Component
{
    public function mount()
    {
        // Check if user is already authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Redirect based on user role
            if ($user->hasRole('admin') || $user->hasRole('manager') || $user->hasRole('staff')) {
                return redirect()->route('dashboard');
            }

            if ($user->hasRole('customer')) {
                return redirect()->route('menu.index');
            }
        }

        // Check if admin is authenticated
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return null;
    }

    public function render()
    {
        return view('livewire.installation.pending-approval');
    }
}
