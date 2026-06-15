{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/pages-edit.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app :title="$pageRecord->name.' | Admin Zone'">
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <div>
            <h1 class="section-title" style="margin:0">Edit Page</h1>
            <p class="muted" style="margin:7px 0 0">{{ $pageRecord->name }} · {{ number_format($pageRecord->followers_count) }} followers · {{ number_format($pageRecord->posts_count) }} posts</p>
        </div>
        <span class="row">
            <a class="btn" href="{{ route('public.pages.show', $pageRecord->slug) }}"><i class="fa-solid fa-up-right-from-square"></i> Preview</a>
            <a class="btn" href="{{ route('app.pages.show', $pageRecord->slug) }}"><i class="fa-regular fa-file-lines"></i> App page</a>
            <a class="btn" href="{{ route('admin.section', 'pages') }}"><i class="fa-solid fa-arrow-left"></i> Pages</a>
        </span>
    </div>

    <form class="panel" method="POST" action="{{ route('admin.pages.update', $pageRecord) }}">
        @csrf
        @method('PATCH')

        <h2 class="section-title">Core</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(273px,1fr));gap:19px">
            <label class="field">Name
                <input name="name" value="{{ old('name', $pageRecord->name) }}" maxlength="73" required>
            </label>
            <label class="field">Slug
                <input name="slug" value="{{ old('slug', $pageRecord->slug) }}" maxlength="73" required>
            </label>
            <label class="field">Owner
                <select name="owner_id" required>
                    @foreach($owners as $owner)
                        <option value="{{ $owner->id }}" @selected((string) old('owner_id', $pageRecord->owner_id) === (string) $owner->id)>{{ $owner->profile?->display_name ?? $owner->name }} · {{ $owner->email }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Category
                <select name="category_id">
                    <option value="">No category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $pageRecord->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Visibility
                <select name="visibility" required>
                    @foreach(['public' => 'Public', 'followers' => 'Followers', 'private' => 'Private', 'hidden' => 'Hidden'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('visibility', $pageRecord->visibility) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Post Approval
                <select name="require_post_approval" required>
                    <option value="1" @selected((string) old('require_post_approval', $pageRecord->require_post_approval ? '1' : '0') === '1')>Require approval</option>
                    <option value="0" @selected((string) old('require_post_approval', $pageRecord->require_post_approval ? '1' : '0') === '0')>Allow direct posting</option>
                </select>
            </label>
            <label class="field">Location Record
                <select name="location_id">
                    <option value="">No location record</option>
                    @foreach($locations as $location)
                        <option value="{{ $location->id }}" @selected((string) old('location_id', $pageRecord->location_id) === (string) $location->id)>{{ $location->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>

        <h2 class="section-title" style="margin-top:27px">Media</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(273px,1fr));gap:19px">
            <div class="field media-upload-field" data-auto-media-upload data-upload-url="{{ route('admin.pages.media', $pageRecord) }}" data-upload-field="avatar" data-target-input="avatar_url">
                <span>Avatar</span>
                <input name="avatar_url" type="hidden" value="{{ old('avatar_url', $pageRecord->avatar_url) }}">
                <div class="media-upload-row">
                    <span class="media-upload-preview" data-media-upload-preview>
                        @if($pageRecord->avatar_url)
                            <img src="{{ $pageRecord->avatar_url }}" alt="">
                        @else
                            <i class="fa-regular fa-file-lines"></i>
                        @endif
                    </span>
                    <label class="media-upload-icon" title="Upload avatar" aria-label="Upload avatar">
                        <i class="fa-solid fa-upload"></i>
                        <input type="file" accept="image/png,image/jpeg,image/webp,image/gif">
                    </label>
                    <small class="media-upload-status" data-media-upload-status>Auto saves after upload</small>
                </div>
            </div>
            <div class="field media-upload-field" data-auto-media-upload data-upload-url="{{ route('admin.pages.media', $pageRecord) }}" data-upload-field="cover" data-target-input="cover_url">
                <span>Cover</span>
                <input name="cover_url" type="hidden" value="{{ old('cover_url', $pageRecord->cover_url) }}">
                <div class="media-upload-row">
                    <span class="media-upload-preview is-cover" data-media-upload-preview>
                        @if($pageRecord->cover_url)
                            <img src="{{ $pageRecord->cover_url }}" alt="">
                        @else
                            <i class="fa-regular fa-image"></i>
                        @endif
                    </span>
                    <label class="media-upload-icon" title="Upload cover" aria-label="Upload cover">
                        <i class="fa-solid fa-upload"></i>
                        <input type="file" accept="image/png,image/jpeg,image/webp,image/gif">
                    </label>
                    <small class="media-upload-status" data-media-upload-status>Auto saves after upload</small>
                </div>
            </div>
        </div>

        <h2 class="section-title" style="margin-top:27px">Location</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(237px,1fr));gap:19px">
            <label class="field">Country
                <select name="country_id">
                    <option value="">No country</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}" @selected((string) old('country_id', $pageRecord->country_id) === (string) $country->id)>{{ $country->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">State
                <select name="state_id">
                    <option value="">No state</option>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}" @selected((string) old('state_id', $pageRecord->state_id) === (string) $state->id)>{{ $state->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">City
                <select name="city_id">
                    <option value="">No city</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}" @selected((string) old('city_id', $pageRecord->city_id) === (string) $city->id)>{{ $city->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="field">Address Country Code
                <input name="address_country" value="{{ old('address_country', $pageRecord->address_country) }}" maxlength="2">
            </label>
            <label class="field">Region
                <input name="address_region" value="{{ old('address_region', $pageRecord->address_region) }}" maxlength="73">
            </label>
            <label class="field">City Name
                <input name="address_city" value="{{ old('address_city', $pageRecord->address_city) }}" maxlength="73">
            </label>
            <label class="field">Postal / ZIP Code
                <input name="address_postal_code" value="{{ old('address_postal_code', $pageRecord->address_postal_code) }}" maxlength="27">
            </label>
            <label class="field">Address Line
                <input name="address_line" value="{{ old('address_line', $pageRecord->address_line) }}" maxlength="191">
            </label>
        </div>

        <h2 class="section-title" style="margin-top:27px">Description</h2>
        <label class="field">
            <textarea name="description" rows="11" maxlength="5000">{{ old('description', $pageRecord->description) }}</textarea>
        </label>

        @if(isset($errors) && $errors->any())
            <div class="flash-message error"><span>{{ $errors->first() }}</span></div>
        @endif

        <div class="row" style="justify-content:flex-end;margin-top:19px">
            <button class="btn primary" type="submit"><i class="fa-solid fa-floppy-disk"></i> Save</button>
        </div>
    </form>
</x-layouts.app>
