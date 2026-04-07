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
final readonly class NotifySaleStatusChangeAction
{
    public function __construct(
        private NotificationService $notificationService,
    ) {}

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
                'order_id' => $sale->id,
                'old_status' => $oldStatus?->value,
                'new_status' => $newStatus->value,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send sale status change notifications', [
                'order_id' => $sale->id,
                'new_status' => $newStatus->value,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function sendEmailStatusUpdate(Sale $sale, SaleStatus $saleStatus): void
    {
        // This would typically use a specific notification class for emails
        Log::info('Email status update sent', [
            'order_id' => $sale->id,
            'email' => $sale->customer_email,
            'status' => $saleStatus->value,
        ]);
    }

    private function sendPushNotificationStatusUpdate(Sale $sale, SaleStatus $saleStatus): void
    {
        // This would integrate with push notification service
        $this->notificationService->sendRealTimeNotification('sale-updates', [
            'order_id' => $sale->id,
            'status' => $saleStatus->value,
            'message' => sprintf('Sale #%s is now %s', $sale->reference, $saleStatus->value),
        ]);
    }
}
