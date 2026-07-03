<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Models\ApprovalTrack;
use App\Models\Document;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        if ($data['flow_type'] === Document::FLOW_TYPE_TEMPLATE) {
            $data['current_stage'] = Document::STAGE_PENDING_MANAGER;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Document $record */
        $record = $this->record;

        if ($record->isCustomFlow() && isset($this->data['approver_ids'])) {
            foreach ($this->data['approver_ids'] as $approverId) {
                ApprovalTrack::create([
                    'document_id' => $record->id,
                    'approver_id' => $approverId,
                    'stage' => Document::STAGE_PENDING_APPROVERS,
                    'status' => ApprovalTrack::STATUS_PENDING,
                ]);
            }

            $record->update(['current_stage' => Document::STAGE_PENDING_APPROVERS]);
        }
    }
}
