<div class="fade-in">

    {{-- Google APIs --}}
    <script src="https://apis.google.com/js/api.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>

    <!-- Breadcrumb -->
    <div style="font-size: 0.875rem; margin-bottom: 1rem; color: var(--text-muted);">
        <a href="{{ route('mahasiswa.tugas.index') ?? '#' }}" style="color: var(--teal); text-decoration: none;">Tugas</a> &rsaquo; {{ $tugas->judul }}
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
        <!-- Task Description (LEFT) -->
        <div style="grid-column: span 2;">
            <div class="card" style="padding: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <span class="badge badge-teal">{{ $tugas->kelas->mataKuliah->nama ?? '' }}</span>
                </div>
                <h1 style="font-size: 1.5rem; font-weight: bold; margin-bottom: 1rem; color: var(--text-primary);">{{ $tugas->judul }}</h1>

                <div style="display: flex; align-items: center; font-size: 0.875rem; margin-bottom: 1.5rem; padding: 1rem; border-radius: 0.375rem; background-color: var(--input-bg); border: 1px solid var(--border);">
                    <div style="flex: 1;">
                        <p style="color: var(--text-muted); margin: 0;">Batas Waktu</p>
                        <p style="font-weight: 600; margin: 0; color: var(--text-primary);">{{ \Carbon\Carbon::parse($tugas->deadline)->format('d M Y, H:i') }}</p>
                    </div>
                    @if($tugas->file_soal)
                    <div>
                        <a href="{{ Storage::url($tugas->file_soal) }}" target="_blank" class="btn btn-outline btn-sm">Download Soal</a>
                    </div>
                    @endif
                </div>

                <div style="color: var(--text-secondary); line-height: 1.6;">
                    {!! $tugas->deskripsi !!}
                </div>
            </div>
        </div>

        <!-- Submission Form (RIGHT) -->
        <div>
            <div class="card" style="padding: 1.5rem;">
                <h2 style="font-size: 1.25rem; font-weight: bold; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border); color: var(--text-primary);">Status Pengumpulan</h2>

                @if($showSuccess)
                    <div style="margin-bottom: 1rem; padding: 0.75rem; border-radius: 0.375rem; font-size: 0.875rem; background-color: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid var(--success);">
                        ✅ Tugas berhasil dikumpulkan!
                    </div>
                @endif

                @php
                    $isPassed = \Carbon\Carbon::parse($tugas->deadline)->isPast();
                @endphp

                @if($pengumpulan)
                    <div style="margin-bottom: 1rem;">
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">Status</p>
                        @if($pengumpulan->status === 'dinilai')
                            <span class="badge badge-green">Dinilai ({{ $pengumpulan->nilai }}/100)</span>
                        @else
                            <span class="badge badge-teal">Dikumpulkan</span>
                        @endif
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">Waktu Pengumpulan</p>
                        <p style="font-weight: 500; margin: 0; color: var(--text-primary);">{{ \Carbon\Carbon::parse($pengumpulan->dikumpulkan_at)->format('d M Y, H:i') }}</p>
                    </div>

                    {{-- Tampilkan file Google Drive jika ada --}}
                    @if($pengumpulan->gdrive_file_id)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.5rem 0;">File Google Drive</p>
                            <a href="https://drive.google.com/file/d/{{ $pengumpulan->gdrive_file_id }}/view" target="_blank"
                               style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border-radius:0.5rem; background:#4285f4; color:white; text-decoration:none; font-size:0.875rem; font-weight:600;">
                                <svg width="16" height="16" viewBox="0 0 87.3 78" xmlns="http://www.w3.org/2000/svg"><path d="m6.6 66.85 3.85 6.65c.8 1.4 1.95 2.5 3.3 3.3l13.75-23.8h-27.5c0 1.55.4 3.1 1.2 4.5z" fill="#0066da"/><path d="m43.65 25-13.75-23.8c-1.35.8-2.5 1.9-3.3 3.3l-25.4 44a9.06 9.06 0 0 0 -1.2 4.5h27.5z" fill="#00ac47"/><path d="m73.55 76.8c1.35-.8 2.5-1.9 3.3-3.3l1.6-2.75 7.65-13.25c.8-1.4 1.2-2.95 1.2-4.5h-27.502l5.852 11.5z" fill="#ea4335"/><path d="m43.65 25 13.75-23.8c-1.35-.8-2.9-1.2-4.5-1.2h-18.5c-1.6 0-3.15.45-4.5 1.2z" fill="#00832d"/><path d="m59.8 53h-32.3l-13.75 23.8c1.35.8 2.9 1.2 4.5 1.2h50.8c1.6 0 3.15-.45 4.5-1.2z" fill="#2684fc"/><path d="m73.4 26.5-12.7-22c-.8-1.4-1.95-2.5-3.3-3.3l-13.75 23.8 16.15 27h27.45c0-1.55-.4-3.1-1.2-4.5z" fill="#ffba00"/></svg>
                                {{ $pengumpulan->gdrive_file_name ?? 'Buka di Google Drive' }}
                            </a>
                        </div>
                    @elseif($pengumpulan->file_path)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">File Terlampir</p>
                            <a href="{{ Storage::url($pengumpulan->file_path) }}" target="_blank" style="font-size: 0.875rem; color: var(--teal); text-decoration: none;">Lihat File</a>
                        </div>
                    @endif

                    @if($pengumpulan->link_url && !$pengumpulan->gdrive_file_id)
                        <div style="margin-bottom: 1rem;">
                            <p style="font-size: 0.875rem; color: var(--text-muted); margin: 0 0 0.25rem 0;">Link Tautan</p>
                            <a href="{{ $pengumpulan->link_url }}" target="_blank" style="font-size: 0.875rem; color: var(--teal); text-decoration: none; word-break: break-all;">{{ $pengumpulan->link_url }}</a>
                        </div>
                    @endif

                    @if($pengumpulan->feedback)
                        <div style="margin-top: 1.5rem; padding: 1rem; border-radius: 0.375rem; background-color: var(--teal-light); border: 1px solid var(--border-teal);">
                            <p style="font-size: 0.875rem; font-weight: 600; margin: 0 0 0.25rem 0; color: var(--teal-dark);">Feedback Dosen:</p>
                            <p style="font-size: 0.875rem; margin: 0; color: var(--teal-dark);">{{ $pengumpulan->feedback }}</p>
                        </div>
                    @endif

                    @if($pengumpulan->status !== 'dinilai' && !$isPassed)
                        <hr style="margin: 1.5rem 0; border: none; border-top: 1px solid var(--border);">
                        <p style="font-size: 0.875rem; margin-bottom: 0.75rem; font-weight: 600; color: var(--text-primary);">Update Pengumpulan</p>
                    @endif
                @endif

                @if(!$pengumpulan && $isPassed)
                    <div style="padding: 1rem; border-radius: 0.375rem; text-align: center; background-color: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger);">
                        <p style="font-weight: 600; margin: 0; color: var(--danger);">Batas Waktu Telah Lewat</p>
                        <p style="font-size: 0.875rem; margin: 0.25rem 0 0 0; color: var(--danger);">Anda tidak dapat lagi mengumpulkan tugas ini.</p>
                    </div>
                @elseif(!$pengumpulan || ($pengumpulan && $pengumpulan->status !== 'dinilai' && !$isPassed))
                    <form wire:submit.prevent="kumpulkan" style="display: flex; flex-direction: column; gap: 1rem;">
                        @error('general') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror

                        {{-- ====== GOOGLE DRIVE PICKER ====== --}}
                        <div x-data="{}" style="border: 2px dashed var(--border); border-radius: 0.75rem; padding: 1rem; background: var(--bg-body);">
                            <p style="font-size: 0.8rem; font-weight: 700; margin: 0 0 0.75rem 0; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em;">
                                <svg style="display:inline-block; vertical-align:middle; margin-right:4px;" width="14" height="14" viewBox="0 0 87.3 78" xmlns="http://www.w3.org/2000/svg"><path d="m6.6 66.85 3.85 6.65c.8 1.4 1.95 2.5 3.3 3.3l13.75-23.8h-27.5c0 1.55.4 3.1 1.2 4.5z" fill="#0066da"/><path d="m43.65 25-13.75-23.8c-1.35.8-2.5 1.9-3.3 3.3l-25.4 44a9.06 9.06 0 0 0 -1.2 4.5h27.5z" fill="#00ac47"/><path d="m73.55 76.8c1.35-.8 2.5-1.9 3.3-3.3l1.6-2.75 7.65-13.25c.8-1.4 1.2-2.95 1.2-4.5h-27.502l5.852 11.5z" fill="#ea4335"/><path d="m43.65 25 13.75-23.8c-1.35-.8-2.9-1.2-4.5-1.2h-18.5c-1.6 0-3.15.45-4.5 1.2z" fill="#00832d"/><path d="m59.8 53h-32.3l-13.75 23.8c1.35.8 2.9 1.2 4.5 1.2h50.8c1.6 0 3.15-.45 4.5-1.2z" fill="#2684fc"/><path d="m73.4 26.5-12.7-22c-.8-1.4-1.95-2.5-3.3-3.3l-13.75 23.8 16.15 27h27.45c0-1.55-.4-3.1-1.2-4.5z" fill="#ffba00"/></svg>
                                Google Drive
                            </p>

                            {{-- Preview file yang sudah dipilih --}}
                            @if($gdriveFileName)
                                <div style="display:flex; align-items:center; gap:0.75rem; padding:0.75rem; border-radius:0.5rem; background:rgba(66,133,244,0.08); border:1px solid rgba(66,133,244,0.3); margin-bottom:0.75rem;">
                                    <svg width="20" height="20" viewBox="0 0 87.3 78" xmlns="http://www.w3.org/2000/svg"><path d="m6.6 66.85 3.85 6.65c.8 1.4 1.95 2.5 3.3 3.3l13.75-23.8h-27.5c0 1.55.4 3.1 1.2 4.5z" fill="#0066da"/><path d="m43.65 25-13.75-23.8c-1.35.8-2.5 1.9-3.3 3.3l-25.4 44a9.06 9.06 0 0 0 -1.2 4.5h27.5z" fill="#00ac47"/><path d="m73.55 76.8c1.35-.8 2.5-1.9 3.3-3.3l1.6-2.75 7.65-13.25c.8-1.4 1.2-2.95 1.2-4.5h-27.502l5.852 11.5z" fill="#ea4335"/><path d="m43.65 25 13.75-23.8c-1.35-.8-2.9-1.2-4.5-1.2h-18.5c-1.6 0-3.15.45-4.5 1.2z" fill="#00832d"/><path d="m59.8 53h-32.3l-13.75 23.8c1.35.8 2.9 1.2 4.5 1.2h50.8c1.6 0 3.15-.45 4.5-1.2z" fill="#2684fc"/><path d="m73.4 26.5-12.7-22c-.8-1.4-1.95-2.5-3.3-3.3l-13.75 23.8 16.15 27h27.45c0-1.55-.4-3.1-1.2-4.5z" fill="#ffba00"/></svg>
                                    <span style="flex:1; font-size:0.85rem; font-weight:600; color:#1a73e8;">{{ $gdriveFileName }}</span>
                                    <button type="button" wire:click="clearGdrive" style="background:none; border:none; color:#ea4335; cursor:pointer; font-size:1rem;" title="Hapus pilihan">✕</button>
                                </div>
                            @endif

                            <button type="button" id="gdrive-picker-btn"
                                    onclick="openDrivePicker()"
                                    style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.25rem; border-radius:0.5rem; border:1px solid #dadce0; background:white; color:#3c4043; font-size:0.875rem; font-weight:500; cursor:pointer; transition:box-shadow 0.2s;"
                                    onmouseover="this.style.boxShadow='0 1px 6px rgba(32,33,36,.28)'"
                                    onmouseout="this.style.boxShadow='none'">
                                <svg width="18" height="18" viewBox="0 0 87.3 78" xmlns="http://www.w3.org/2000/svg"><path d="m6.6 66.85 3.85 6.65c.8 1.4 1.95 2.5 3.3 3.3l13.75-23.8h-27.5c0 1.55.4 3.1 1.2 4.5z" fill="#0066da"/><path d="m43.65 25-13.75-23.8c-1.35.8-2.5 1.9-3.3 3.3l-25.4 44a9.06 9.06 0 0 0 -1.2 4.5h27.5z" fill="#00ac47"/><path d="m73.55 76.8c1.35-.8 2.5-1.9 3.3-3.3l1.6-2.75 7.65-13.25c.8-1.4 1.2-2.95 1.2-4.5h-27.502l5.852 11.5z" fill="#ea4335"/><path d="m43.65 25 13.75-23.8c-1.35-.8-2.9-1.2-4.5-1.2h-18.5c-1.6 0-3.15.45-4.5 1.2z" fill="#00832d"/><path d="m59.8 53h-32.3l-13.75 23.8c1.35.8 2.9 1.2 4.5 1.2h50.8c1.6 0 3.15-.45 4.5-1.2z" fill="#2684fc"/><path d="m73.4 26.5-12.7-22c-.8-1.4-1.95-2.5-3.3-3.3l-13.75 23.8 16.15 27h27.45c0-1.55-.4-3.1-1.2-4.5z" fill="#ffba00"/></svg>
                                {{ $gdriveFileName ? 'Ganti File Google Drive' : 'Pilih dari Google Drive' }}
                            </button>
                            <p style="font-size:0.72rem; color:var(--text-muted); margin:0.5rem 0 0 0;">File tetap tersimpan di Google Drive Anda. Tidak memakan storage server.</p>
                        </div>
                        {{-- ====== END GOOGLE DRIVE PICKER ====== --}}

                        <div>
                            <label class="form-label" style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: var(--text-primary);">
                                — atau — Upload File Langsung
                            </label>
                            <input type="file" wire:model="fileUpload" class="form-input" style="width: 100%; box-sizing: border-box;">
                            @error('fileUpload') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: var(--text-primary);">Tautan (Link) Lainnya</label>
                            <input type="url" wire:model="linkUrl" placeholder="https://..." class="form-input" style="width: 100%; box-sizing: border-box;">
                            @error('linkUrl') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; color: var(--text-primary);">Catatan</label>
                            <textarea wire:model="catatan" rows="3" class="form-input" style="width: 100%; box-sizing: border-box;"></textarea>
                            @error('catatan') <span style="font-size: 0.75rem; color: var(--danger);">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-full" style="display: flex; justify-content: center; align-items: center; width: 100%;">
                            <span wire:loading.remove wire:target="kumpulkan">Kumpulkan Tugas</span>
                            <span wire:loading wire:target="kumpulkan">Mengirim...</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ====== Google Drive Picker JavaScript ====== --}}
<script>
    const GDRIVE_API_KEY   = '{{ config("google.api_key") }}';
    const GDRIVE_CLIENT_ID = '{{ config("google.client_id") }}';
    let tokenClient;
    let accessToken = null;
    let pickerApiLoaded = false;

    // Load Picker API
    gapi.load('picker', function() {
        pickerApiLoaded = true;
    });

    // Init OAuth token client (Google Identity Services)
    function initTokenClient() {
        tokenClient = google.accounts.oauth2.initTokenClient({
            client_id: GDRIVE_CLIENT_ID,
            scope: 'https://www.googleapis.com/auth/drive.readonly',
            callback: function(response) {
                if (response.error !== undefined) {
                    console.error('OAuth error:', response);
                    return;
                }
                accessToken = response.access_token;
                createPicker();
            },
        });
    }

    function openDrivePicker() {
        if (!tokenClient) initTokenClient();

        if (accessToken === null) {
            // Minta user login Google
            tokenClient.requestAccessToken({ prompt: 'consent' });
        } else {
            // Sudah punya token, langsung buka picker
            createPicker();
        }
    }

    function createPicker() {
        if (!pickerApiLoaded) {
            gapi.load('picker', function() {
                pickerApiLoaded = true;
                createPicker();
            });
            return;
        }

        const view = new google.picker.DocsView()
            .setIncludeFolders(false)
            .setSelectFolderEnabled(false);

        const picker = new google.picker.PickerBuilder()
            .enableFeature(google.picker.Feature.NAV_HIDDEN)
            .setTitle('Pilih File Tugas dari Google Drive Anda')
            .addView(view)
            .addView(new google.picker.DocsUploadView())
            .setOAuthToken(accessToken)
            .setDeveloperKey(GDRIVE_API_KEY)
            .setCallback(pickerCallback)
            .build();

        picker.setVisible(true);
    }

    function pickerCallback(data) {
        if (data.action === google.picker.Action.PICKED) {
            const file = data.docs[0];
            const fileId   = file.id;
            const fileName = file.name;
            const fileUrl  = `https://drive.google.com/file/d/${fileId}/view`;

            // Kirim ke Livewire component
            @this.set('gdriveFileId',   fileId);
            @this.set('gdriveFileName', fileName);
            @this.set('gdriveFileUrl',  fileUrl);
        }
    }
</script>
