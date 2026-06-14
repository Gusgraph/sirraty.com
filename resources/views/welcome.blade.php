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
            .hero h1 { margin: 0; }
            .home .sirraty-text-logo { font-size: clamp(4.1rem, 13vw, 11rem); }
            .public-links { position: absolute; left: 27px; bottom: 19px; z-index: 3; display: flex; gap: 15px; flex-wrap: wrap; font-size: .89rem; color: color-mix(in srgb, var(--text) 73%, transparent); }
            .hero p { margin: 19px 0 0; font-size: clamp(1.3rem, 3vw, 2.1rem); color: var(--muted); }
            .actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 27px; }
            .access { display: grid; gap: 11px; }
            .home-modal { position: fixed; inset: 0; z-index: 37; display: none; place-items: center; padding: 19px; background: color-mix(in srgb, var(--text) 37%, transparent); backdrop-filter: blur(11px); }
            .home-modal[aria-hidden="false"] { display: grid; }
            .modal-window { width: min(100%, 451px); max-height: calc(100vh - 38px); overflow: auto; background: var(--panel); border: 1px solid var(--line); border-radius: 7px; padding: 27px; box-shadow: 0 31px 73px rgba(0, 0, 0, .27); }
            .modal-head { display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 19px; }
            .modal-head h2 { margin: 0; }
            .auth-error { margin: 0; padding: 11px 13px; border: 1px solid color-mix(in srgb, #b3261e 57%, var(--line)); border-radius: 7px; background: color-mix(in srgb, #b3261e 11%, var(--panel)); color: color-mix(in srgb, #b3261e 83%, var(--text)); }
            .icon-btn { width: 39px; height: 39px; display: grid; place-items: center; border: 1px solid var(--line); border-radius: 7px; background: var(--bg); color: var(--text); cursor: pointer; }
            .auth-form { display: grid; gap: 15px; }
            .bird-layer { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
            .bird { position: absolute; width: 29px; height: 19px; color: color-mix(in srgb, var(--gold) 73%, transparent); transform: translate(-50%, -50%); opacity: .83; }
            .bird svg { width: 100%; height: 100%; overflow: visible; filter: drop-shadow(0 1px 1px rgba(0, 0, 0, .17)); }
            .bird .wing-top { transform-origin: 21px 15px; animation: wingbeatTop .57s ease-in-out infinite alternate; }
            .bird .wing-bottom { transform-origin: 21px 15px; animation: wingbeatBottom .57s ease-in-out infinite alternate; }
            @keyframes wingbeatTop { from { transform: rotate(-3deg) translateY(1px); } to { transform: rotate(-17deg) translateY(-3px); } }
            @keyframes wingbeatBottom { from { transform: rotate(3deg) translateY(-1px); } to { transform: rotate(13deg) translateY(3px); } }
            @media (max-width: 830px) { .hero { grid-template-columns: 1fr; gap: 31px; padding: 51px 0; } }
        </style>
    @endpush
    <main class="home">
        <div class="bird-layer" aria-hidden="true"></div>
        <div class="wrap hero">
            <section>
                <h1><x-brand-logo variant="text" /></h1>
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
        <nav class="public-links" aria-label="Public pages">
            <a href="{{ route('public.privacy') }}">Privacy</a>
            <a href="{{ route('public.terms') }}">Terms</a>
            <a href="{{ route('public.business') }}">Business</a>
        </nav>
        <div class="home-modal" id="signin-modal" aria-hidden="{{ ($authModal ?? null) === 'signin' ? 'false' : 'true' }}" role="dialog" aria-modal="true" aria-labelledby="signin-title">
            <form class="modal-window auth-form" method="POST" action="{{ route('login.store') }}">
                @csrf
                <div class="modal-head">
                    <h2 id="signin-title">Sign in</h2>
                    <button class="icon-btn" type="button" data-close-auth aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <label class="field">Username, email, or phone <input name="login" value="{{ old('login') }}" required autocomplete="username"></label>
                <label class="field">Password <span class="password-control"><input id="signin-password" type="password" name="password" required autocomplete="current-password"><button type="button" data-password-toggle="signin-password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></span></label>
                <label class="row"><input type="checkbox" name="remember" value="1"> Remember me</label>
                @if($errors->signin->any() && ($authModal ?? null) === 'signin')<p class="auth-error">{{ $errors->signin->first() }}</p>@endif
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
                <label class="field">Password <span class="password-control"><input id="signup-password" type="password" name="password" required autocomplete="new-password"><button type="button" data-password-toggle="signup-password" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></span></label>
                <label class="field">Confirm password <span class="password-control"><input id="signup-password-confirmation" type="password" name="password_confirmation" required autocomplete="new-password"><button type="button" data-password-toggle="signup-password-confirmation" aria-label="Show password"><i class="fa-regular fa-eye"></i></button></span></label>
                @if($errors->any() && ($authModal ?? null) === 'signup')<p class="muted">{{ $errors->first() }}</p>@endif
                <button class="btn primary" type="submit">Create account</button>
            </form>
        </div>
    </main>
    @push('scripts')
        <script>
            const layer = document.querySelector('.bird-layer');
            const flockSize = 33;
            const groups = 3;
            const mouse = { x: -9999, y: -9999, active: false };
            const targets = Array.from({ length: groups }, (_, group) => ({
                x: innerWidth * (.23 + group * .27),
                y: 117 + group * 73,
                drift: Math.random() * 7,
            }));
            const flock = Array.from({ length: flockSize }, (_, index) => {
                const el = document.createElement('i');
                const group = index % groups;
                el.className = 'bird';
                el.innerHTML = '<svg viewBox="0 0 43 29" aria-hidden="true"><path class="wing-top" d="M20 15 C14 6 6 3 1 4 C8 11 13 15 21 16" fill="currentColor"/><path class="wing-bottom" d="M20 16 C12 18 6 22 3 27 C12 25 18 21 23 17" fill="currentColor"/><path d="M14 16 C19 12 29 11 38 15 C31 16 27 18 21 19 C18 19 16 18 14 16Z" fill="currentColor"/><path d="M36 14 L42 12 L38 16Z" fill="currentColor"/><path d="M14 16 L5 13 L11 18Z" fill="currentColor"/><circle cx="33" cy="14" r=".7" fill="rgba(20, 20, 20, .41)"/></svg>';
                el.style.animationDelay = `${(index % 11) * .07}s`;
                layer.appendChild(el);
                return {
                    el,
                    group,
                    x: 73 + Math.random() * (innerWidth - 146),
                    y: 73 + Math.random() * Math.max(173, innerHeight * .51),
                    vx: 1.1 + Math.random() * 1.7,
                    vy: (Math.random() - .5) * 1.9,
                    turn: Math.random() * Math.PI * 2,
                    scale: .73 + Math.random() * .37,
                };
            });
            const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
            const steer = (bird, dx, dy, strength) => {
                const distance = Math.max(1, Math.hypot(dx, dy));
                bird.vx += (dx / distance) * strength;
                bird.vy += (dy / distance) * strength;
            };
            addEventListener('pointermove', (event) => {
                mouse.x = event.clientX;
                mouse.y = event.clientY;
                mouse.active = true;
            });
            addEventListener('pointerleave', () => { mouse.active = false; });
            function moveTargets(time) {
                targets.forEach((target, index) => {
                    target.x += Math.cos(time / (1900 + index * 317) + target.drift) * 1.9;
                    target.y += Math.sin(time / (1700 + index * 271) + target.drift) * 1.3;
                    if (Math.random() < .007) {
                        target.x = 73 + Math.random() * (innerWidth - 146);
                        target.y = 51 + Math.random() * Math.max(173, innerHeight * .57);
                    }
                    target.x = clamp(target.x, 37, innerWidth - 37);
                    target.y = clamp(target.y, 37, innerHeight - 37);
                });
            }
            function fly(time = 0) {
                moveTargets(time);
                const uniformPhase = (Math.sin(time / 3700) + 1) / 2;
                const uniformFlight = uniformPhase > .57 ? (uniformPhase - .57) / .43 : 0;
                flock.forEach((bird) => {
                    const target = targets[bird.group];
                    let separationX = 0;
                    let separationY = 0;
                    let alignmentX = 0;
                    let alignmentY = 0;
                    let cohesionX = 0;
                    let cohesionY = 0;
                    let near = 0;

                    flock.forEach((other) => {
                        if (other === bird || other.group !== bird.group) return;
                        const dx = other.x - bird.x;
                        const dy = other.y - bird.y;
                        const distance = Math.hypot(dx, dy);
                        if (distance < 117) {
                            near += 1;
                            alignmentX += other.vx;
                            alignmentY += other.vy;
                            cohesionX += other.x;
                            cohesionY += other.y;
                            if (distance < 57) {
                                separationX -= dx / Math.max(1, distance);
                                separationY -= dy / Math.max(1, distance);
                            }
                        }
                    });

                    if (near) {
                        steer(bird, alignmentX / near - bird.vx, alignmentY / near - bird.vy, .019 + uniformFlight * .057);
                        steer(bird, cohesionX / near - bird.x, cohesionY / near - bird.y, .0017 + uniformFlight * .0037);
                        steer(bird, separationX, separationY, .173 - uniformFlight * .073);
                    }

                    steer(bird, target.x - bird.x, target.y - bird.y, .0037 + uniformFlight * .0057);
                    bird.turn += .037 + Math.random() * .019;
                    bird.vx += Math.cos(bird.turn + bird.group) * (.047 - uniformFlight * .027) + (Math.random() - .5) * (.073 - uniformFlight * .051);
                    bird.vy += Math.sin(bird.turn * 1.3) * (.037 - uniformFlight * .019) + (Math.random() - .5) * (.057 - uniformFlight * .039);

                    if (mouse.active) {
                        const dx = bird.x - mouse.x;
                        const dy = bird.y - mouse.y;
                        const distance = Math.hypot(dx, dy);
                        if (distance < 173) {
                            steer(bird, dx, dy, (173 - distance) * .0037);
                            bird.vx += (Math.random() - .5) * .73;
                            bird.vy += (Math.random() - .5) * .73;
                        }
                    }

                    if (bird.x < -57) bird.x = innerWidth + 57;
                    if (bird.x > innerWidth + 57) bird.x = -57;
                    if (bird.y < -57) bird.y = innerHeight + 57;
                    if (bird.y > innerHeight + 57) bird.y = -57;

                    const speed = Math.hypot(bird.vx, bird.vy);
                    const maxSpeed = mouse.active ? 5.7 : 3.7;
                    if (speed > maxSpeed) {
                        bird.vx = (bird.vx / speed) * maxSpeed;
                        bird.vy = (bird.vy / speed) * maxSpeed;
                    }

                    bird.x += bird.vx;
                    bird.y += bird.vy;
                    const angle = Math.atan2(bird.vy, bird.vx) * 57.2958;
                    bird.el.style.transform = `translate(${bird.x}px, ${bird.y}px) rotate(${angle}deg) scale(${bird.scale})`;
                    bird.el.style.opacity = `${clamp(.57 + speed * .09, .57, .91)}`;
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
