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
            .home { position: relative; min-height: 100vh; overflow: hidden; display: grid; align-items: center; }
            .home::before { content: ""; position: absolute; inset: 0; background: radial-gradient(circle at 17% 21%, rgba(36,117,83,.19), transparent 31rem), radial-gradient(circle at 83% 79%, rgba(179,139,49,.23), transparent 37rem); }
            .hero { position: relative; z-index: 2; display: grid; grid-template-columns: minmax(0, 1fr) 351px; gap: 73px; align-items: center; padding: 73px 0; }
            .hero h1 { margin: 0; font-size: clamp(4.1rem, 13vw, 11rem); line-height: .87; letter-spacing: 0; }
            .hero p { margin: 19px 0 0; font-size: clamp(1.3rem, 3vw, 2.1rem); color: var(--muted); }
            .actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 27px; }
            .access { display: grid; gap: 11px; }
            .bird-layer { position: absolute; inset: 0; z-index: 1; pointer-events: none; }
            .bird { position: absolute; width: 11px; height: 7px; color: color-mix(in srgb, var(--brand) 63%, transparent); transform: translate(-50%, -50%); }
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
                    <a class="btn primary" href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i> Signup</a>
                    <a class="btn" href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i> Sign in</a>
                </div>
            </section>
            <aside class="panel access">
                <h2 class="section-title">Enter Sirraty</h2>
                <p class="muted">Private by default, guided by clear controls.</p>
                <a class="btn primary" href="{{ route('register') }}">Create account</a>
                <a class="btn" href="{{ route('login') }}">I already have an account</a>
                <div class="row">
                    <label class="theme-toggle"><input type="radio" name="theme" value="light"><span><i class="fa-regular fa-sun"></i></span></label>
                    <label class="theme-toggle"><input type="radio" name="theme" value="dark"><span><i class="fa-regular fa-moon"></i></span></label>
                </div>
            </aside>
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
        </script>
    @endpush
</x-layouts.base>
