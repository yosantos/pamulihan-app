<?php

namespace App\Jobs;

use App\Services\CampaignMessageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

/**
 * Send Campaign Message Job
 *
 * Queued job for sending campaign messages asynchronously
 *
 * Usage:
 * ```php
 * SendCampaignMessageJob::dispatch('Welcome Campaign', '628123456789', [
 *     'user_name' => 'John Doe',
 *     'code' => '123456'
 * ]);
 * ```
 *
 * @package App\Jobs
 */
class SendCampaignMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public int $backoff = 60;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     */
    public int $maxExceptions = 3;

    /**
     * Delete the job if its models no longer exist.
     *
     * @var bool
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param string $campaignName The campaign name to send
     * @param string $phoneNumber The recipient's phone number
     * @param array $variables Variables for template replacement
     * @param int|null $userId User ID who triggered this message
     * @param int|null $campaignId Optional: Use campaign ID instead of name
     */
    public function __construct(
        public string $campaignName,
        public string $phoneNumber,
        public array $variables = [],
        public ?int $userId = null,
        public ?int $campaignId = null
    ) {
        // Set queue and connection if needed
        $this->onQueue('whatsapp');
    }

    /**
     * Execute the job.
     *
     * @param CampaignMessageService $campaignService
     * @return void
     */
    public function handle(CampaignMessageService $campaignService): void
    {
        Log::info('Queued campaign message job started', [
            'campaign_name' => $this->campaignName,
            'campaign_id' => $this->campaignId,
            'phone' => $this->phoneNumber,
            'attempt' => $this->attempts(),
        ]);

        try {
            // Send using campaign ID or name
            if ($this->campaignId) {
                $result = $campaignService->sendCampaignMessage(
                    $this->campaignId,
                    $this->phoneNumber,
                    $this->variables,
                    $this->userId
                );
            } else {
                $result = $campaignService->sendCampaignByName(
                    $this->campaignName,
                    $this->phoneNumber,
                    $this->variables,
                    $this->userId
                );
            }

            if ($result['success']) {
                Log::info('Queued campaign message sent successfully', [
                    'campaign_name' => $this->campaignName,
                    'phone' => $this->phoneNumber,
                    'message_id' => $result['data']['message_id'] ?? null,
                ]);
            } else {
                Log::warning('Queued campaign message failed', [
                    'campaign_name' => $this->campaignName,
                    'phone' => $this->phoneNumber,
                    'error' => $result['message'],
                ]);

                // Release back to queue if retriable
                if ($this->shouldRetry($result['message'])) {
                    $this->release($this->backoff);
                } else {
                    // Don't retry for these errors
                    $this->fail(new \Exception($result['message']));
                }
            }
        } catch (\Exception $e) {
            Log::error('Queued campaign message exception', [
                'campaign_name' => $this->campaignName,
                'phone' => $this->phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Determine if the job should be retried based on the error message
     *
     * @param string $errorMessage
     * @return bool
     */
    protected function shouldRetry(string $errorMessage): bool
    {
        // Don't retry for these errors
        $nonRetriableErrors = [
            'not found',
            'not active',
            'Invalid phone number',
            'Missing required variables',
        ];

        foreach ($nonRetriableErrors as $error) {
            if (str_contains(strtolower($errorMessage), strtolower($error))) {
                return false;
            }
        }

        // Retry for network/API errors
        return true;
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Queued campaign message job failed permanently', [
            'campaign_name' => $this->campaignName,
            'phone' => $this->phoneNumber,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts(),
        ]);

        // You can notify admins, update database, etc.
        // Example: Send notification to admin
        // Notification::route('mail', config('app.admin_email'))
        //     ->notify(new CampaignMessageFailedNotification($this));
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags(): array
    {
        return [
            'campaign',
            'whatsapp',
            "campaign:{$this->campaignName}",
            "phone:{$this->phoneNumber}",
        ];
    }
}
