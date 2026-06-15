<?php

namespace App\Models;

use App\Models\Concerns\SirratyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RecoveryCampaign extends Model
{
    use SirratyModel;

    protected function casts(): array
    {
        return [
            'bounce_stop_rate' => 'decimal:2',
            'queued_at' => 'datetime',
            'started_at' => 'datetime',
            'stopped_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(RecoveryDelivery::class);
    }
}
