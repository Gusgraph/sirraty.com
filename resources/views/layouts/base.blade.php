{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/layouts/base.blade.php --}}
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
                            list.insertAdjacentHTML('beforeend', `<div class="comment-item"><a class="comment-avatar" href="${data.comment.user_url}">${avatar}</a><div class="comment-main"><div class="comment-meta-row"><div class="comment-identity"><a class="comment-author" href="${data.comment.user_url}">${escapeHtml(data.comment.user_name)}</a><a class="muted" href="${data.comment.user_url}">@${escapeHtml(data.comment.user_username)}</a><span class="muted">${escapeHtml(data.comment.created_at)}</span></div></div><p>${escapeHtml(data.comment.body)}</p></div></div>`);
                        }
                        if (count) count.innerHTML = `<i class="fa-regular fa-comment"></i> ${data.comments_count ?? '0'}`;
                        form.reset();
                    }
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
    </script>
    @stack('scripts')
</body>
</html>
