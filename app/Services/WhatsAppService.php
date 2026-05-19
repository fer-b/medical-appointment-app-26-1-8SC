<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function __construct()
    {
        // Ya no necesitamos cargar variables de Twilio
    }

    /**
     * Sends an order confirmation message.
     */
    public function sendConfirmation($phone, $orderData)
    {
        $message = "Hola {$orderData['client_name']}, tu pedido con {$orderData['employee_name']} ha sido agendado para el día {$orderData['date']} a las {$orderData['time']}.";
        return $this->sendMessage($phone, $message);
    }

    /**
     * Sends an order reminder message.
     */
    public function sendReminder($phone, $orderData)
    {
        $message = "Recordatorio: {$orderData['client_name']}, tienes un pedido programado para mañana con {$orderData['employee_name']} a las {$orderData['time']}.";
        return $this->sendMessage($phone, $message);
    }

    /**
     * Internal method to send the message via HTTP.
     */
    protected function sendMessage($phone, $message)
    {
        $callMeBotKey = env('CALLMEBOT_API_KEY');

        // --- OPCIÓN 1: CALLMEBOT (Gratis e instantáneo) ---
        if (!empty($callMeBotKey)) {
            try {
                $response = Http::get("https://api.callmebot.com/whatsapp.php", [
                    'phone' => $phone,
                    'text' => $message,
                    'apikey' => $callMeBotKey
                ]);

                if ($response->successful()) {
                    Log::info("WhatsApp enviado vía CallMeBot a {$phone}. Respuesta: " . $response->body());
                    return true;
                }
            } catch (\Exception $e) {
                Log::error("Excepción CallMeBot: " . $e->getMessage());
            }
        }

        // --- FALLBACK: LOG (Simulación) ---
        Log::info("WhatsApp SIMULADO (Sin API configurada) para {$phone}: {$message}");
        return true;
    }
}
