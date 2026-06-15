<?php

namespace App\Services\Mail;

use App\Models\RecoveryCampaign;
use App\Models\RecoveryDelivery;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class RecoveryAudienceService
{
    public function query(): Builder
    {
        return User::query()
            ->whereNotNull('email')
            ->where('email', 'not like', '%.test')
            ->whereNull('email_suppressed_at')
            ->where('email_status', 'active')
            ->whereNull('email_verified_at')
            ->whereIn('status', ['active', 'limited']);
    }

    public function preview(int $limit = 250): array
    {
        $base = $this->query();

        return [
            'eligible' => (clone $base)->count(),
            'sample' => (clone $base)->latest('id')->limit(10)->get(['id', 'name', 'username', 'email', 'created_at']),
            'suppressed' => User::whereNotNull('email_suppressed_at')->count(),
            'bounced' => User::where('email_status', 'bounced')->count(),
            'complained' => User::where('email_status', 'complained')->count(),
            'unsubscribed' => User::where('email_status', 'unsubscribed')->count(),
            'limit' => $limit,
        ];
    }

    public function seedCampaign(RecoveryCampaign $campaign, int $limit): int
    {
        $count = 0;
        $this->query()
            ->select(['id', 'email'])
            ->orderBy('id')
            ->chunkById(250, function ($users) use ($campaign, $limit, &$count): bool {
                foreach ($users as $user) {
                    if ($count >= $limit) {
                        return false;
                    }

                    RecoveryDelivery::firstOrCreate(
                        ['recovery_campaign_id' => $campaign->id, 'email' => strtolower($user->email)],
                        [
                            'user_id' => $user->id,
                            'status' => 'queued',
                            'unsubscribe_token' => Str::random(48),
                        ],
                    );
                    $count++;
                }

                return true;
            });

        $campaign->update(['recipient_count' => $count]);

        return $count;
    }
}
