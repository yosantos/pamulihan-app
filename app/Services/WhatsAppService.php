<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url');
        $this->apiKey = config('services.whatsapp.api_key');
    }

    /**
     * Send WhatsApp message via microservice
     *
     * @param string $phoneNumber Phone number with country code (e.g., 6283821348593)
     * @param string $message The message content
     * @return array Response from WhatsApp API
     * @throws Exception
     */
    public function send(string $phoneNumber, string $message): array
    {
        try {
            // Remove any spaces, dashes, or plus signs from phone number
            $phoneNumber = preg_replace('/[\s\-\+]/', '', $phoneNumber);

            // Ensure phone number starts with country code
            if (!str_starts_with($phoneNumber, '62') && str_starts_with($phoneNumber, '0')) {
                // Convert Indonesian local format (0xxx) to international (62xxx)
                $phoneNumber = '62' . ltrim($phoneNumber, '0');
            }

            $response = Http::timeout(30)
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($this->apiUrl, [
                    'to' => $phoneNumber,
                    'message' => $message,
                ]);

            if ($response->failed()) {
                $errorMessage = $response->json('message') ?? 'Failed to send WhatsApp message';
                Log::error('WhatsApp API Error', [
                    'phone' => $phoneNumber,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                throw new Exception($errorMessage);
            }

            Log::info('WhatsApp message sent successfully', [
                'phone' => $phoneNumber,
                'message_length' => strlen($message),
            ]);

            return [
                'success' => true,
                'data' => $response->json(),
                'message' => 'WhatsApp message sent successfully',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp Service Exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send WhatsApp message with file attachment
     *
     * @param string $phoneNumber Phone number with country code
     * @param string $message The message content (caption)
     * @param string $filePath Full path to the file to send
     * @return array Response from WhatsApp API
     * @throws Exception
     */
    public function sendWithFile(string $phoneNumber, string $message, string $filePath): array
    {
        try {
            // Remove any spaces, dashes, or plus signs from phone number
            $phoneNumber = preg_replace('/[\s\-\+]/', '', $phoneNumber);

            // Ensure phone number starts with country code
            if (!str_starts_with($phoneNumber, '62') && str_starts_with($phoneNumber, '0')) {
                // Convert Indonesian local format (0xxx) to international (62xxx)
                $phoneNumber = '62' . ltrim($phoneNumber, '0');
            }

            // Verify file exists
            if (!file_exists($filePath)) {
                throw new Exception("File not found: {$filePath}");
            }

            $response = Http::timeout(60) // Increase timeout for file uploads
                ->withHeaders([
                    'x-api-key' => $this->apiKey,
                ])
                ->attach('file', file_get_contents($filePath), basename($filePath))
                ->post($this->apiUrl, [
                    'to' => $phoneNumber,
                    'message' => $message,
                ]);

            if ($response->failed()) {
                $errorMessage = $response->json('message') ?? 'Failed to send WhatsApp message with file';
                Log::error('WhatsApp API Error (with file)', [
                    'phone' => $phoneNumber,
                    'file' => basename($filePath),
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                throw new Exception($errorMessage);
            }

            Log::info('WhatsApp message with file sent successfully', [
                'phone' => $phoneNumber,
                'file' => basename($filePath),
                'message_length' => strlen($message),
            ]);

            return [
                'success' => true,
                'data' => $response->json(),
                'message' => 'WhatsApp message with file sent successfully',
            ];
        } catch (Exception $e) {
            Log::error('WhatsApp Service Exception (with file)', [
                'phone' => $phoneNumber,
                'file' => $filePath ?? 'N/A',
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Send bulk WhatsApp messages
     *
     * @param array $recipients Array of ['phone' => '', 'message' => '']
     * @return array Results of each send attempt
     */
    public function sendBulk(array $recipients): array
    {
        $results = [];

        foreach ($recipients as $recipient) {
            try {
                $result = $this->send($recipient['phone'], $recipient['message']);
                $results[] = [
                    'phone' => $recipient['phone'],
                    'success' => true,
                    'response' => $result,
                ];
            } catch (Exception $e) {
                $results[] = [
                    'phone' => $recipient['phone'],
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Validate phone number format
     *
     * @param string $phoneNumber
     * @return bool
     */
    public function validatePhoneNumber(string $phoneNumber): bool
    {
        $cleaned = preg_replace('/[\s\-\+]/', '', $phoneNumber);

        // Check if it's a valid Indonesian number
        return preg_match('/^(62|0)\d{8,12}$/', $cleaned);
    }
}
