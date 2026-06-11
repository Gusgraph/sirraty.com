{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/welcome.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.base title="Sirraty | Halal Social">
    @push('styles')
        <style>
            .home { position: relative; min-height: 100vh; overflow: hidden; display: grid; align-items: center; background: url("https://res.cloudinary.com/duja2smra/image/upload/BGImage_Jun_10_2026_08_51_19_PM_u7erfm.webp") center / cover no-repeat fixed; }
            .home::before { content: ""; position: absolute; inset: 0; background: linear-gradient(73deg, color-mix(in srgb, var(--bg) 91%, transparent), color-mix(in srgb, var(--bg) 73%, transparent) 57%, color-mix(in srgb, var(--bg) 83%, transparent)), radial-gradient(circle at 17% 21%, rgba(36,117,83,.19), transparent 31rem), radial-gradient(circle at 83% 79%, rgba(179,139,49,.23), transparent 37rem); }
            .hero { position: relative; z-index: 2; display: grid; grid-template-columns: minmax(0, 1fr) 351px; gap: 73px; align-items: center; padding: 73px 0; }
            .hero h1 { margin: 0; font-size: clamp(4.1rem, 13vw, 11rem); line-height: .87; letter-spacing: 0; }
            .hero p { margin: 19px 0 0; font-size: clamp(1.3rem, 3vw, 2.1rem); color: var(--muted); }
            .actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 27px; }
            .access { display: grid; gap: 11px; }
            .home-modal { position: fixed; inset: 0; z-index: 37; display: none; place-items: center; padding: 19px; background: color-mix(in srgb, var(--text) 37%, transparent); backdrop-filter: blur(11px); }
            .home-modal[aria-hidden="false"] { display: grid; }
            .modal-window { width: min(100%, 451px); max-height: calc(100vh - 38px); overflow: auto; background: var(--panel); border: 1px solid var(--line); border-radius: 7px; padding: 27px; box-shadow: 0 31px 73px rgba(0, 0, 0, .27); }
            .modal-head { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 19px; }
            .modal-head h2 { margin: 0; }
            .icon-btn { width: 39px; height: 39px; display: grid; place-items: center; border: 1px solid var(--line); border-radius: 7px; background: var(--bg); color: var(--text); cursor: pointer; }
            .auth-form { display: grid; gap: 15px; }
            .bird-layer { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
            .bird { position: absolute; width: 11px; height: 7px; color: color-mix(in srgb, var(--gold) 73%, transparent); transform: translate(-50%, -50%); }
            .bird::before, .bird::after { content: ""; position: absolute; width: 9px; height: 3px; border-top: 2px solid currentColor; border-radius: 50%; transform-origin: right center; }
            .bird::after { right: 0; transform-origin: left center; }
            @media (max-width: 830px) { .hero { grid-template-columns: 1fr; gap: 31px; padding: 51px 0; } }
        </style>
    @endpush
    <main class="home">
        <div class="bird-layer" aria-hidden="true"></div>
        <div class="wrap hero">
            <section>
                <div class="brand" style="margin-bottom:19px"><span class="brand-mark"><i class="fa-solid fa-mosque"></i></span> Sirraty</div>
                <h1>Sirraty</h1>
                <p>Halal Social</p>
                <div class="actions">
                    <button class="btn primary" type="button" data-open-auth="signup"><i class="fa-solid fa-user-plus"></i> Signup</button>
                    <button class="btn" type="button" data-open-auth="signin"><i class="fa-solid fa-right-to-bracket"></i> Sign in</button>
                </div>
            </section>
            <aside class="panel access">
                <h2 class="section-title">Enter Sirraty</h2>
                <p class="muted">Private by default, guided by clear controls.</p>
                <button class="btn primary" type="button" data-open-auth="signup">Create account</button>
                <button class="btn" type="button" data-open-auth="signin">I already have an account</button>
                <div class="row">
                    <label class="theme-toggle"><input type="radio" name="theme" value="light"><span><i class="fa-regular fa-sun"></i></span></label>
                    <label class="theme-toggle"><input type="radio" name="theme" value="dark"><span><i class="fa-regular fa-moon"></i></span></label>
                </div>
            </aside>
        </div>
        <div class="home-modal" id="signin-modal" aria-hidden="{{ ($authModal ?? null) === 'signin' ? 'false' : 'true' }}" role="dialog" aria-modal="true" aria-labelledby="signin-title">
            <form class="modal-window auth-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="modal-head">
                    <h2 id="signin-title">Sign in</h2>
                    <button class="icon-btn" type="button" data-close-auth aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <label class="field">Email <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
                <label class="field">Password <input type="password" name="password" required autocomplete="current-password"></label>
                <label class="row"><input type="checkbox" name="remember" value="1"> Remember me</label>
                @if($errors->any() && ($authModal ?? null) === 'signin')<p class="muted">{{ $errors->first() }}</p>@endif
                <div class="row"><button class="btn primary" type="submit">Sign in</button><a class="btn link" href="{{ route('password.request') }}">Password help</a></div>
            </form>
        </div>
        <div class="home-modal" id="signup-modal" aria-hidden="{{ ($authModal ?? null) === 'signup' ? 'false' : 'true' }}" role="dialog" aria-modal="true" aria-labelledby="signup-title">
            <form class="modal-window auth-form" method="POST" action="{{ route('register.store') }}">
                @csrf
                <div class="modal-head">
                    <h2 id="signup-title">Signup</h2>
                    <button class="icon-btn" type="button" data-close-auth aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <label class="field">Name <input name="name" value="{{ old('name') }}" required maxlength="73" autocomplete="name"></label>
                <label class="field">Username <input name="username" value="{{ old('username') }}" required maxlength="73" autocomplete="username"></label>
                <label class="field">Email <input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
                <label class="field">Password <input type="password" name="password" required autocomplete="new-password"></label>
                <label class="field">Confirm password <input type="password" name="password_confirmation" required autocomplete="new-password"></label>
                @if($errors->any() && ($authModal ?? null) === 'signup')<p class="muted">{{ $errors->first() }}</p>@endif
                <button class="btn primary" type="submit">Create account</button>
            </form>
        </div>
    </main>
    @push('scripts')
        <script>
            const layer = document.querySelector('.bird-layer');
            const flock = Array.from({ length: 27 }, (_, i) => {
                const el = document.createElement('i');
                el.className = 'bird';
                layer.appendChild(el);
                return { el, x: 73 + i * 19, y: 117 + Math.sin(i) * 73, vx: 1.1 + (i % 5) * .07, vy: Math.cos(i) * .19 };
            });
            const mouse = { x: innerWidth / 2, y: innerHeight / 2 };
            addEventListener('pointermove', (event) => { mouse.x = event.clientX; mouse.y = event.clientY; });
            function fly() {
                flock.forEach((bird, index) => {
                    const dx = bird.x - mouse.x;
                    const dy = bird.y - mouse.y;
                    const distance = Math.max(37, Math.hypot(dx, dy));
                    bird.vx += (dx / distance) * .017 + (flock[(index + 1) % flock.length].x - bird.x) * .0007;
                    bird.vy += (dy / distance) * .017 + (flock[(index + 1) % flock.length].y - bird.y) * .0007;
                    bird.vx = Math.max(-2.7, Math.min(2.7, bird.vx));
                    bird.vy = Math.max(-1.9, Math.min(1.9, bird.vy));
                    bird.x = (bird.x + bird.vx + innerWidth + 27) % (innerWidth + 27);
                    bird.y = Math.max(37, Math.min(innerHeight - 37, bird.y + bird.vy));
                    bird.el.style.transform = `translate(${bird.x}px, ${bird.y}px) rotate(${bird.vx * 11}deg)`;
                });
                requestAnimationFrame(fly);
            }
            fly();

            const modals = {
                signin: document.getElementById('signin-modal'),
                signup: document.getElementById('signup-modal'),
            };
            const setModal = (name) => {
                Object.entries(modals).forEach(([key, modal]) => modal?.setAttribute('aria-hidden', key === name ? 'false' : 'true'));
                document.body.style.overflow = name ? 'hidden' : '';
                if (name) {
                    modals[name]?.querySelector('input')?.focus();
                    history.replaceState(null, '', name === 'signup' ? '{{ route('register') }}' : '{{ route('login') }}');
                } else {
                    history.replaceState(null, '', '{{ route('home') }}');
                }
            };
            document.querySelectorAll('[data-open-auth]').forEach((button) => {
                button.addEventListener('click', () => setModal(button.dataset.openAuth));
            });
            document.querySelectorAll('[data-close-auth]').forEach((button) => {
                button.addEventListener('click', () => setModal(null));
            });
            document.querySelectorAll('.home-modal').forEach((modal) => {
                modal.addEventListener('click', (event) => {
                    if (event.target === modal) setModal(null);
                });
            });
            addEventListener('keydown', (event) => {
                if (event.key === 'Escape') setModal(null);
            });
        </script>
    @endpush
</x-layouts.base>
