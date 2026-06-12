{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/module-create.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="'Create '.$config['singular'].' | Sirraty'">
    <div class="row module-topbar">
        <div>
            <h1 class="section-title" style="margin:0">Create {{ $config['singular'] }}</h1>
            <p class="muted" style="margin:7px 0 0">{{ $config['create_copy'] }}</p>
        </div>
        <a class="btn" href="{{ route('app.module', $module) }}"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <form class="panel module-form" method="POST" action="{{ route('app.modules.store', $module) }}" enctype="multipart/form-data">
        @csrf
        @if($errors->any())<div class="empty" style="margin-bottom:15px">{{ $errors->first() }}</div>@endif

        @if($module === 'market')
            <label class="field">Title <input name="title" value="{{ old('title') }}" maxlength="73" required></label>
            <label class="field">Details <textarea name="description" rows="7" maxlength="2000" required>{{ old('description') }}</textarea></label>
        @else
            <label class="field">Name <input name="name" value="{{ old('name') }}" maxlength="73" required></label>
            <label class="field">Description <textarea name="description" rows="7" maxlength="2000">{{ old('description') }}</textarea></label>
        @endif

        <div class="module-form-sections">
            <section class="module-form-section">
                <h2>Media</h2>
                @if($module === 'market')
                    <label class="field">Image <input name="media" type="file" accept="image/png,image/jpeg,image/webp,image/gif"></label>
                    <label class="field">Price <input name="price" type="number" step="0.01" min="0" value="{{ old('price') }}"></label>
                @else
                    <label class="field">Avatar <input name="avatar_upload" type="file" accept="image/png,image/jpeg,image/webp,image/gif"></label>
                    <label class="field">Cover <input name="cover_upload" type="file" accept="image/png,image/jpeg,image/webp,image/gif"></label>
                @endif
            </section>

            <section class="module-form-section">
                <h2>Category</h2>
                <label class="field">Category
                    <select name="category_id">
                        <option value="">Select</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
                @if($module === 'market')
                    <label class="field">City
                        <select name="location_id">
                            <option value="">Select</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" @selected((string) old('location_id') === (string) $location->id)>{{ $location->name }}</option>
                            @endforeach
                        </select>
                    </label>
                @endif
            </section>

            @if(in_array($module, ['pages', 'groups'], true))
                <section class="module-form-section">
                    <h2>Address</h2>
                    <label class="field">Country
                        <select name="address_country">
                            <option value="">Select</option>
                            @foreach($countries as $code => $country)
                                <option value="{{ $code }}" @selected(old('address_country') === $code)>{{ $country }}</option>
                            @endforeach
                        </select>
                    </label>
                    <div class="module-form-grid">
                        <label class="field">State / Region <input name="address_region" value="{{ old('address_region') }}" maxlength="73"></label>
                        <label class="field">City <input name="address_city" value="{{ old('address_city') }}" maxlength="73"></label>
                    </div>
                    <label class="field">Postal / ZIP Code <input name="address_postal_code" value="{{ old('address_postal_code') }}" maxlength="27"></label>
                    <label class="field">Address <input name="address_line" value="{{ old('address_line') }}" maxlength="191"></label>
                </section>
            @endif

            @if($module === 'pages')
                <section class="module-form-section">
                    <h2>Access</h2>
                    <label class="field">Visibility
                        <select name="visibility">
                            @foreach(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('visibility', 'public') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </section>
            @elseif($module === 'groups')
                <section class="module-form-section">
                    <h2>Access</h2>
                    <label class="field">Visibility
                        <select name="type">
                            @foreach(['public' => 'Public', 'approval' => 'By Approval', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', 'public') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Rules <textarea name="rules" rows="5" maxlength="2000">{{ old('rules') }}</textarea></label>
                </section>
            @endif
        </div>

        <button class="btn primary" type="submit"><i class="fa-solid fa-plus"></i> Create</button>
    </form>
</x-layouts.app>
