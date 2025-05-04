<?php
require 'vendor/autoload.php';

use Twilio\Rest\Client;

function notifyShopOwner($shopOwnerPhone, $orderDetails) {
    // Replace with your Twilio credentials
    $sid    = 'YOUR_TWILIO_ACCOUNT_SID';
    $token  = 'YOUR_TWILIO_AUTH_TOKEN';
    $twilioNumber = 'YOUR_TWILIO_PHONE_NUMBER';

    $client = new Client($sid, $token);

    $message = "🔔 New Order Received\n";
    $message .= "Order #: {$orderDetails['id']}\n";
    $message .= "Customer: {$orderDetails['user']}\n";
    $message .= "Total: ₹" . number_format($orderDetails['total'], 2) . "\n";
    $message .= "Payment: {$orderDetails['payment_method']}\n";

    try {
        $client->messages->create(
            $shopOwnerPhone, // e.g., '+91XXXXXXXXXX'
            [
                'from' => $twilioNumber,
                'body' => $message
            ]
        );
    } catch (Exception $e) {
        error_log("SMS sending failed: " . $e->getMessage());
    }
}
