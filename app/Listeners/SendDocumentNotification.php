<?php

namespace App\Listeners;

use App\Events\DocumentStatusChanged;
use App\Services\DocumentLogService;
use Illuminate\Support\Facades\Redis;

class SendDocumentNotification
{
    public function __construct(protected DocumentLogService $logService) {}

    public function handle(DocumentStatusChanged $event): void
    {
        $this->logService->logStatusChange(
            $event->document,
            $event->oldStage,
            $event->newStage,
            $event->comment,
            $event->approverId
        );

        $notificationData = [
            'document_id' => $event->document->id,
            'title' => $event->document->title,
            'old_stage' => $event->oldStage,
            'new_stage' => $event->newStage,
            'timestamp' => now()->toDateTimeString(),
        ];

        Redis::publish('document:notifications', json_encode($notificationData));
    }
}
