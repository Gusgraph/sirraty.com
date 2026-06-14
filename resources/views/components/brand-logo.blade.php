{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/components/brand-logo.blade.php --}}
{{-- ===================================================== --}}
@props(['text' => 'Sirraty', 'variant' => 'image'])
@if($variant === 'text')
    <span {{ $attributes->merge(['class' => 'sirraty-text-logo']) }} data-text="{{ $text }}"><span>{{ $text }}</span><i class="logo-mark-feather" aria-hidden="true"></i></span>
@else
    <span {{ $attributes->merge(['class' => 'sirraty-image-logo']) }}><img src="https://res.cloudinary.com/duja2smra/image/upload/Logo-Sirraty.com_o0hrjr.webp" alt="{{ $text }}"></span>
@endif
