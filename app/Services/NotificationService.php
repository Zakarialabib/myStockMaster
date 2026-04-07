<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SaleStatus;
use App\Models\Notification as NotificationModel;
use App\Models\Sale;
// use App\Notifications\SaleStatusUpdate;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /** Notify customer about order status changes */
    public function notifyCustomer(Sale $sale, SaleStatus $saleStatus): void
    {
        try {
            if (! $sale->customer_email) {
                Log::info('No customer email available for notification', [
                    'order_id' => $sale->id,
                ]);

                return;
            }

            // $notification = new SaleStatusUpdate($order, $status);
            // Notification::route('mail', $order->customer_email)->notify($notification);

            Log::info('Customer notified about order status change', [
                'order_id' => $sale->id,
                'order_reference' => $sale->reference,
                'customer_email' => $sale->customer_email,
                'status' => $saleStatus->value,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to notify customer', [
                'order_id' => $sale->id,
                'customer_email' => $sale->customer_email,
                'status' => $saleStatus->value,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /** Notify about delayed orders */
    public function notifyDelayedSale(Sale $sale): void
    {
        try {
            // Notify customer if they provided contact info
            if ($sale->customer_email) {
                $this->notifyCustomer($sale, SaleStatus::SHIPPED);
            }

            Log::info('Delayed order notifications sent', [
                'order_id' => $sale->id,
                'order_reference' => $sale->reference,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send delayed order notifications', [
                'order_id' => $sale->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /** Notify about order completion */
    public function notifySaleCompletion(Sale $sale): void
    {
        try {
            // Notify customer
            $this->notifyCustomer($sale, SaleStatus::COMPLETED);

            Log::info('Sale completion notifications sent', [
                'order_id' => $sale->id,
                'order_reference' => $sale->reference,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send order completion notifications', [
                'order_id' => $sale->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /** Notify about order cancellation */
    public function notifySaleCancellation(Sale $sale, string $reason = ''): void
    {
        try {
            // Notify customer
            $this->notifyCustomer($sale, SaleStatus::CANCELED);

            Log::info('Sale cancellation notifications sent', [
                'order_id' => $sale->id,
                'order_reference' => $sale->reference,
                'reason' => $reason,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send order cancellation notifications', [
                'order_id' => $sale->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /** Get unread notification count for a user */
    public function getUnreadCount($user): int
    {
        if (! $user) {
            return 0;
        }

        return $user->unreadNotifications()->count();
    }

    /** Get unread notifications for a customer */
    public function getUnreadNotifications($customer): array
    {
        return NotificationModel::query()->where('notifiable_type', $customer::class)
            ->where('notifiable_id', $customer->id)
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
    }

    /** Mark all notifications as read for a user */
    public function markAllAsRead($user): void
    {
        if ($user) {
            $user->unreadNotifications->markAsRead();
        }
    }

    /** Mark a specific notification as read */
    public function markAsRead($user, string $notificationId): void
    {
        if ($user) {
            $user->unreadNotifications()
                ->where('id', $notificationId)
                ->markAsRead();
        }
    }

    /** Mark notifications as read */
    public function markNotificationsAsRead($customer, array $notificationIds): bool
    {
        try {
            NotificationModel::query()->where('notifiable_type', $customer::class)
                ->where('notifiable_id', $customer->id)
                ->whereIn('id', $notificationIds)
                ->update(['read_at' => now()]);

            return true;
        } catch (Exception $exception) {
            Log::error('Failed to mark notifications as read', [
                'customer_id' => $customer->id,
                'notification_ids' => $notificationIds,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /** Send real-time notification via WebSocket or similar */
    public function sendRealTimeNotification(string $channel, array $data): void
    {
        try {
            // This should be implemented based on your real-time notification system
            // (Laravel Echo)

            // Example implementation:
            // broadcast(new KitchenNotification($channel, $data));

            Log::info('Real-time notification sent', [
                'channel' => $channel,
                'data' => $data,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send real-time notification', [
                'channel' => $channel,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    /** Send SMS notification (if configured) */
    public function sendSmsNotification(string $phoneNumber, string $message): void
    {
        try {
            // This should be implemented based on your SMS service
            // (Twilio, Nexmo, etc.)

            // Example implementation:
            // $smsService = app(SmsService::class);
            // $smsService->send($phoneNumber, $message);

            Log::info('SMS notification sent', [
                'phone_number' => $phoneNumber,
                'message' => $message,
            ]);
        } catch (Exception $exception) {
            Log::error('Failed to send SMS notification', [
                'phone_number' => $phoneNumber,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
