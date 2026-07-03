<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'description', 'file_path', 'flow_type', 'current_stage', 'meta'])]
class Document extends Model
{
    const FLOW_TYPE_TEMPLATE = 'template';
    const FLOW_TYPE_CUSTOM = 'custom';

    const STAGE_PENDING_MANAGER = 'pending_manager';
    const STAGE_PENDING_HR = 'pending_hr';
    const STAGE_PENDING_APPROVERS = 'pending_approvers';
    const STAGE_APPROVED = 'approved';
    const STAGE_REJECTED = 'rejected';

    protected function casts(): array
    {
        return [
            'meta' => 'array',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvalTracks(): HasMany
    {
        return $this->hasMany(ApprovalTrack::class);
    }

    public function isTemplateFlow(): bool
    {
        return $this->flow_type === self::FLOW_TYPE_TEMPLATE;
    }

    public function isCustomFlow(): bool
    {
        return $this->flow_type === self::FLOW_TYPE_CUSTOM;
    }

    public function isApproved(): bool
    {
        return $this->current_stage === self::STAGE_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->current_stage === self::STAGE_REJECTED;
    }

    public function isPending(): bool
    {
        return in_array($this->current_stage, [
            self::STAGE_PENDING_MANAGER,
            self::STAGE_PENDING_HR,
            self::STAGE_PENDING_APPROVERS,
        ]);
    }
}
