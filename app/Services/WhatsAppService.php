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
     * Sends an appointment confirmation message.
     */
    public function sendConfirmation($phone, $appointmentData)
    {
        $message = "Hola {$appointmentData['patient_name']}, tu cita médica con el Dr/Dra. {$appointmentData['doctor_name']} ha sido agendada para el día {$appointmentData['date']} a las {$appointmentData['time']}.";
        return $this->sendMessage($phone, $message);
    }

    /**
     * Sends an appointment reminder message.
     */
    public function sendReminder($phone, $appointmentData)
    {
        $message = "Recordatorio: {$appointmentData['patient_name']}, tienes una cita médica mañana con el Dr/Dra. {$appointmentData['doctor_name']} a las {$appointmentData['time']}. ¡Por favor sé puntual!";
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
