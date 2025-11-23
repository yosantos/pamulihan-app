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
 * Send Bulk Campaign Job
 *
 * Queued job for sending campaign messages to multiple recipients
 * Processes in batches to avoid memory issues
 *
 * Usage:
 * ```php
 * $recipients = [
 *     ['phone' => '628123456789', 'variables' => ['name' => 'John']],
 *     ['phone' => '628987654321', 'variables' => ['name' => 'Jane']],
 * ];
 *
 * SendBulkCampaignJob::dispatch('Newsletter', $recipients);
 * ```
 *
 * @package App\Jobs
 */
class SendBulkCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public int $timeout = 300; // 5 minutes

    /**
     * Create a new job instance.
     *
     * @param string $campaignName The campaign name to send
     * @param array $recipients Array of ['phone' => '', 'variables' => []]
     * @param int|null $userId User ID who triggered this bulk send
     * @param bool $dispatchIndividually If true, dispatch individual jobs instead of bulk sending
     */
    public function __construct(
        public string $campaignName,
        public array $recipients,
        public ?int $userId = null,
        public bool $dispatchIndividually = false
    ) {
        $this->onQueue('whatsapp-bulk');
    }

    /**
     * Execute the job.
     *
     * @param CampaignMessageService $campaignService
     * @return void
     */
    public function handle(CampaignMessageService $campaignService): void
    {
        Log::info('Bulk campaign job started', [
            'campaign_name' => $this->campaignName,
            'recipients_count' => count($this->recipients),
            'dispatch_individually' => $this->dispatchIndividually,
        ]);

        if ($this->dispatchIndividually) {
            // Dispatch individual jobs for each recipient
            // Better for rate limiting and retries
            $this->dispatchIndividualJobs();
        } else {
            // Send all at once using bulk method
            $this->sendBulk($campaignService);
        }
    }

    /**
     * Dispatch individual jobs for each recipient
     *
     * @return void
     */
    protected function dispatchIndividualJobs(): void
    {
        $dispatched = 0;

        foreach ($this->recipients as $recipient) {
            $phone = $recipient['phone'] ?? null;
            $variables = $recipient['variables'] ?? [];

            if (!$phone) {
                Log::warning('Skipping recipient without phone number', [
                    'campaign' => $this->campaignName,
                    'recipient' => $recipient,
                ]);
                continue;
            }

            // Dispatch individual job
            SendCampaignMessageJob::dispatch(
                $this->campaignName,
                $phone,
                $variables,
                $this->userId
            );

            $dispatched++;
        }

        Log::info('Individual campaign jobs dispatched', [
            'campaign_name' => $this->campaignName,
            'dispatched_count' => $dispatched,
            'total_recipients' => count($this->recipients),
        ]);
    }

    /**
     * Send bulk campaign using the service
     *
     * @param CampaignMessageService $campaignService
     * @return void
     */
    protected function sendBulk(CampaignMessageService $campaignService): void
    {
        $batchSize = 100; // Process in batches of 100
        $batches = array_chunk($this->recipients, $batchSize);

        $totalSuccess = 0;
        $totalFailed = 0;

        foreach ($batches as $batchIndex => $batch) {
            Log::info('Processing batch', [
                'campaign_name' => $this->campaignName,
                'batch' => $batchIndex + 1,
                'batch_size' => count($batch),
            ]);

            $result = $campaignService->sendBulkCampaign(
                $this->campaignName,
                $batch,
                $this->userId
            );

            $totalSuccess += $result['success_count'];
            $totalFailed += $result['failed_count'];

            // Log failed messages in this batch
            foreach ($result['results'] as $messageResult) {
                if (!$messageResult['success']) {
                    Log::warning('Bulk campaign message failed', [
                        'campaign' => $this->campaignName,
                        'phone' => $messageResult['phone'],
                        'error' => $messageResult['message'],
                    ]);
                }
            }

            // Small delay between batches to avoid overwhelming the API
            if (count($batches) > 1 && $batchIndex < count($batches) - 1) {
                sleep(2);
            }
        }

        Log::info('Bulk campaign job completed', [
            'campaign_name' => $this->campaignName,
            'total_recipients' => count($this->recipients),
            'success_count' => $totalSuccess,
            'failed_count' => $totalFailed,
        ]);
    }

    /**
     * Handle a job failure.
     *
     * @param \Throwable $exception
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Bulk campaign job failed permanently', [
            'campaign_name' => $this->campaignName,
            'recipients_count' => count($this->recipients),
            'error' => $exception->getMessage(),
        ]);
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
            'bulk',
            "campaign:{$this->campaignName}",
            'recipients:' . count($this->recipients),
        ];
    }
}
