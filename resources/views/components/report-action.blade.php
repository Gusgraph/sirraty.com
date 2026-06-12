{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/components/report-action.blade.php --}}
{{-- ===================================================== --}}
@props(['type', 'id'])

@auth
    <details class="report-action">
        <summary><i class="fa-regular fa-flag"></i> Report</summary>
        <form method="POST" action="{{ route('app.reports.store') }}">
            @csrf
            <input type="hidden" name="reportable_type" value="{{ $type }}">
            <input type="hidden" name="reportable_id" value="{{ $id }}">
            <label class="field">Reason
                <select name="reason" required>
                    <option value="Spam">Spam</option>
                    <option value="Harassment">Harassment</option>
                    <option value="Hate or abuse">Hate or abuse</option>
                    <option value="Scam or fraud">Scam or fraud</option>
                    <option value="Unsafe content">Unsafe content</option>
                    <option value="Other">Other</option>
                </select>
            </label>
            <label class="field">Details
                <textarea name="details" rows="3" maxlength="1000"></textarea>
            </label>
            <button type="submit"><i class="fa-solid fa-paper-plane"></i> Send</button>
        </form>
    </details>
@endauth
