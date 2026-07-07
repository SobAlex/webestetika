<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactEmail implements ShouldQueue
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
    protected string $emailTemplate;

    /**
     * Create a new job instance.
     */
    public function __construct(array $emailData, string $emailTemplate = 'emails.contact')
    {
        $this->emailData = $emailData;
        $this->emailTemplate = $emailTemplate;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::send($this->emailTemplate, $this->emailData, function ($message) {
                $message->to(config('mail.from.address'))
                    ->subject($this->emailData['subject']);
            });

            Log::info('Contact email sent successfully', [
                'template' => $this->emailTemplate,
                'customer_email' => $this->emailData['email'] ?? 'no email'
            ]);

        } catch (\Throwable $e) {
            Log::error('Contact email failed', [
                'error' => $e->getMessage(),
                'template' => $this->emailTemplate,
                'customer_email' => $this->emailData['email'] ?? 'no email'
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
        Log::error('Contact email job failed permanently', [
            'error' => $exception->getMessage(),
            'template' => $this->emailTemplate,
            'customer_email' => $this->emailData['email'] ?? 'no email'
        ]);
    }
}
