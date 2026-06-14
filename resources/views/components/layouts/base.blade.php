{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/components/layouts/base.blade.php --}}
{{-- ===================================================== --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sirraty' }}</title>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --bg: #f7f4ef; --panel: #fffdf7; --text: #17221c; --muted: #647067; --line: #d9d1c3; --brand: #247553; --gold: #b38b31; --soft: rgba(36,117,83,.11); }
        [data-theme="dark"] { --bg: #111712; --panel: #172019; --text: #edf4ed; --muted: #aeb9ae; --line: #303a31; --brand: #73c79f; --gold: #d1ad57; --soft: rgba(115,199,159,.15); }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; background: var(--bg); color: var(--text); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; letter-spacing: 0; }
        a { color: inherit; text-decoration: none; }
        button, input, textarea, select { font: inherit; }
        .shell { min-height: 100vh; }
        .topbar { position: sticky; top: 0; z-index: 19; display: flex; align-items: center; justify-content: space-between; gap: 19px; padding: 15px 27px; background: color-mix(in srgb, var(--bg) 91%, transparent); border-bottom: 1px solid var(--line); backdrop-filter: blur(15px); }
        .brand { display: inline-flex; align-items: center; gap: 11px; font-weight: 800; font-size: 1.15rem; }
        .brand-mark { width: 37px; height: 37px; display: grid; place-items: center; border-radius: 999px; background: var(--soft); color: var(--brand); }
        .sirraty-text-logo { position: relative; display: inline-block; line-height: .83; letter-spacing: 0; font-weight: 950; color: #0f3f2d; text-shadow: 0 3px 0 rgba(115,199,159,.37), 0 9px 21px rgba(6,38,27,.37); }
        .sirraty-text-logo::before { content: ""; position: absolute; left: 1%; right: 3%; bottom: -.09em; height: .11em; border-radius: 999px; background: linear-gradient(93deg, #073b2b, #247553 37%, #1eb9c5 73%, #b38b31); opacity: .83; z-index: -1; transform: skewX(-17deg); }
        .sirraty-text-logo::after { content: attr(data-text); position: absolute; inset: 0; background: linear-gradient(117deg, #092f23 0%, #247553 31%, #0e766f 57%, #b38b31 83%, #0f3f2d 100%); -webkit-background-clip: text; background-clip: text; color: transparent; filter: drop-shadow(0 1px 0 rgba(255,255,255,.13)); }
        .sirraty-text-logo span { position: relative; z-index: 1; color: transparent; }
        .sirraty-text-logo .logo-mark-feather { position: absolute; right: .01em; top: -.23em; width: .17em; height: .51em; transform: rotate(29deg); transform-origin: 50% 91%; filter: drop-shadow(0 0 11px rgba(115,199,159,.37)); }
        .sirraty-text-logo .logo-mark-feather::before { content: ""; position: absolute; left: .03em; top: 0; width: .13em; height: .43em; border-radius: 999px 999px 999px 3px; background: linear-gradient(153deg, #d1ad57 0 17%, #1eb9c5 31%, #247553 63%, #092f23 100%); clip-path: polygon(50% 0, 89% 13%, 69% 71%, 57% 100%, 50% 83%, 43% 100%, 31% 71%, 11% 13%); }
        .sirraty-text-logo .logo-mark-feather::after { content: ""; position: absolute; left: .083em; top: .19em; width: .017em; height: .21em; border-radius: 999px; background: rgba(9, 47, 35, .73); }
        .sirraty-image-logo { display: inline-flex; align-items: center; line-height: 1; }
        .sirraty-image-logo img { display: block; width: auto; height: 1em; max-width: 100%; object-fit: contain; filter: drop-shadow(0 7px 17px rgba(6,38,27,.19)); }
        .nav { display: flex; align-items: center; gap: 7px; flex-wrap: wrap; }
        .nav a, .nav button, .btn { border: 1px solid var(--line); background: var(--panel); color: var(--text); min-height: 39px; padding: 9px 15px; border-radius: 7px; cursor: pointer; }
        .btn.primary { background: var(--brand); border-color: var(--brand); color: #fff; }
        .btn.link { border-color: transparent; background: transparent; }
        .theme-toggle { display: inline-grid; grid-auto-flow: column; gap: 3px; padding: 3px; border: 1px solid var(--line); border-radius: 999px; background: var(--panel); }
        .theme-toggle input { position: absolute; opacity: 0; }
        .theme-toggle span { display: inline-grid; place-items: center; min-width: 37px; min-height: 31px; border-radius: 999px; color: var(--muted); }
        .theme-toggle input:checked + span { background: var(--soft); color: var(--brand); }
        .wrap { width: min(1187px, calc(100% - 31px)); margin: 0 auto; }
        .grid { display: grid; gap: 19px; }
        .grid.two { grid-template-columns: minmax(0, 1fr) 319px; }
        .panel { background: var(--panel); border: 1px solid var(--line); border-radius: 7px; padding: 19px; }
        .muted { color: var(--muted); }
        .field { display: grid; gap: 7px; margin: 0 0 15px; }
        .field input, .field textarea, .field select { width: 100%; border: 1px solid var(--line); border-radius: 7px; padding: 11px 13px; background: var(--bg); color: var(--text); }
        .password-control { position: relative; display: block; }
        .password-control input { padding-right: 51px; }
        .password-control button { position: absolute; right: 7px; top: 50%; transform: translateY(-50%); width: 37px; height: 37px; display: grid; place-items: center; border: 0; border-radius: 7px; background: transparent; color: var(--muted); cursor: pointer; }
        .password-control button:hover, .password-control button:focus-visible { color: var(--brand); background: var(--soft); outline: 0; }
        .row { display: flex; gap: 11px; align-items: center; flex-wrap: wrap; }
        .section-title { margin: 0 0 15px; font-size: 1.23rem; }
        .empty { padding: 27px; text-align: center; color: var(--muted); border: 1px dashed var(--line); border-radius: 7px; }
        .flash-message { --flash-color: var(--brand); --flash-bg: rgba(36,117,83,.11); position: relative; display: flex; align-items: center; gap: 11px; margin-bottom: 15px; padding: 13px 15px; border: 1px solid color-mix(in srgb, var(--flash-color) 57%, transparent); border-radius: 7px; background: linear-gradient(117deg, var(--flash-bg), color-mix(in srgb, var(--flash-color) 7%, transparent), var(--flash-bg)); color: var(--flash-color); font-weight: 750; box-shadow: 0 0 19px color-mix(in srgb, var(--flash-color) 17%, transparent); animation: flashPulse 2.7s ease-in-out 3; overflow: hidden; }
        .flash-message::before { content: ""; width: 9px; align-self: stretch; border-radius: 999px; background: var(--flash-color); box-shadow: 0 0 17px var(--flash-color); }
        .flash-message::after { content: ""; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(91deg, transparent, color-mix(in srgb, var(--flash-color) 17%, transparent), transparent); transform: translateX(-100%); animation: flashSweep 2.7s ease-in-out 2; }
        .flash-message.success { --flash-color: #168a57; --flash-bg: rgba(22,138,87,.13); }
        .flash-message.info { --flash-color: #187a9b; --flash-bg: rgba(24,122,155,.13); }
        .flash-message.warning { --flash-color: #a36f13; --flash-bg: rgba(163,111,19,.15); }
        .flash-message.error { --flash-color: #b83247; --flash-bg: rgba(184,50,71,.13); }
        @keyframes flashPulse { 0%, 100% { filter: brightness(1); } 50% { filter: brightness(1.17); } }
        @keyframes flashSweep { 0% { transform: translateX(-100%); } 57%, 100% { transform: translateX(100%); } }
        .symbols { pointer-events: none; user-select: none; position: fixed; inset: 0; overflow: hidden; z-index: 0; color: color-mix(in srgb, var(--text) 7%, transparent); }
        .symbols span { position: absolute; font-size: clamp(2.3rem, 9vw, 7.3rem); }
        main, header, .topbar { position: relative; z-index: 1; }
        @media (max-width: 830px) { .topbar { padding: 11px 15px; } .grid.two { grid-template-columns: 1fr; } .nav { width: 100%; } }
    </style>
    @stack('styles')
</head>
<body>
    <div class="symbols" aria-hidden="true">
        <span style="left:7%;top:13%">﷽</span><span style="right:11%;top:19%">ﷺ</span><span style="left:17%;bottom:11%">ﷴ</span><span style="right:19%;bottom:13%">ﷻ</span><span style="left:51%;top:47%">ﷲ</span>
    </div>
    {{ $slot }}
    <script>
        const storedTheme = localStorage.getItem('sirraty-theme') || 'light';
        document.documentElement.dataset.theme = storedTheme;
        document.querySelectorAll('input[name="theme"]').forEach((input) => {
            input.checked = input.value === storedTheme;
            input.addEventListener('change', () => {
                document.documentElement.dataset.theme = input.value;
                localStorage.setItem('sirraty-theme', input.value);
            });
        });
        document.querySelectorAll('[data-theme-cycle]').forEach((button) => {
            button.addEventListener('click', () => {
                const nextTheme = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
                document.documentElement.dataset.theme = nextTheme;
                localStorage.setItem('sirraty-theme', nextTheme);
                document.querySelectorAll('input[name="theme"]').forEach((input) => {
                    input.checked = input.value === nextTheme;
                });
            });
        });
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordToggle);
            if (! input) return;
            button.addEventListener('click', () => {
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
                button.innerHTML = isHidden ? '<i class="fa-regular fa-eye-slash"></i>' : '<i class="fa-regular fa-eye"></i>';
            });
        });
        const placePickerPanels = () => {
            document.querySelectorAll('.composer-tools[open]').forEach((tool) => {
                const panel = tool.querySelector('.picker-panel');
                if (! panel) return;
                tool.classList.remove('picker-opens-up');
                panel.style.maxHeight = '';
                const buttonBox = tool.getBoundingClientRect();
                const below = window.innerHeight - buttonBox.bottom - 19;
                const above = buttonBox.top - 19;
                if (below < 373 && above > below) {
                    tool.classList.add('picker-opens-up');
                    panel.style.maxHeight = `${Math.max(273, Math.min(573, above))}px`;
                } else {
                    panel.style.maxHeight = `${Math.max(273, Math.min(573, below))}px`;
                }
            });
        };
        document.querySelectorAll('.composer-tools').forEach((tool) => {
            tool.addEventListener('toggle', placePickerPanels);
        });
        document.addEventListener('click', (event) => {
            document.querySelectorAll('.composer-tools[open], .comment-tool-picker[open]').forEach((tool) => {
                if (! tool.contains(event.target)) {
                    tool.removeAttribute('open');
                }
            });
        });
        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            document.querySelectorAll('.composer-tools[open], .comment-tool-picker[open]').forEach((tool) => {
                tool.removeAttribute('open');
            });
        });
        window.addEventListener('resize', placePickerPanels);
        window.addEventListener('scroll', placePickerPanels, { passive: true });
        const escapeHtml = (value) => {
            const holder = document.createElement('div');
            holder.textContent = value || '';
            return holder.innerHTML;
        };
        document.querySelectorAll('[data-post-ajax]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                button?.setAttribute('disabled', 'disabled');
                try {
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });
                    if (! response.ok) {
                        form.submit();
                        return;
                    }
                    const data = await response.json();
                    const post = form.closest('.feed-post, .profile-post, article');
                    if (form.dataset.postAjax === 'react') {
                        const likeButton = post?.querySelector('[data-like-button]');
                        const dislikeButton = post?.querySelector('[data-dislike-button]');
                        const likeCount = post?.querySelector('[data-like-count]');
                        const dislikeCount = post?.querySelector('[data-dislike-count]');
                        likeButton?.classList.toggle('is-active', Boolean(data.liked));
                        dislikeButton?.classList.toggle('is-active', Boolean(data.disliked));
                        if (likeButton) likeButton.querySelector('i').className = `${data.liked ? 'fas' : 'far'} fa-heart`;
                        if (dislikeButton) dislikeButton.querySelector('i').className = `${data.disliked ? 'fas' : 'far'} fa-thumbs-down`;
                        if (likeCount) likeCount.textContent = data.likes_count ?? '0';
                        if (dislikeCount) dislikeCount.textContent = data.dislikes_count ?? '0';
                    }
                    if (form.dataset.postAjax === 'save') {
                        const saveButton = post?.querySelector('[data-save-button]');
                        saveButton?.classList.toggle('is-active', Boolean(data.saved));
                        if (saveButton) {
                            saveButton.querySelector('i').className = `${data.saved ? 'fas' : 'far'} fa-bookmark`;
                            saveButton.querySelector('span').textContent = data.saved ? 'Saved' : 'Save';
                        }
                    }
                    if (form.dataset.postAjax === 'comment' && data.comment) {
                        const list = post?.querySelector('[data-comments-list]');
                        const count = post?.querySelector('[data-comment-count]');
                        if (list) {
                            const avatar = data.comment.user_avatar_url
                                ? `<img src="${escapeHtml(data.comment.user_avatar_url)}" alt="">`
                                : `<span>${escapeHtml(data.comment.user_initial || data.comment.user_name.charAt(0) || 'S')}</span>`;
                            const icons = (data.comment.icon_classes || []).map((icon) => `<i class="${escapeHtml(icon)}"></i>`).join('');
                            const media = (data.comment.media || [])
                                .filter((item) => item.media_type === 'image')
                                .map((item) => `<img src="${escapeHtml(item.secure_url)}" alt="">`)
                                .join('');
                            list.insertAdjacentHTML('beforeend', `<div class="comment-item"><a class="comment-avatar" href="${data.comment.user_url}">${avatar}</a><div class="comment-main"><div class="comment-meta-row"><div class="comment-identity"><a class="comment-author" href="${data.comment.user_url}">${escapeHtml(data.comment.user_name)}</a><a class="muted" href="${data.comment.user_url}">@${escapeHtml(data.comment.user_username)}</a><span class="muted">${escapeHtml(data.comment.created_at)}</span></div></div><p>${escapeHtml(data.comment.body || '')}</p>${icons ? `<div class="comment-icon-strip">${icons}</div>` : ''}${media ? `<div class="comment-media-grid">${media}</div>` : ''}</div></div>`);
                        }
                        if (count) count.innerHTML = `<i class="fa-regular fa-comment"></i> ${data.comments_count ?? '0'}`;
                        form.querySelector('[data-comment-icon-values]')?.replaceChildren();
                        form.querySelector('[data-comment-selected-icons]')?.replaceChildren();
                        form.querySelector('[data-comment-media-preview]')?.replaceChildren();
                        form.reset();
                    }
                } catch (error) {
                    form.submit();
                } finally {
                    button?.removeAttribute('disabled');
                }
            });
        });
        document.querySelectorAll('[data-comment-ajax]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                button?.setAttribute('disabled', 'disabled');
                try {
                    const response = await fetch(form.action, {
                        method: form.method || 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });
                    if (! response.ok) {
                        form.submit();
                        return;
                    }
                    const data = await response.json();
                    const post = form.closest('.feed-post, .profile-post, article');
                    const item = form.closest('[data-comment-item]');
                    const count = post?.querySelector('[data-comment-count]');

                    if (form.dataset.commentAjax === 'edit') {
                        if (data.visible === false) {
                            item?.remove();
                        } else {
                            const body = item?.querySelector('[data-comment-body-display]');
                            if (body) body.textContent = data.comment?.body || '';
                            form.closest('details')?.removeAttribute('open');
                        }
                    }

                    if (form.dataset.commentAjax === 'remove') {
                        item?.remove();
                    }

                    if (count) count.innerHTML = `<i class="fa-regular fa-comment"></i> ${data.comments_count ?? '0'}`;
                } catch (error) {
                    form.submit();
                } finally {
                    button?.removeAttribute('disabled');
                }
            });
        });
        document.querySelectorAll('[data-follow-ajax]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = form.querySelector('[data-follow-button]');
                button?.setAttribute('disabled', 'disabled');
                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });
                    if (! response.ok) {
                        form.submit();
                        return;
                    }
                    const data = await response.json();
                    form.action = data.action;
                    form.querySelector('input[name="_method"]')?.remove();
                    if (data.method === 'DELETE') {
                        form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="DELETE">');
                    }
                    if (button) {
                        button.textContent = data.label;
                        button.classList.toggle('is-active', Boolean(data.followed));
                    }
                } catch (error) {
                    form.submit();
                } finally {
                    button?.removeAttribute('disabled');
                }
            });
        });
        const searchSelects = Array.from(document.querySelectorAll('[data-search-select]')).map((field) => {
            const input = field.querySelector('[data-search-select-input]');
            const select = field.querySelector('[data-search-select-menu]');
            if (! input || ! select) return null;
            const list = document.createElement('div');
            list.className = 'search-select-results';
            field.appendChild(list);
            const item = { field, input, select, list, options: Array.from(select.options) };
            const selected = select.selectedOptions[0];
            if (selected && selected.value) input.value = selected.textContent.trim();
            return item;
        }).filter(Boolean);

        const allowedOption = (option) => option.value !== '' && option.dataset.filtered !== '1';

        const closeSearchSelects = (except = null) => {
            searchSelects.forEach((item) => {
                if (item !== except) item.field.classList.remove('is-open');
            });
        };

        const remoteParams = (item) => {
            const params = new URLSearchParams({ q: item.input.value.trim() });
            const items = searchSelects.filter((candidate) => candidate.select.form === item.select.form);
            const country = items.find((candidate) => candidate.select.dataset.geoRole === 'country');
            const state = items.find((candidate) => candidate.select.dataset.geoRole === 'state');
            const parentCategory = items.find((candidate) => candidate.select.dataset.categoryRole === 'parent');
            if (country?.select.value) params.set('country_id', country.select.value);
            if (state?.select.value) params.set('state_id', state.select.value);
            if (parentCategory?.select.value) params.set('parent_id', parentCategory.select.value);
            if (item.select.dataset.categoryType) params.set('category_type', item.select.dataset.categoryType);
            return params;
        };

        const appendRemoteOptions = (item, rows) => {
            rows.forEach((row) => {
                let option = item.options.find((candidate) => candidate.value === String(row.id));
                if (! option) {
                    option = new Option(row.label, row.id);
                    item.select.add(option);
                    item.options.push(option);
                }
                if (row.country_id) option.dataset.countryId = row.country_id;
                if (row.state_id) option.dataset.stateId = row.state_id;
                if (row.parent_id) option.dataset.parentId = row.parent_id;
                option.dataset.filtered = '0';
            });
        };

        const renderSearchSelect = async (item) => {
            if (item.select.dataset.searchUrl) {
                const response = await fetch(`${item.select.dataset.searchUrl}?${remoteParams(item).toString()}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                });
                if (response.ok) {
                    const data = await response.json();
                    appendRemoteOptions(item, data.items || []);
                }
            }
            const term = item.input.value.toLowerCase().trim();
            const matches = item.options.filter((option) => allowedOption(option) && (term === '' || option.textContent.toLowerCase().includes(term))).slice(0, 73);
            item.list.innerHTML = matches.length
                ? matches.map((option) => `<button class="search-select-option" type="button" data-value="${escapeHtml(option.value)}">${escapeHtml(option.textContent.trim())}</button>`).join('')
                : '<button class="search-select-option" type="button" disabled>No matches</button>';
            item.field.classList.add('is-open');
        };

        const chooseSearchOption = (item, value) => {
            const option = item.options.find((candidate) => candidate.value === value);
            if (! option) return;
            item.select.value = option.value;
            item.input.value = option.value ? option.textContent.trim() : '';
            item.field.classList.remove('is-open');
            item.select.dispatchEvent(new Event('change', { bubbles: true }));
        };

        const clearSearchSelect = (item) => {
            item.select.value = '';
            item.input.value = '';
            item.select.dispatchEvent(new Event('change', { bubbles: true }));
        };

        const refreshDependentFields = (scope) => {
            const items = searchSelects.filter((item) => item.select.form === scope);
            const country = items.find((item) => item.select.dataset.geoRole === 'country');
            const state = items.find((item) => item.select.dataset.geoRole === 'state');
            const city = items.find((item) => item.select.dataset.geoRole === 'city');
            if (state) {
                state.options.forEach((option) => {
                    option.dataset.filtered = option.value && country?.select.value && option.dataset.countryId !== country.select.value ? '1' : '0';
                });
                if (state.select.value && state.select.selectedOptions[0]?.dataset.filtered === '1') clearSearchSelect(state);
            }
            if (city) {
                city.options.forEach((option) => {
                    const countryMismatch = option.value && country?.select.value && option.dataset.countryId !== country.select.value;
                    const stateMismatch = option.value && state?.select.value && option.dataset.stateId !== state.select.value;
                    option.dataset.filtered = countryMismatch || stateMismatch ? '1' : '0';
                });
                if (city.select.value && city.select.selectedOptions[0]?.dataset.filtered === '1') clearSearchSelect(city);
            }
            const parentCategory = items.find((item) => item.select.dataset.categoryRole === 'parent');
            const childCategory = items.find((item) => item.select.dataset.categoryRole === 'child');
            if (childCategory) {
                childCategory.options.forEach((option) => {
                    option.dataset.filtered = option.value && parentCategory?.select.value && option.dataset.parentId !== parentCategory.select.value ? '1' : '0';
                });
                if (childCategory.select.value && childCategory.select.selectedOptions[0]?.dataset.filtered === '1') clearSearchSelect(childCategory);
            }
        };

        searchSelects.forEach((item) => {
            refreshDependentFields(item.select.form);
            item.input.addEventListener('focus', () => {
                closeSearchSelects(item);
                renderSearchSelect(item);
            });
            item.input.addEventListener('input', () => {
                closeSearchSelects(item);
                renderSearchSelect(item);
            });
            item.list.addEventListener('click', (event) => {
                const button = event.target.closest('[data-value]');
                if (! button) return;
                chooseSearchOption(item, button.dataset.value);
            });
            item.select.addEventListener('change', () => {
                const selected = item.select.selectedOptions[0];
                item.input.value = selected?.value ? selected.textContent.trim() : '';
                refreshDependentFields(item.select.form);
            });
        });

        document.addEventListener('click', (event) => {
            if (! event.target.closest('[data-search-select]')) closeSearchSelects();
        });
    </script>
    @stack('scripts')
</body>
</html>
