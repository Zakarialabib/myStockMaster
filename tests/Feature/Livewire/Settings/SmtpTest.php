<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\Smtp;
use Livewire\Livewire;
use Tests\TestCase;

class SmtpTest extends TestCase
{
    /** @test */
    public function the_component_can_render(): void
    {
        $this->withoutExceptionHandling();

        Livewire::test(Smtp::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.settings.smtp');
    }

    /** @test */
    public function component_loads_mail_config(): void
    {
        Livewire::test(Smtp::class)
            ->assertSet('mail_mailer', config('mail.mailer'))
            ->assertSet('mail_host', config('mail.host'));
    }
}
