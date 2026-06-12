{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/app/module-edit.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="'Edit '.$record->name.' | Sirraty'">
    <div class="row module-topbar">
        <div>
            <h1 class="section-title" style="margin:0">Edit {{ $config['singular'] }}</h1>
            <p class="muted" style="margin:7px 0 0">{{ $record->name }}</p>
        </div>
        <a class="btn" href="{{ $module === 'pages' ? route('app.pages.show', $record) : route('app.groups.show', $record) }}"><i class="fa-solid fa-arrow-left"></i> Back</a>
    </div>

    <form class="panel module-form" method="POST" action="{{ $module === 'pages' ? route('app.pages.update', $record) : route('app.groups.update', $record) }}" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        @if($errors->any())<div class="empty" style="margin-bottom:15px">{{ $errors->first() }}</div>@endif

        <label class="field">Name <input name="name" value="{{ old('name', $record->name) }}" maxlength="73" required></label>
        <label class="field">Description <textarea name="description" rows="7" maxlength="2000">{{ old('description', $record->description) }}</textarea></label>

        <div class="module-form-sections">
            <section class="module-form-section">
                <h2>Media</h2>
                @if($record->avatar_url)
                    <span class="profile-avatar module-avatar" style="margin:0 0 11px">
                        <img src="{{ $record->avatar_url }}" alt="">
                    </span>
                @endif
                <label class="field">Avatar <input name="avatar_upload" type="file" accept="image/png,image/jpeg,image/webp,image/gif"></label>
                @if($record->cover_url)
                    <div class="module-cover" style="margin:0 0 11px;min-height:117px;background-image:linear-gradient(117deg, rgba(23, 34, 28, .17), rgba(57, 255, 136, .11)), url('{{ $record->cover_url }}')"></div>
                @endif
                <label class="field">Cover <input name="cover_upload" type="file" accept="image/png,image/jpeg,image/webp,image/gif"></label>
            </section>

            <section class="module-form-section">
                <h2>Category</h2>
                <label class="field search-select" data-search-select>Category
                    <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search categories" aria-label="Search categories" data-search-select-input>
                    <select name="category_id" data-search-select-menu>
                        <option value="">Select</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $record->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </label>
            </section>

            <section class="module-form-section">
                <h2>Address</h2>
                <label class="field search-select" data-search-select>Country
                    <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search countries" aria-label="Search countries" data-search-select-input>
                    <select name="country_id" data-search-select-menu data-geo-role="country">
                        <option value="">Select</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" @selected((string) old('country_id', $record->country_id) === (string) $country->id)>{{ $country->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field search-select" data-search-select>State / Region
                    <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search states" aria-label="Search states" data-search-select-input>
                    <select name="state_id" data-search-select-menu data-search-url="{{ route('app.options', 'states') }}" data-geo-role="state">
                        <option value="">Select</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}" data-country-id="{{ $state->country_id }}" @selected((string) old('state_id', $record->state_id) === (string) $state->id)>{{ $state->name }}{{ $state->country ? ', '.$state->country->code : '' }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field search-select" data-search-select>City
                    <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search cities" aria-label="Search cities" data-search-select-input>
                    <select name="city_id" data-search-select-menu data-search-url="{{ route('app.options', 'cities') }}" data-geo-role="city">
                        <option value="">Select</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" data-country-id="{{ $city->country_id }}" data-state-id="{{ $city->state_id }}" @selected((string) old('city_id', $record->city_id) === (string) $city->id)>{{ $city->name }}{{ $city->state ? ', '.$city->state->name : '' }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="field">Postal / ZIP Code <input name="address_postal_code" value="{{ old('address_postal_code', $record->address_postal_code) }}" maxlength="27"></label>
                <label class="field">Address <input name="address_line" value="{{ old('address_line', $record->address_line) }}" maxlength="191"></label>
            </section>

            <section class="module-form-section">
                <h2>Settings</h2>
                @if($module === 'pages')
                    <label class="field">Visibility
                        <select name="visibility">
                            @foreach(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('visibility', $record->visibility) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Post approval
                        <select name="require_post_approval">
                            <option value="0" @selected(! old('require_post_approval', $record->require_post_approval))>Publish public posts</option>
                            <option value="1" @selected((bool) old('require_post_approval', $record->require_post_approval))>Require owner approval</option>
                        </select>
                    </label>
                @else
                    <label class="field">Visibility
                        <select name="type">
                            @foreach(['public' => 'Public', 'approval' => 'By Approval', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('type', $record->type) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Post approval
                        <select name="require_post_approval">
                            <option value="0" @selected(! old('require_post_approval', $record->require_post_approval))>Publish member posts</option>
                            <option value="1" @selected((bool) old('require_post_approval', $record->require_post_approval))>Require owner approval</option>
                        </select>
                    </label>
                    <label class="field">Rules <textarea name="rules" rows="5" maxlength="2000">{{ old('rules', $record->rules) }}</textarea></label>
                @endif
            </section>
        </div>

        <button class="btn primary" type="submit"><i class="far fa-save"></i> Save</button>
    </form>
</x-layouts.app>
