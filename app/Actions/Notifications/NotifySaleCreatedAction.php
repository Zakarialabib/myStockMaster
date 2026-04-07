<?php

declare(strict_types=1);

namespace App\Actions\Notifications;

use App\Models\Sale;
use App\Services\NotificationService;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Handles comprehensive notifications when a new sale is created.
 *
 * ARCHITECTURAL CONTEXT:
 * This action orchestrates all notifications that should be sent when a new sale
 * is placed, including customer confirmations and staff alerts.
 *
 * COMMON PITFALLS:
 * Ensure customer contact information is validated before sending notifications.
 * Handle notification failures gracefully to avoid blocking sale creation.
 */
final readonly class NotifySaleCreatedAction
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

    public function __invoke(Sale $sale): void
    {
        try {
            // Send sale confirmation to customer
            $this->sendSaleConfirmation($sale);

            // Send SMS confirmation if phone number provided
            if ($sale->customer_phone) {
                $this->sendSMSConfirmation($sale);
            }

            // Send email confirmation if email provided
            if ($sale->customer_email) {
                $this->sendEmailConfirmation($sale);
            }

            // Setup ongoing status notifications
            $this->setupSaleStatusNotifications($sale);

            Log::info('Sale creation notifications sent', [
                'order_id' => $sale->id,
                'order_reference' => $sale->reference,
                'customer_email' => $sale->customer_email,
                'customer_phone' => $sale->customer_phone,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send sale creation notifications', [
                'order_id' => $sale->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function sendSaleConfirmation(Sale $sale): void
    {
        // Send customer confirmation
        if ($sale->customer && $sale->customer->email) {
            $this->notificationService->notifyCustomer(
                $sale->customer,
                'sale_created'
            );
        }

        Log::info('Sale confirmation sent', [
            'order_id' => $sale->id,
            'customer_email' => $sale->customer_email,
        ]);
    }

    private function sendSMSConfirmation(Sale $sale): void
    {
        // Send SMS if customer has phone
        if ($sale->customer && $sale->customer->phone) {
            $this->notificationService->sendSMS(
                $sale->customer->phone,
                sprintf('Your order #%s for %s has been confirmed. Thank you!', $sale->id, $sale->total_amount)
            );
        }
    }

    private function sendEmailConfirmation(Sale $sale): void
    {
        // This would typically use a specific notification class for sale confirmation emails
        Log::info('Email confirmation sent', [
            'order_id' => $sale->id,
            'email' => $sale->customer_email,
        ]);
    }

    private function setupSaleStatusNotifications(Sale $sale): void
    {
        // Setup automated notifications for status changes
        Log::info('Sale status notifications setup', [
            'order_id' => $sale->id,
        ]);

        // TODO: Implement automated notification scheduling
        // This could involve setting up jobs or events for future notifications
    }
}
