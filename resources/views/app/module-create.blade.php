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
                @if($module === 'market')
                    <label class="field search-select" data-search-select>Main Category
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search main categories" aria-label="Search main categories" data-search-select-input>
                        <select data-search-select-menu data-category-role="parent">
                            <option value="">Select</option>
                            @foreach($categories->whereNull('parent_id') as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field search-select" data-search-select>Category
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search categories" aria-label="Search categories" data-search-select-input>
                        <select name="market_category_id" data-search-select-menu data-search-url="{{ route('app.options', 'categories') }}" data-category-type="market" data-category-role="child">
                            <option value="">Select</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" data-parent-id="{{ $category->parent_id }}" @selected((string) old('market_category_id') === (string) $category->id)>{{ $category->parent ? $category->parent->name.' / ' : '' }}{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field search-select" data-search-select>Country
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search countries" aria-label="Search countries" data-search-select-input>
                        <select name="country_id" data-search-select-menu data-geo-role="country">
                            <option value="">Select</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected((string) old('country_id') === (string) $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field search-select" data-search-select>State / Region
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search states" aria-label="Search states" data-search-select-input>
                        <select name="state_id" data-search-select-menu data-search-url="{{ route('app.options', 'states') }}" data-geo-role="state">
                            <option value="">Select</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" data-country-id="{{ $state->country_id }}" @selected((string) old('state_id') === (string) $state->id)>{{ $state->name }}{{ $state->country ? ', '.$state->country->code : '' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field search-select" data-search-select>City
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search cities" aria-label="Search cities" data-search-select-input>
                        <select name="city_id" data-search-select-menu data-search-url="{{ route('app.options', 'cities') }}" data-geo-role="city">
                            <option value="">Select</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" data-country-id="{{ $city->country_id }}" data-state-id="{{ $city->state_id }}" @selected((string) old('city_id') === (string) $city->id)>{{ $city->name }}{{ $city->state ? ', '.$city->state->name : '' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Condition
                        <select name="condition">
                            @foreach(['' => 'Select', 'new' => 'New', 'like_new' => 'Like new', 'good' => 'Good', 'fair' => 'Fair', 'used' => 'Used'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('condition') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">Listing type
                        <select name="listing_type">
                            @foreach(['sale' => 'For sale', 'service' => 'Service', 'job' => 'Job', 'event' => 'Event', 'free' => 'Free'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('listing_type', 'sale') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                @else
                    <label class="field search-select" data-search-select>Category
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search categories" aria-label="Search categories" data-search-select-input>
                        <select name="category_id" data-search-select-menu>
                            <option value="">Select</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                @endif
            </section>

            @if(in_array($module, ['pages', 'groups'], true))
                <section class="module-form-section">
                    <h2>Address</h2>
                    <label class="field search-select" data-search-select>Country
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search countries" aria-label="Search countries" data-search-select-input>
                        <select name="country_id" data-search-select-menu data-geo-role="country">
                            <option value="">Select</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" @selected((string) old('country_id') === (string) $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field search-select" data-search-select>State / Region
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search states" aria-label="Search states" data-search-select-input>
                        <select name="state_id" data-search-select-menu data-search-url="{{ route('app.options', 'states') }}" data-geo-role="state">
                            <option value="">Select</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" data-country-id="{{ $state->country_id }}" @selected((string) old('state_id') === (string) $state->id)>{{ $state->name }}{{ $state->country ? ', '.$state->country->code : '' }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field search-select" data-search-select>City
                        <input type="text" inputmode="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" placeholder="Search cities" aria-label="Search cities" data-search-select-input>
                        <select name="city_id" data-search-select-menu data-search-url="{{ route('app.options', 'cities') }}" data-geo-role="city">
                            <option value="">Select</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}" data-country-id="{{ $city->country_id }}" data-state-id="{{ $city->state_id }}" @selected((string) old('city_id') === (string) $city->id)>{{ $city->name }}{{ $city->state ? ', '.$city->state->name : '' }}</option>
                            @endforeach
                        </select>
                    </label>
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
