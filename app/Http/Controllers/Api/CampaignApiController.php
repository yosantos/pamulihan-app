<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampaignApiLog;
use App\Models\WhatsAppCampaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CampaignApiController extends Controller
{
    /**
     * Send WhatsApp message using campaign template.
     */
    public function send(Request $request): JsonResponse
    {
        $ipAddress = $request->ip();
        $campaignCode = $request->header('X-Campaign-Code');
        $apiKey = $request->header('X-API-Key');

        // Authenticate using campaign_code and api_key
        if (!$campaignCode || !$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Missing authentication headers. X-Campaign-Code and X-API-Key are required.',
            ], 401);
        }

        $campaign = WhatsAppCampaign::where('campaign_code', $campaignCode)
            ->where('api_key', $apiKey)
            ->first();

        if (!$campaign) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid campaign credentials.',
            ], 401);
        }

        // Check if campaign is active
        if (!$campaign->isActive()) {
            $this->logApiCall(
                $campaign->id,
                $request->input('phone', ''),
                $request->all(),
                'failed',
                'Campaign is not active',
                $ipAddress
            );

            return response()->json([
                'success' => false,
                'message' => 'Campaign is not active.',
            ], 403);
        }

        // Extract dynamic placeholders from template
        $placeholders = $this->extractPlaceholders($campaign->template);

        // Validate request data
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            ...$this->buildPlaceholderRules($placeholders),
        ]);

        if ($validator->fails()) {
            $this->logApiCall(
                $campaign->id,
                $request->input('phone', ''),
                $request->all(),
                'failed',
                'Validation failed: ' . json_encode($validator->errors()),
                $ipAddress
            );

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Validate and format phone number
        $phone = $this->formatPhoneNumber($request->input('phone'));
        if (!$phone) {
            $this->logApiCall(
                $campaign->id,
                $request->input('phone', ''),
                $request->all(),
                'failed',
                'Invalid phone number format',
                $ipAddress
            );

            return response()->json([
                'success' => false,
                'message' => 'Invalid phone number format. Use Indonesian format (e.g., 08xxxxxxxxxx or +62xxxxxxxxxx).',
            ], 422);
        }

        // Prepare placeholder values
        $placeholderValues = [];
        foreach ($placeholders as $placeholder) {
            $placeholderValues[$placeholder] = $request->input($placeholder);
        }

        // Replace placeholders in template
        $message = $campaign->replaceVariables($placeholderValues);

        // Send WhatsApp message
        try {
            $response = $this->sendWhatsAppMessage($phone, $message);

            if ($response['success']) {
                $this->logApiCall(
                    $campaign->id,
                    $phone,
                    $request->all(),
                    'success',
                    null,
                    $ipAddress
                );

                // Increment campaign usage count
                $campaign->incrementUsage();

                return response()->json([
                    'success' => true,
                    'message' => 'Message sent successfully.',
                    'data' => [
                        'phone' => $phone,
                        'campaign' => $campaign->name,
                    ],
                ], 200);
            } else {
                $this->logApiCall(
                    $campaign->id,
                    $phone,
                    $request->all(),
                    'failed',
                    $response['error'] ?? 'Unknown error',
                    $ipAddress
                );

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send message.',
                    'error' => $response['error'] ?? 'Unknown error',
                ], 500);
            }
        } catch (\Exception $e) {
            $this->logApiCall(
                $campaign->id,
                $phone,
                $request->all(),
                'failed',
                $e->getMessage(),
                $ipAddress
            );

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the message.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Extract placeholders from template using regex.
     * Looks for patterns like {placeholder_name}
     */
    private function extractPlaceholders(string $template): array
    {
        preg_match_all('/\{(\w+)\}/', $template, $matches);
        return array_unique($matches[1]);
    }

    /**
     * Build validation rules for placeholders.
     */
    private function buildPlaceholderRules(array $placeholders): array
    {
        $rules = [];
        foreach ($placeholders as $placeholder) {
            $rules[$placeholder] = 'required|string';
        }
        return $rules;
    }

    /**
     * Format and validate Indonesian phone number.
     * Converts various formats to international format (62xxx).
     */
    private function formatPhoneNumber(string $phone): ?string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert to international format
        if (str_starts_with($phone, '0')) {
            // 08xxx -> 628xxx
            $phone = '62' . substr($phone, 1);
        } elseif (str_starts_with($phone, '8')) {
            // 8xxx -> 628xxx
            $phone = '62' . $phone;
        } elseif (str_starts_with($phone, '62')) {
            // Already in correct format
            $phone = $phone;
        } else {
            // Invalid format
            return null;
        }

        // Validate Indonesian phone number length (10-13 digits after country code)
        if (strlen($phone) < 11 || strlen($phone) > 15) {
            return null;
        }

        return $phone;
    }

    /**
     * Send WhatsApp message via microservice.
     */
    private function sendWhatsAppMessage(string $phone, string $message): array
    {
        try {
            $apiUrl = config('services.whatsapp.api_url');
            $apiKey = config('services.whatsapp.api_key');

            $response = Http::withHeaders([
                'X-API-Key' => $apiKey,
            ])->post($apiUrl, [
                'phone' => $phone,
                'message' => $message,
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json(),
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $response->body(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Log API call to database.
     */
    private function logApiCall(
        int $campaignId,
        string $phone,
        array $requestData,
        string $status,
        ?string $errorMessage,
        string $ipAddress
    ): void {
        CampaignApiLog::create([
            'campaign_id' => $campaignId,
            'phone' => $phone,
            'request_data' => $requestData,
            'response_status' => $status,
            'error_message' => $errorMessage,
            'ip_address' => $ipAddress,
        ]);
    }
}
