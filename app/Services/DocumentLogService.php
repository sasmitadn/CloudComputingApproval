<?php

namespace App\Services;

use App\Models\Document;
use Google\Cloud\Firestore\FirestoreClient;

class DocumentLogService
{
    protected FirestoreClient $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient([
            'projectId' => env('GOOGLE_CLOUD_PROJECT_ID'),
        ]);
    }

    public function logStatusChange(Document $document, string $oldStatus, string $newStatus, ?string $comment = null, ?int $approverId = null): void
    {
        $data = [
            'document_id' => $document->id,
            'title' => $document->title,
            'old_stage' => $oldStatus,
            'new_stage' => $newStatus,
            'comment' => $comment,
            'approver_id' => $approverId,
            'created_by' => $document->created_by,
            'flow_type' => $document->flow_type,
            'timestamp' => now()->toDateTimeString(),
        ];

        $this->firestore->collection('document_logs')->add($data);
    }
}
