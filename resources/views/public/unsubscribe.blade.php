<x-layouts.base title="Unsubscribed | Sirraty">
    <main style="min-height:100vh;display:grid;place-items:center;padding:24px;background:#f7f4ef;color:#17221c">
        <section style="max-width:520px;text-align:center">
            <x-brand-logo style="font-size:64px" />
            <h1>Unsubscribed</h1>
            <p>{{ $email }} will not receive Sirraty recovery campaign emails again.</p>
            <a href="{{ route('home') }}">Return to Sirraty</a>
        </section>
    </main>
</x-layouts.base>
