<?php

namespace App\Models;

use App\Models\Concerns\SirratyModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecoveryDelivery extends Model
{
    use SirratyModel;

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'feedback_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(RecoveryCampaign::class, 'recovery_campaign_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
