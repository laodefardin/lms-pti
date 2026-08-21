<div class="fade-in">

    {{-- Header --}}
    <div class="section-header" style="margin-bottom:2rem;">
        <h1 class="section-title">Profil Saya</h1>
        <p class="section-sub" style="color:var(--text-secondary);">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    {{-- Flash messages --}}
    @foreach(['success_profile' => 'fa-check-circle', 'success_foto' => 'fa-camera', 'success_password' => 'fa-lock'] as $key => $icon)
        @if(session($key))
            <div style="display:flex;align-items:center;gap:.6rem;background:rgba(34,197,94,.12);border:1px solid #22c55e;border-radius:.75rem;padding:.85rem 1.25rem;margin-bottom:1.5rem;color:#16a34a;font-weight:600;font-size:.875rem;">
                <i class="fas {{ $icon }}"></i> {{ session($key) }}
            </div>
        @endif
    @endforeach

    <div style="display:grid;grid-template-columns:300px 1fr;gap:1.5rem;align-items:start;">

        {{-- ===== LEFT ===== --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">

            {{-- Avatar Card --}}
            <div class="card" style="text-align:center;padding:2rem 1.5rem;">
                <div style="position:relative;width:110px;height:110px;margin:0 auto 1.25rem;">
                    @if($foto)
                        <img src="{{ $foto->temporaryUrl() }}" style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid var(--teal);">
                    @else
                        <img src="{{ $user->foto ? asset('storage/'.$user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=004b93&background=e8f2fc&size=200' }}"
                             style="width:110px;height:110px;border-radius:50%;object-fit:cover;border:4px solid var(--teal);"
                             onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=004b93&background=e8f2fc&size=200'">
                    @endif
                    <label for="foto-upload" style="position:absolute;bottom:0;right:0;width:32px;height:32px;border-radius:50%;background:var(--teal);border:2px solid var(--bg-card);display:flex;align-items:center;justify-content:center;cursor:pointer;">
                        <i class="fas fa-camera" style="font-size:.7rem;color:white;"></i>
                    </label>
                    <input type="file" id="foto-upload" wire:model="foto" accept="image/*" style="display:none;">
                </div>

                @if($foto)
                    <div style="margin-bottom:.75rem;">
                        <button wire:click="uploadFoto" class="btn btn-primary btn-sm" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="uploadFoto"><i class="fas fa-upload"></i> Simpan Foto</span>
                            <span wire:loading wire:target="uploadFoto"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                    @error('foto') <p style="font-size:.75rem;color:var(--danger);">{{ $message }}</p> @enderror
                @endif

                <h2 style="font-size:1.1rem;font-weight:800;color:var(--text-primary);margin-bottom:.25rem;">{{ $user->name }}</h2>
                <p style="font-size:.8rem;color:var(--text-muted);margin-bottom:.75rem;">{{ $user->email }}</p>
                <span style="display:inline-flex;align-items:center;gap:.4rem;padding:.3rem .9rem;border-radius:99px;font-size:.75rem;font-weight:700;text-transform:uppercase;background:rgba(16,185,129,.12);color:#059669;">
                    <i class="fas fa-user-graduate"></i> Mahasiswa
                </span>

                @if($user->google_id)
                    <div style="margin-top:.75rem;display:inline-flex;align-items:center;gap:.4rem;padding:.25rem .75rem;border-radius:99px;font-size:.72rem;font-weight:600;background:rgba(66,133,244,.1);color:#4285f4;border:1px solid rgba(66,133,244,.25);">
                        <svg width="12" height="12" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                        Terhubung Google
                    </div>
                @endif
            </div>

            {{-- Stats --}}
            <div class="card" style="padding:1.25rem;">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:1rem;">Statistik Belajar</p>
                <div style="display:flex;flex-direction:column;gap:.75rem;">
                    @foreach([
                        ['icon'=>'fa-book-open','label'=>'Kelas Diikuti','val'=>$stats['kelas_count'],'color'=>'var(--teal)'],
                        ['icon'=>'fa-tasks','label'=>'Tugas Dikumpulkan','val'=>$stats['tugas_selesai'],'color'=>'#f59e0b'],
                        ['icon'=>'fa-clipboard-check','label'=>'Kuis Selesai','val'=>$stats['kuis_selesai'],'color'=>'#8b5cf6'],
                        ['icon'=>'fa-star','label'=>'Total Poin','val'=>$stats['total_poin'],'color'=>'#f59e0b'],
                    ] as $s)
                        <div style="display:flex;justify-content:space-between;align-items:center;">
                            <span style="font-size:.85rem;color:var(--text-secondary);"><i class="fas {{ $s['icon'] }}" style="width:16px;color:{{ $s['color'] }};"></i> {{ $s['label'] }}</span>
                            <strong style="color:var(--text-primary);">{{ $s['val'] }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Info --}}
            <div class="card" style="padding:1.25rem;">
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);margin-bottom:1rem;">Informasi Akun</p>
                <div style="display:flex;flex-direction:column;gap:.75rem;">
                    <div><p style="font-size:.72rem;color:var(--text-muted);margin-bottom:.15rem;">NIM</p><p style="font-size:.9rem;font-weight:600;color:var(--text-primary);">{{ $user->nim_nidn ?? '-' }}</p></div>
                    <div><p style="font-size:.72rem;color:var(--text-muted);margin-bottom:.15rem;">Angkatan</p><p style="font-size:.9rem;font-weight:600;color:var(--text-primary);">{{ $user->angkatan ?? '-' }}</p></div>
                    <div><p style="font-size:.72rem;color:var(--text-muted);margin-bottom:.15rem;">No. HP</p><p style="font-size:.9rem;font-weight:600;color:var(--text-primary);">{{ $user->no_hp ?? '-' }}</p></div>
                    <div><p style="font-size:.72rem;color:var(--text-muted);margin-bottom:.15rem;">Bergabung</p><p style="font-size:.9rem;font-weight:600;color:var(--text-primary);">{{ $user->created_at->format('d M Y') }}</p></div>
                </div>
            </div>
        </div>

        {{-- ===== RIGHT ===== --}}
        <div style="display:flex;flex-direction:column;gap:1.5rem;">

            {{-- Edit Profil --}}
            <div class="card" style="padding:1.75rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:{{ $editMode ? '1.5rem' : '0' }};{{ $editMode ? 'padding-bottom:1rem;border-bottom:1px solid var(--border);' : '' }}">
                    <div>
                        <h3 style="font-size:1.05rem;font-weight:700;color:var(--text-primary);margin-bottom:.2rem;">Informasi Pribadi</h3>
                        <p style="font-size:.8rem;color:var(--text-muted);">Perbarui nama, angkatan, nomor HP, dan bio Anda.</p>
                    </div>
                    @if(!$editMode)
                        <button wire:click="$set('editMode', true)" class="btn btn-outline btn-sm"><i class="fas fa-pen"></i> Edit</button>
                    @endif
                </div>

                @if($editMode)
                    <form wire:submit.prevent="save" style="display:flex;flex-direction:column;gap:1.25rem;">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                            <div>
                                <label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Nama Lengkap *</label>
                                <input type="text" wire:model="name" class="form-input" placeholder="Nama lengkap">
                                @error('name') <p style="font-size:.75rem;color:var(--danger);margin-top:.2rem;">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Angkatan</label>
                                <input type="text" wire:model="angkatan" class="form-input" placeholder="2023">
                            </div>
                            <div>
                                <label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Nomor HP</label>
                                <input type="text" wire:model="no_hp" class="form-input" placeholder="08xx-xxxx-xxxx">
                            </div>
                            <div>
                                <label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Email</label>
                                <input type="email" value="{{ $email }}" class="form-input" disabled style="opacity:.6;cursor:not-allowed;">
                            </div>
                        </div>
                        <div>
                            <label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Bio</label>
                            <textarea wire:model="bio" rows="3" class="form-input" placeholder="Ceritakan tentang diri Anda..."></textarea>
                        </div>
                        <div style="display:flex;gap:.75rem;">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="save"><i class="fas fa-save"></i> Simpan</span>
                                <span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                            <button type="button" wire:click="$set('editMode', false)" class="btn btn-outline">Batal</button>
                        </div>
                    </form>
                @else
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                        <div><p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.2rem;">Nama Lengkap</p><p style="font-weight:600;color:var(--text-primary);">{{ $user->name }}</p></div>
                        <div><p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.2rem;">NIM</p><p style="font-weight:600;color:var(--text-primary);">{{ $user->nim_nidn ?? '-' }}</p></div>
                        <div><p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.2rem;">Angkatan</p><p style="font-weight:600;color:var(--text-primary);">{{ $user->angkatan ?? '-' }}</p></div>
                        <div><p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.2rem;">Nomor HP</p><p style="font-weight:600;color:var(--text-primary);">{{ $user->no_hp ?? '-' }}</p></div>
                        <div><p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.2rem;">Email</p><p style="font-weight:600;color:var(--text-primary);word-break:break-all;">{{ $user->email }}</p></div>
                        <div style="grid-column:span 2;"><p style="font-size:.75rem;color:var(--text-muted);margin-bottom:.2rem;">Bio</p><p style="color:var(--text-secondary);font-size:.9rem;line-height:1.6;">{{ $user->bio ?: 'Belum ada bio.' }}</p></div>
                    </div>
                @endif
            </div>

            {{-- Ubah Password --}}
            <div class="card" style="padding:1.75rem;">
                <div style="display:flex;justify-content:space-between;align-items:center;{{ $showPasswordForm ? 'margin-bottom:1.5rem;padding-bottom:1rem;border-bottom:1px solid var(--border);' : '' }}">
                    <div>
                        <h3 style="font-size:1.05rem;font-weight:700;color:var(--text-primary);margin-bottom:.2rem;"><i class="fas fa-shield-alt" style="color:var(--teal);margin-right:.4rem;"></i>Keamanan Akun</h3>
                        <p style="font-size:.8rem;color:var(--text-muted);">Perbarui password untuk menjaga keamanan akun.</p>
                    </div>
                    @if(!$showPasswordForm)
                        <button wire:click="$set('showPasswordForm', true)" class="btn btn-outline btn-sm"><i class="fas fa-key"></i> Ubah Password</button>
                    @endif
                </div>
                @if($showPasswordForm)
                    <form wire:submit.prevent="changePassword" style="display:flex;flex-direction:column;gap:1.25rem;">
                        <div><label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Password Saat Ini</label>
                            <input type="password" wire:model="currentPassword" class="form-input" placeholder="••••••••">
                            @error('currentPassword') <p style="font-size:.75rem;color:var(--danger);margin-top:.2rem;">{{ $message }}</p> @enderror</div>
                        <div><label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Password Baru (min. 8 karakter)</label>
                            <input type="password" wire:model="newPassword" class="form-input" placeholder="••••••••">
                            @error('newPassword') <p style="font-size:.75rem;color:var(--danger);margin-top:.2rem;">{{ $message }}</p> @enderror</div>
                        <div><label style="font-size:.8rem;font-weight:600;color:var(--text-secondary);display:block;margin-bottom:.4rem;">Konfirmasi Password Baru</label>
                            <input type="password" wire:model="newPasswordConfirmation" class="form-input" placeholder="••••••••"></div>
                        <div style="display:flex;gap:.75rem;">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <span wire:loading.remove wire:target="changePassword"><i class="fas fa-lock"></i> Simpan Password</span>
                                <span wire:loading wire:target="changePassword"><i class="fas fa-spinner fa-spin"></i></span>
                            </button>
                            <button type="button" wire:click="$set('showPasswordForm', false)" class="btn btn-outline">Batal</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>