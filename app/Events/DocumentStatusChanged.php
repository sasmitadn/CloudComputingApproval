<?php

namespace App\Events;

use App\Models\Document;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Document $document,
        public string $oldStage,
        public string $newStage,
        public ?int $approverId = null,
        public ?string $comment = null,
    ) {}
}
