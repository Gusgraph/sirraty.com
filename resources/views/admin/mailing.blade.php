{{-- أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم --}}
{{-- Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim --}}
{{-- version: 0.1.0 --}}
{{-- ====================================================== --}}
{{-- - Sirraty --}}
{{-- - Gusgraph --}}
{{-- - Author: Gus Kazem --}}
{{-- - https://Gusgraph.com --}}
{{-- - File Path: resources/views/admin/mailing.blade.php --}}
{{-- ===================================================== --}}
<x-layouts.app title="Mailing | Admin Zone">
    @php($selectedTemplate = $templates->firstWhere('enabled', true) ?? $templates->first())
    <div class="row" style="justify-content:space-between;margin-bottom:19px">
        <h1 class="section-title" style="margin:0">Mailing</h1>
        <div class="row" style="gap:7px">
            <a class="btn" href="{{ route('admin.mailing.recovery') }}">Recovery</a>
            <a class="btn" href="{{ route('admin.mailing.queue') }}">Queue</a>
            <a class="btn" href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>
    </div>

    <section class="panel" style="margin-bottom:19px">
        <h2 class="section-title" style="margin-bottom:11px">Sending Configuration</h2>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));gap:11px;margin-bottom:15px">
            @foreach($mailStatus as $label => $value)
                <div style="border-top:1px solid rgba(22,199,101,.27);padding-top:7px">
                    <small class="muted">{{ str_replace('_', ' ', ucfirst($label)) }}</small>
                    <strong style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $value ?: 'Not set' }}</strong>
                </div>
            @endforeach
        </div>
        <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(137px,1fr));gap:11px;margin-bottom:15px">
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Protection mode</small>
                <strong style="display:block">{{ ucfirst($protection['mode']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Transactional recovery</small>
                <strong style="display:block">{{ $protection['transactional_recovery'] ? 'Allowed' : 'Blocked' }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">SES events</small>
                <strong style="display:block">{{ number_format($sesFeedback['events']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Bounced users</small>
                <strong style="display:block">{{ number_format($sesFeedback['bounced']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Complained users</small>
                <strong style="display:block">{{ number_format($sesFeedback['complained']) }}</strong>
            </div>
            <div style="border-top:1px solid rgba(30,185,197,.27);padding-top:7px">
                <small class="muted">Suppressed users</small>
                <strong style="display:block">{{ number_format($sesFeedback['suppressed']) }}</strong>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.mailing.settings') }}" class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));gap:11px;align-items:end">
            @csrf
            @method('PATCH')
            <label>Mailing enabled
                <select name="mailing_enabled">
                    <option value="1" @selected($settings['enabled'] === '1')>Allow</option>
                    <option value="0" @selected($settings['enabled'] !== '1')>Do not allow</option>
                </select>
            </label>
            <label>Mail provider
                <select name="mail_provider">
                    @foreach($mailProviders as $providerKey => $provider)
                        <option value="{{ $providerKey }}" @selected($selectedProvider === $providerKey)>{{ $provider['label'] }}{{ $provider['available'] ? '' : ' (not configured)' }}</option>
                    @endforeach
                </select>
            </label>
            <label>Protection mode
                <select name="protection_mode">
                    <option value="strict" @selected($settings['protection_mode'] === 'strict')>Strict block</option>
                    <option value="monitor" @selected($settings['protection_mode'] === 'monitor')>Monitor</option>
                </select>
            </label>
            <label>Transactional recovery
                <select name="allow_transactional_recovery">
                    <option value="1" @selected($settings['allow_transactional_recovery'] === '1')>Allow</option>
                    <option value="0" @selected($settings['allow_transactional_recovery'] !== '1')>Block</option>
                </select>
            </label>
            <label>Block free bulk domains
                <select name="block_free_bulk_domains">
                    <option value="0" @selected($settings['block_free_bulk_domains'] !== '1')>Do not block</option>
                    <option value="1" @selected($settings['block_free_bulk_domains'] === '1')>Block</option>
                </select>
            </label>
            <label>Reply to
                <input name="reply_to" type="email" value="{{ $settings['reply_to'] }}" autocomplete="off">
            </label>
            <label>Max users in this campaign
                <input name="max_recipients" type="number" min="1" max="250000" value="{{ $settings['max_recipients'] }}" autocomplete="off">
            </label>
            <label>Emails per 5 minutes
                <input name="emails_per_3_minutes" type="number" min="1" max="997" value="{{ $settings['emails_per_3_minutes'] }}" autocomplete="off">
            </label>
            <label style="grid-column:1/-1">Email footer text
                <input name="footer" value="{{ $settings['footer'] }}" autocomplete="off">
            </label>
            <div style="grid-column:1/-1;border-top:1px solid rgba(22,199,101,.19);padding-top:9px;font-size:.83rem" class="muted">
                Active provider: {{ $mailProviders[$selectedProvider]['label'] ?? 'Laravel default' }} via {{ $mailStatus['provider_mailer'] }}. Strict protection blocks suppressed, bounced, complained, test, invalid, and disposable recipients before sending.
            </div>
            <button class="btn" type="submit">Save</button>
        </form>
    </section>

    <section class="panel" style="margin-bottom:19px">
        <div class="grid" style="grid-template-columns:minmax(0,1fr) minmax(213px,273px);gap:19px;align-items:start">
            <div>
                <h2 class="section-title" style="margin-bottom:11px">Template Editor</h2>
                <form id="template-editor-form" method="POST" action="{{ $selectedTemplate ? route('admin.mailing.templates.update', $selectedTemplate) : route('admin.mailing.templates.store') }}">
                    @csrf
                    <input id="template-method" type="hidden" name="_method" value="{{ $selectedTemplate ? 'PATCH' : '' }}" @if(! $selectedTemplate) disabled @endif>
                    <label>Key
                        <input id="template-key" name="key" value="{{ $selectedTemplate?->key }}" required autocomplete="off">
                    </label>
                    <label>Subject
                        <input id="template-subject" name="subject" value="{{ $selectedTemplate?->subject }}" required autocomplete="off">
                    </label>
                    <label>HTML body
                        <input id="template-body" type="hidden" name="body" value="{{ $selectedTemplate?->body }}">
                        <div id="template-html-editor" contenteditable="true" style="min-height:273px;margin-top:7px;padding:15px;border:1px solid rgba(22,199,101,.37);border-radius:7px;background:rgba(255,255,255,.07);outline:none;overflow:auto">{!! $selectedTemplate?->body !!}</div>
                    </label>
                    <div class="row" style="gap:11px;justify-content:space-between;margin-top:11px">
                        <label style="display:flex;gap:7px;align-items:center;margin:0">
                            <input id="template-enabled" type="checkbox" name="enabled" value="1" @checked($selectedTemplate?->enabled ?? true)> Enabled
                        </label>
                        <div class="row" style="gap:7px">
                            <button class="btn" id="new-template-button" type="button">New</button>
                            <button class="btn" type="submit">Save Template</button>
                        </div>
                    </div>
                </form>
            </div>
            <aside>
                <h2 class="section-title" style="margin-bottom:7px">Saved Templates</h2>
                <div style="display:grid;gap:5px;max-height:427px;overflow:auto">
                    @foreach($templates as $template)
                        <button type="button" class="mail-template-row" data-id="{{ $template->id }}" data-key="{{ $template->key }}" data-subject="{{ $template->subject }}" data-body-template="mail-template-body-{{ $template->id }}" data-enabled="{{ $template->enabled ? '1' : '0' }}" data-update-url="{{ route('admin.mailing.templates.update', $template) }}" style="text-align:left;padding:7px 9px;border:0;border-top:1px solid rgba(22,199,101,.19);background:transparent;color:inherit;cursor:pointer">
                            <span style="display:grid;grid-template-columns:minmax(0,1fr) auto;gap:7px;align-items:center">
                                <span>
                                    <strong style="display:block;font-size:.83rem;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ str_replace('_', ' ', $template->key) }}</strong>
                                    <span class="muted" style="font-size:.73rem">{{ $template->enabled ? 'Enabled' : 'Disabled' }}</span>
                                </span>
                                <span class="mail-template-view" role="button" tabindex="0" style="font-size:.73rem;padding:3px 7px;border:1px solid rgba(22,199,101,.37);border-radius:7px;color:inherit">View</span>
                            </span>
                        </button>
                        <template id="mail-template-body-{{ $template->id }}">{!! $template->body !!}</template>
                    @endforeach
                </div>
            </aside>
        </div>
    </section>

    <section class="panel" style="margin-bottom:19px">
        <h2 class="section-title" style="margin-bottom:11px">Send Campaign</h2>
        <form method="POST" action="{{ route('admin.mailing.send') }}" class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));gap:11px;align-items:end">
            @csrf
            <input class="selected-template-id" type="hidden" name="template_id" value="{{ $selectedTemplate?->id }}">
            <label>Campaign name
                <input name="name" required value="{{ old('name') }}" autocomplete="off">
            </label>
            <label>Audience
                <select name="audience_type">
                    <option value="unverified">Incomplete signup</option>
                    <option value="all">All users</option>
                    <option value="role">By role</option>
                    <option value="status">By status</option>
                    <option value="country">By country</option>
                </select>
            </label>
            <label>Role
                <select name="role">
                    @foreach(['member','moderator','admin','owner'] as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </label>
            <label>Status
                <select name="status">
                    @foreach(['active','limited','suspended','banned'] as $status)
                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </label>
            <label>Country
                <select name="country_id">
                    <option value="">All countries</option>
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
            </label>
            <button class="btn" type="submit">Queue Send</button>
        </form>
    </section>

    <section class="panel" style="margin-bottom:19px">
        <h2 class="section-title" style="margin-bottom:11px">Test Email</h2>
        <form method="POST" action="{{ route('admin.mailing.test') }}" class="grid" style="grid-template-columns:repeat(auto-fit,minmax(173px,1fr));gap:11px;align-items:end">
            @csrf
            <input class="selected-template-id" type="hidden" name="template_id" value="{{ $selectedTemplate?->id }}">
            <label>Send to
                <input name="test_email" type="email" value="{{ auth()->user()?->email }}" required autocomplete="off">
            </label>
            <button class="btn" type="submit">Send Selected Template</button>
        </form>
    </section>

    <section class="panel">
        <div class="row" style="justify-content:space-between;margin-bottom:11px">
            <h2 class="section-title" style="margin:0">Recent Campaigns</h2>
            <a class="btn" href="{{ route('admin.mailing.queue') }}">Open Queue</a>
        </div>
        <div style="overflow:auto">
            <table style="width:100%;border-collapse:collapse;font-size:.83rem">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Name</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Status</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Recipients</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Sent</th>
                        <th style="text-align:left;padding:5px;border-bottom:1px solid rgba(22,199,101,.27)">Failed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                        <tr>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)"><a href="{{ route('admin.mailing.queue', $campaign) }}">{{ $campaign->name }}</a></td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ str_replace('_', ' ', $campaign->status) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->recipient_count) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->sent_count) }}</td>
                            <td style="padding:5px;border-bottom:1px solid rgba(22,199,101,.13)">{{ number_format($campaign->failed_count) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="muted" style="padding:11px">No campaigns yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <div id="template-preview-modal" aria-hidden="true" style="position:fixed;inset:0;z-index:1000;display:none;align-items:center;justify-content:center;padding:19px;background:rgba(0,0,0,.57)">
        <section style="width:min(873px,100%);max-height:91vh;overflow:hidden;border:1px solid rgba(22,199,101,.57);border-radius:11px;background:#f7f4ef;color:#17221c;box-shadow:0 19px 73px rgba(0,0,0,.37)">
            <div class="row" style="justify-content:space-between;gap:11px;padding:13px 17px;border-bottom:1px solid rgba(22,199,101,.27)">
                <div>
                    <strong id="template-preview-title" style="display:block">Email Preview</strong>
                    <span id="template-preview-subject" class="muted" style="font-size:.79rem"></span>
                </div>
                <button id="template-preview-close" class="btn" type="button" style="padding:7px 11px">Close</button>
            </div>
            <div style="max-height:calc(91vh - 73px);overflow:auto;padding:19px;background:linear-gradient(rgba(247,244,239,.19),rgba(247,244,239,.27)),url('https://res.cloudinary.com/duja2smra/image/upload/emails-Sirr4857_s2ppm3.webp') center/cover no-repeat #f7f4ef">
                <div style="max-width:573px;margin:0 auto">
                    <div style="padding:0 19px 19px"><x-brand-logo style="font-size:51px" /></div>
                    <article style="background:#fffdf7;border:1px solid #d9d1c3;border-radius:7px;overflow:hidden;box-shadow:0 11px 37px rgba(23,34,28,.09)">
                        <div style="height:73px;background:linear-gradient(117deg,rgba(36,117,83,.93),rgba(30,185,197,.53)),repeating-linear-gradient(137deg,rgba(255,255,255,.23) 0 1px,transparent 1px 19px)"></div>
                        <div style="padding:31px 27px">
                            <h1 id="template-preview-heading" style="margin:0 0 15px;font-size:27px;line-height:1.17;color:#17221c"></h1>
                            <div id="template-preview-body" style="font-size:15px;line-height:1.57;color:#435047"></div>
                        </div>
                    </article>
                    <div style="padding:19px;color:#647067;font-size:13px;text-align:center">{{ $settings['footer'] ?: 'Sirraty · Halal Social' }}<br><a href="{{ route('public.privacy') }}">Privacy</a> · <a href="{{ route('public.terms') }}">Terms</a> · <a href="{{ route('public.business') }}">Business</a></div>
                </div>
            </div>
        </section>
    </div>

    <script>
        (() => {
            const editorForm = document.getElementById('template-editor-form');
            const method = document.getElementById('template-method');
            const key = document.getElementById('template-key');
            const subject = document.getElementById('template-subject');
            const body = document.getElementById('template-body');
            const htmlEditor = document.getElementById('template-html-editor');
            const enabled = document.getElementById('template-enabled');
            const templateIds = document.querySelectorAll('.selected-template-id');
            const storeUrl = @json(route('admin.mailing.templates.store'));
            const previewModal = document.getElementById('template-preview-modal');
            const previewTitle = document.getElementById('template-preview-title');
            const previewSubject = document.getElementById('template-preview-subject');
            const previewHeading = document.getElementById('template-preview-heading');
            const previewBody = document.getElementById('template-preview-body');
            const getTemplateBody = (button) => {
                const template = document.getElementById(button.dataset.bodyTemplate);
                const holder = document.createElement('div');
                if (template?.content) {
                    holder.appendChild(template.content.cloneNode(true));
                } else {
                    holder.innerHTML = template?.innerHTML || '';
                }

                return holder.innerHTML.trim();
            };
            const textToHtml = (value) => (value || '').split(/\r?\n/).map((line) => line.trim()).filter(Boolean).map((line) => `<p style="margin:0 0 19px">${escapeHtml(line).replace(/(https?:\/\/[^\s<]+)/g, '<a href="$1">$1</a>')}</p>`).join('');
            const renderTemplateBody = (value) => /<\/?[a-z][\s\S]*>/i.test(value) ? value : textToHtml(value);
            const escapeHtml = (value) => {
                const holder = document.createElement('div');
                holder.textContent = value || '';
                return holder.innerHTML;
            };

            htmlEditor?.addEventListener('input', () => body.value = htmlEditor.innerHTML);
            editorForm?.addEventListener('submit', () => body.value = htmlEditor.innerHTML);

            document.querySelectorAll('.mail-template-row').forEach((button) => {
                button.addEventListener('click', () => {
                    editorForm.action = button.dataset.updateUrl;
                    method.disabled = false;
                    method.value = 'PATCH';
                    key.value = button.dataset.key || '';
                    subject.value = button.dataset.subject || '';
                    htmlEditor.innerHTML = getTemplateBody(button);
                    body.value = getTemplateBody(button);
                    enabled.checked = button.dataset.enabled === '1';
                    templateIds.forEach((input) => input.value = button.dataset.id);
                    document.querySelectorAll('.mail-template-row').forEach((row) => row.style.background = 'transparent');
                    button.style.background = 'rgba(22,199,101,.07)';
                });

                button.querySelector('.mail-template-view')?.addEventListener('click', (event) => {
                    event.stopPropagation();
                    previewTitle.textContent = (button.dataset.key || 'Email Preview').replaceAll('_', ' ');
                    previewSubject.textContent = button.dataset.subject || '';
                    previewHeading.textContent = button.dataset.subject || '';
                    previewBody.innerHTML = renderTemplateBody(getTemplateBody(button));
                    previewModal.style.display = 'flex';
                    previewModal.setAttribute('aria-hidden', 'false');
                });
            });

            const closePreview = () => {
                previewModal.style.display = 'none';
                previewModal.setAttribute('aria-hidden', 'true');
            };
            document.getElementById('template-preview-close')?.addEventListener('click', closePreview);
            previewModal?.addEventListener('click', (event) => {
                if (event.target === previewModal) closePreview();
            });
            window.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && previewModal?.style.display === 'flex') closePreview();
            });

            document.getElementById('new-template-button')?.addEventListener('click', () => {
                editorForm.action = storeUrl;
                method.disabled = true;
                method.value = '';
                key.value = '';
                subject.value = '';
                htmlEditor.innerHTML = '';
                body.value = '';
                enabled.checked = true;
            });
        })();
    </script>
</x-layouts.app>
