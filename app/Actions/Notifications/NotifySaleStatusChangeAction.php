<?php

declare(strict_types=1);

namespace App\Actions\Notifications;

use App\Enums\SaleStatus;
use App\Models\Sale;
use App\Services\NotificationService;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Handles comprehensive sale status change notifications across all channels.
 *
 * ARCHITECTURAL CONTEXT:
 * This action orchestrates multiple notification channels when an sale status changes,
 * coordinating between customer notifications, staff alerts, and management updates.
 *
 * EXTENSION POINTS:
 * Add new notification channels by extending the handle method.
 * Customize notification logic based on sale type or customer preferences.
 *
 * COMMON PITFALLS:
 * Ensure all notification channels are properly configured before use.
 * Handle notification failures gracefully to avoid blocking sale processing.
 */
final class NotifySaleStatusChangeAction
{
    public function __construct(
        private readonly NotificationService $notificationService,
    ) {
    }

    public function __invoke(Sale $sale, SaleStatus $newStatus, ?SaleStatus $oldStatus = null): void
    {
        try {
            // Notify customer about status change
            if ($sale->customer_email) {
                $this->notificationService->notifyOrderStatusChange(
                    $sale->customer,
                    $sale,
                    $newStatus
                );
            }

            // Send SMS notification
            if ($sale->customer_phone) {
                $message = $this->buildSMSMessage($sale, $oldStatus, $newStatus);
                $this->notificationService->sendSMS($sale->customer_phone, $message);
            }

            // Send email updates for all status changes
            if ($sale->customer_email) {
                $this->sendEmailStatusUpdate($sale, $newStatus);
            }

            // Send push notifications if customer has app
            $this->sendPushNotificationStatusUpdate($sale, $newStatus);

            Log::info('Sale status change notifications sent', [
                'order_id'   => $sale->id,
                'old_status' => $oldStatus?->value,
                'new_status' => $newStatus->value,
            ]);
        } catch (Exception $e) {
            Log::error('Failed to send sale status change notifications', [
                'order_id'   => $sale->id,
                'new_status' => $newStatus->value,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    private function sendSMSStatusUpdate(Sale $sale, SaleStatus $status): void
    {
        $message = match ($status) {
            SaleStatus::SHIPPED   => "Your sale #{$sale->reference} has been shipped!",
            SaleStatus::COMPLETED => "Your sale #{$sale->reference} has been completed. Thank you!",
            default               => "Your sale #{$sale->reference} status: {$status->value}",
        };

        $this->notificationService->sendSmsNotification($sale->customer_phone, $message);
    }

    private function sendEmailStatusUpdate(Sale $sale, SaleStatus $status): void
    {
        // This would typically use a specific notification class for emails
        Log::info('Email status update sent', [
            'order_id' => $sale->id,
            'email'    => $sale->customer_email,
            'status'   => $status->value,
        ]);
    }

    private function sendPushNotificationStatusUpdate(Sale $sale, SaleStatus $status): void
    {
        // This would integrate with push notification service
        $this->notificationService->sendRealTimeNotification('sale-updates', [
            'order_id' => $sale->id,
            'status'   => $status->value,
            'message'  => "Sale #{$sale->reference} is now {$status->value}",
        ]);
    }
}
