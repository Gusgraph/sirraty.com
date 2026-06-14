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
    <style>
        .word-filter-list {
            display: grid;
            gap: 3px;
        }

        .word-filter-row {
            display: grid;
            grid-template-columns: minmax(131px, 1fr) 73px 137px 79px 73px 83px;
            gap: 7px;
            align-items: center;
            min-height: 37px;
            padding: 3px 7px;
        }

        .word-filter-row .field {
            margin: 0;
            font-size: .71rem;
            gap: 2px;
        }

        .word-filter-row input,
        .word-filter-row select {
            min-height: 27px;
            padding: 3px 7px;
            font-size: .79rem;
        }

        .word-filter-row .btn {
            min-height: 27px;
            padding: 3px 9px;
            font-size: .79rem;
        }

        .word-filter-word {
            overflow: hidden;
            font-size: .87rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        @media (max-width: 800px) {
            .word-filter-row {
                grid-template-columns: 1fr 73px;
            }
        }
    </style>

    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Word Filters</h1>
            <p class="muted" style="margin:7px 0 0">Whole-word matching. Members see blocked words as ****.</p>
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

    <section class="word-filter-list">
        @forelse($records as $word)
            <article class="panel word-filter-row">
                <form method="POST" action="{{ route('admin.word-filters.update', $word) }}" class="word-filter-row" style="display:contents">
                    @csrf
                    @method('PATCH')
                    <strong class="word-filter-word" title="{{ $word->word }}">{{ $word->word }}</strong>
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
                <form method="POST" action="{{ route('admin.word-filters.destroy', $word) }}" style="margin:0">
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
