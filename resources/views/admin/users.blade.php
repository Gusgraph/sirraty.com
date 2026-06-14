{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/users.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Users | Admin Zone">
    <style>
        .admin-user-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(137px, 1fr));
            gap: 7px;
            margin-bottom: 15px;
        }

        .admin-user-stat {
            padding: 9px 11px;
            border-top: 1px solid rgba(22, 199, 101, .19);
            border-radius: 7px;
            background: rgba(255, 253, 247, .03);
        }

        .admin-user-stat strong {
            display: block;
            font-size: 1.17rem;
            line-height: 1;
        }

        .admin-user-filters {
            display: grid;
            grid-template-columns: minmax(191px, 1fr) 137px 137px 137px auto auto;
            gap: 7px;
            align-items: end;
            margin-bottom: 15px;
        }

        .admin-user-list {
            display: grid;
            gap: 5px;
        }

        .admin-user-row {
            display: grid;
            grid-template-columns: minmax(217px, 1.4fr) 91px 91px 91px 91px 113px 137px;
            gap: 7px;
            align-items: center;
            padding: 7px 9px;
        }

        .admin-user-identity {
            display: grid;
            gap: 3px;
            min-width: 0;
        }

        .admin-user-identity strong,
        .admin-user-identity span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-user-actions {
            display: flex;
            justify-content: flex-end;
            gap: 5px;
        }

        .admin-user-actions .btn {
            min-height: 31px;
            padding: 5px 9px;
            font-size: .79rem;
        }

        @media (max-width: 1000px) {
            .admin-user-filters,
            .admin-user-row {
                grid-template-columns: 1fr;
            }

            .admin-user-actions {
                justify-content: flex-start;
            }
        }
    </style>

    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Users</h1>
            <p class="muted" style="margin:7px 0 0">Search, review, and manage member access.</p>
        </div>
        <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
    </div>

    <section class="admin-user-stats">
        <div class="admin-user-stat"><strong>{{ number_format($records->total()) }}</strong><span class="muted">Shown by filter</span></div>
        <div class="admin-user-stat"><strong>{{ number_format($statusCounts['active'] ?? 0) }}</strong><span class="muted">Active</span></div>
        <div class="admin-user-stat"><strong>{{ number_format(($statusCounts['limited'] ?? 0) + ($statusCounts['suspended'] ?? 0) + ($statusCounts['banned'] ?? 0)) }}</strong><span class="muted">Restricted</span></div>
        <div class="admin-user-stat"><strong>{{ number_format($roleCounts['owner'] ?? 0) }}</strong><span class="muted">Owners</span></div>
        <div class="admin-user-stat"><strong>{{ number_format(($roleCounts['admin'] ?? 0) + ($roleCounts['moderator'] ?? 0)) }}</strong><span class="muted">Admin team</span></div>
        <div class="admin-user-stat"><strong>{{ number_format($unverifiedCount) }}</strong><span class="muted">Unverified</span></div>
    </section>

    <form class="panel admin-user-filters" method="GET" action="{{ route('admin.section', 'users') }}">
        <label class="field">Search
            <input name="q" value="{{ $filters['search'] }}" autocomplete="off" placeholder="Name, username, email, phone">
        </label>
        <label class="field">Role
            <select name="role">
                <option value="">All roles</option>
                @foreach(['member' => 'Member', 'moderator' => 'Moderator', 'admin' => 'Admin', 'owner' => 'Owner'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['role'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">Status
            <select name="status">
                <option value="">All status</option>
                @foreach(['active' => 'Active', 'limited' => 'Limited', 'suspended' => 'Suspended', 'banned' => 'Banned'] as $value => $label)
                    <option value="{{ $value }}" @selected($filters['status'] === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label class="field">Email
            <select name="verified">
                <option value="">Any</option>
                <option value="yes" @selected($filters['verified'] === 'yes')>Verified</option>
                <option value="no" @selected($filters['verified'] === 'no')>Not verified</option>
            </select>
        </label>
        <button class="btn primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Search</button>
        <a class="btn" href="{{ route('admin.section', 'users') }}">Clear</a>
    </form>

    <section class="admin-user-list">
        @forelse($records as $user)
            <article class="panel admin-user-row">
                <div class="admin-user-identity">
                    <strong>{{ $user->profile?->display_name ?? $user->name }}</strong>
                    <span class="muted">{{ '@'.$user->username }} · {{ $user->email }}</span>
                </div>
                <span class="metric">{{ ucfirst($user->role) }}</span>
                <span class="metric">{{ ucfirst($user->status) }}</span>
                <span class="metric">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</span>
                <span class="muted">{{ number_format($user->posts_count) }} posts</span>
                <span class="muted">{{ $user->last_seen_at ? 'Seen '.$user->last_seen_at->diffForHumans() : 'Not seen yet' }}</span>
                <span class="admin-user-actions">
                    <a class="btn" href="{{ route('profile.show', $user) }}"><i class="fa-regular fa-user"></i></a>
                    <a class="btn primary" href="{{ route('admin.users.edit', $user) }}"><i class="fa-regular fa-pen-to-square"></i> Edit</a>
                </span>
            </article>
        @empty
            <div class="empty">No users match this filter.</div>
        @endforelse
    </section>

    {{ $records->links() }}
</x-layouts.app>
