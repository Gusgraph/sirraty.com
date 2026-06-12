{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/word-filters.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Word Filters | Admin Zone">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Word Filters</h1>
            <p class="muted" style="margin:7px 0 0">Whole-word matching. Blocked words show as ****.</p>
        </div>
        <span class="row">
            <form method="POST" action="{{ route('admin.word-filters.import') }}">
                @csrf
                <button class="btn" type="submit"><i class="fa-solid fa-download"></i> Import</button>
            </form>
            <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </span>
    </div>

    <form class="panel" method="POST" action="{{ route('admin.word-filters.store') }}" style="margin-bottom:15px">
        @csrf
        <div class="grid" style="grid-template-columns:minmax(191px,1fr) 151px 97px auto;gap:11px;align-items:end">
            <label class="field">Word
                <input name="word" maxlength="73" required>
            </label>
            <label class="field">Action
                <select name="action" required>
                    <option value="blocked">Blocked</option>
                    <option value="auto-hide">Auto-hide</option>
                    <option value="auto-flag">Auto-flag</option>
                    <option value="watch">Watch</option>
                </select>
            </label>
            <label class="field">Severity
                <input name="severity" type="number" min="1" max="9" value="5" required>
            </label>
            <button class="btn primary" type="submit"><i class="fa-solid fa-plus"></i> Add</button>
        </div>
    </form>

    <section class="grid">
        @forelse($records as $word)
            <article class="panel">
                <form method="POST" action="{{ route('admin.word-filters.update', $word) }}" class="row" style="justify-content:space-between">
                    @csrf
                    @method('PATCH')
                    <strong>{{ \App\Services\ModerationWordService::CENSOR }}</strong>
                    <span class="muted">Length {{ mb_strlen($word->word) }}</span>
                    <label class="field" style="margin:0">Action
                        <select name="action">
                            @foreach(['blocked' => 'Blocked', 'auto-hide' => 'Auto-hide', 'auto-flag' => 'Auto-flag', 'watch' => 'Watch'] as $value => $label)
                                <option value="{{ $value }}" @selected($word->action === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field" style="margin:0">Severity
                        <input name="severity" type="number" min="1" max="9" value="{{ $word->severity }}">
                    </label>
                    <button class="btn" type="submit"><i class="fa-solid fa-check"></i> Save</button>
                </form>
                <form method="POST" action="{{ route('admin.word-filters.destroy', $word) }}" style="margin-top:7px">
                    @csrf
                    @method('DELETE')
                    <button class="btn" type="submit"><i class="fa-regular fa-trash-can"></i> Remove</button>
                </form>
            </article>
        @empty
            <div class="empty">No word filters yet.</div>
        @endforelse
    </section>
    {{ $records->links() }}
</x-layouts.app>
