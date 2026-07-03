<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a given phone number.
     * 
     * @param string $phone
     * @param string $message
     * @return bool
     */
    public function sendMessage(string $phone, string $message): bool
    {
        // For demonstration and local development, we simulate the WhatsApp API
        // by logging the payload to storage/logs/laravel.log. 
        // In a production environment, you would use Http::post() to hit 
        // your WhatsApp provider's API (e.g., Twilio, UltraMsg).
        
        /* 
        Example API Call Integration:
        $response = Http::withToken(env('WHATSAPP_API_TOKEN'))
            ->post('https://api.whatsapp.provider.com/messages', [
                'to' => $phone,
                'body' => $message,
            ]);
        return $response->successful();
        */

        // Log the simulation
        Log::channel('single')->info("WhatsApp Notification Dispatched", [
            'to' => $phone,
            'message' => $message,
            'status' => 'Simulated Delivery'
        ]);
        
        return true;
    }
}
