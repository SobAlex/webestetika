<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendServiceOrderEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    protected array $emailData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::send('emails.service-order', $this->emailData, function ($message) {
                $message->to(config('mail.from.address'))
                    ->subject($this->emailData['subject']);

                // Attach file if present
                if (!empty($this->emailData['attachmentPath']) && file_exists(storage_path('app/public/' . $this->emailData['attachmentPath']))) {
                    $message->attach(storage_path('app/public/' . $this->emailData['attachmentPath']), [
                        'as' => $this->emailData['attachmentName'] ?? 'attachment',
                        'mime' => $this->emailData['attachmentMime'] ?? 'application/octet-stream',
                    ]);
                }
            });

            Log::info('Service order email sent successfully', [
                'service_name' => $this->emailData['serviceName'],
                'customer_email' => $this->emailData['email']
            ]);

        } catch (\Throwable $e) {
            Log::error('Service order email failed', [
                'error' => $e->getMessage(),
                'service_name' => $this->emailData['serviceName'],
                'customer_email' => $this->emailData['email']
            ]);

            // Повторить попытку отправки
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Service order email job failed permanently', [
            'error' => $exception->getMessage(),
            'service_name' => $this->emailData['serviceName'],
            'customer_email' => $this->emailData['email']
        ]);
    }
}
