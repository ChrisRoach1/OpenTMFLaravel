<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title', 'metadata_type_id', 'is_required'])]
class DocumentMetadata extends Model
{
    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
        ];
    }

    public function metadataType(): BelongsTo
    {
        return $this->belongsTo(MetadataType::class);
    }
}
