<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire\Settings;

use App\Livewire\Settings\Messaging;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class MessagingTest extends TestCase
{
    #[Test]
    public function the_component_can_render(): void
    {
        $this->withoutExceptionHandling();

        Livewire::test(Messaging::class)
            ->assertStatus(200)
            ->assertViewIs('livewire.settings.messaging');
    }

    #[Test]
    public function component_loads_telegram_channel(): void
    {
        Livewire::test(Messaging::class)
            ->assertSet('botToken', settings()?->telegram_channel);
    }

    #[Test]
    public function can_toggle_modals(): void
    {
        Livewire::test(Messaging::class)
            ->call('openProductModal')
            ->assertSet('openProductModal', true)
            ->call('openTemplate')
            ->assertSet('openTemplate', true);
    }

    #[Test]
    public function updated_type_resets_chat_id(): void
    {
        Livewire::test(Messaging::class)
            ->set('chatId', '12345')
            ->set('type', 'telegram')
            ->assertSet('chatId', '');
    }
}
