<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\Update;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    /** @test */
    public function the_component_can_render(): void
    {
        $this->withoutExceptionHandling();

        Livewire::test(Update::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.settings.update');
    }

    /** @test */
    public function can_check_for_updates(): void
    {
        Livewire::test(Update::class)
            ->call('checkForUpdates')
            ->assertStatus(200);
    }
}
