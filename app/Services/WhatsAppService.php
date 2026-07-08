<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a given phone number using Meta Cloud API.
     * 
     * @param string $phone The recipient's phone number (e.g. 1234567890, no plus sign for Meta API)
     * @param string $message The message body
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        $token = env('WHATSAPP_TOKEN');
        $phoneNumberId = env('WHATSAPP_PHONE_NUMBER_ID');

        if (!$token || !$phoneNumberId) {
            Log::error("WhatsApp Notification Failed: Meta credentials not found in .env");
            return false;
        }

        // Clean phone number: Meta API requires the phone number without the '+' sign
        $cleanPhone = ltrim($phone, '+');

        $url = "https://graph.facebook.com/v19.0/{$phoneNumberId}/messages";

        try {
            $response = Http::withToken($token)->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $cleanPhone,
                'type' => 'text',
                'text' => [
                    'body' => $message,
                ]
            ]);

            if ($response->failed()) {
                Log::error("WhatsApp Notification Failed: " . $response->body());
                return false;
            }

            return true;

        } catch (\Exception $e) {
            Log::error("WhatsApp Notification Failed: " . $e->getMessage());
            return false;
        }
    }
}
