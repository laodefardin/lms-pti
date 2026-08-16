<div class="fade-in">
    <div class="section-header" style="margin-bottom: 2rem;">
        <h1 class="section-title">Profil Saya 👤</h1>
        <p class="section-sub" style="color: var(--text-secondary);">Kelola informasi profil dan pengaturan akun Anda.</p>
    </div>

    <div style="display: flex; gap: 2rem; align-items: flex-start; flex-wrap: wrap;">
        <!-- Left Column: Profile Card -->
        <div style="flex: 1; min-width: 300px;">
            <div class="card" style="text-align: center; margin-bottom: 1.5rem;">
                <div style="position: relative; width: 120px; height: 120px; margin: 0 auto 1rem;">
                    @if ($foto)
                        <img src="{{ $foto->temporaryUrl() }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--teal-light);">
                    @else
                        <img src="{{ $user->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--teal-light);">
                    @endif
                    
                    <div style="margin-top: 1rem;">
                        <input type="file" wire:model="foto" id="foto" style="display: none;">
                        <label for="foto" class="btn btn-outline btn-sm" style="cursor: pointer; display: inline-block;">Pilih Foto</label>
                    </div>
                </div>
                @if($foto)
                    <div style="margin-bottom: 1rem;">
                        <button wire:click="uploadFoto" class="btn btn-primary btn-sm">Unggah</button>
                    </div>
                    @error('foto') <span class="form-error" style="display:block; margin-bottom:1rem;">{{ $message }}</span> @enderror
                @endif
                @if(session()->has('success_foto'))
                    <div style="color: var(--success); font-size: 0.875rem; margin-bottom: 1rem;">{{ session('success_foto') }}</div>
                @endif

                <h2 style="font-size: 1.25rem; font-weight: bold; color: var(--text-primary); margin-bottom: 0.5rem;">{{ $user->name }}</h2>
                <div style="margin-bottom: 1rem;">
                    <span class="badge badge-teal">Mahasiswa</span>
                </div>

                <div style="text-align: left; background: var(--bg-card); border-radius: 0.5rem; padding: 1rem; border: 1px solid var(--border);">
                    <div style="margin-bottom: 0.5rem;">
                        <strong style="color: var(--text-secondary); font-size: 0.875rem;">NIM</strong>
                        <div style="color: var(--text-primary);">{{ $user->nim ?? '-' }}</div>
                    </div>
                    <div style="margin-bottom: 0.5rem;">
                        <strong style="color: var(--text-secondary); font-size: 0.875rem;">Angkatan</strong>
                        <div style="color: var(--text-primary);">{{ $user->angkatan ?? '-' }}</div>
                    </div>
                    <div style="margin-bottom: 0.5rem;">
                        <strong style="color: var(--text-secondary); font-size: 0.875rem;">Email</strong>
                        <div style="color: var(--text-primary); word-break: break-all;">{{ $user->email }}</div>
                    </div>
                    <div>
                        <strong style="color: var(--text-secondary); font-size: 0.875rem;">No HP</strong>
                        <div style="color: var(--text-primary);">{{ $user->no_hp ?? '-' }}</div>
                    </div>
                </div>

                <div style="margin-top: 1.5rem; text-align: left;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <strong style="color: var(--text-primary);">Total Poin</strong>
                        <span class="badge badge-orange">{{ $user->gamifikasiPoin()->sum('jumlah_poin') ?? 0 }} pts</span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 1rem;">
                        <span class="badge badge-purple">Pemula</span>
                        <span class="badge badge-green">Rajin</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Details & Settings -->
        <div style="flex: 2; min-width: 400px;" x-data="{ tab: 'info' }">
            <div class="card" style="margin-bottom: 1.5rem;">
                <div style="display: flex; border-bottom: 1px solid var(--border); margin-bottom: 1.5rem; gap: 1rem;">
                    <button @click="tab = 'info'" :style="tab === 'info' ? 'border-bottom: 2px solid var(--teal); color: var(--teal); font-weight: bold;' : 'color: var(--text-secondary);'" style="padding: 0.5rem 1rem; background: none; border: none; border-bottom: 2px solid transparent; cursor: pointer; font-size: 1rem;">Info Pribadi</button>
                    <button @click="tab = 'keamanan'" :style="tab === 'keamanan' ? 'border-bottom: 2px solid var(--teal); color: var(--teal); font-weight: bold;' : 'color: var(--text-secondary);'" style="padding: 0.5rem 1rem; background: none; border: none; border-bottom: 2px solid transparent; cursor: pointer; font-size: 1rem;">Keamanan</button>
                </div>

                <!-- Info Tab -->
                <div x-show="tab === 'info'">
                    @if(session()->has('success_profile'))
                        <div style="background: var(--success); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                            {{ session('success_profile') }}
                        </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 class="section-title" style="font-size: 1.125rem;">Informasi Dasar</h3>
                        @if(!$editMode)
                            <button wire:click="edit" class="btn btn-outline btn-sm">Edit Profil</button>
                        @endif
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div>
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="form-input" {{ $editMode ? '' : 'disabled' }}>
                            @error('name') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="form-label">Angkatan</label>
                            <input type="text" wire:model="angkatan" class="form-input" {{ $editMode ? '' : 'disabled' }}>
                            @error('angkatan') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">No Handphone</label>
                        <input type="text" wire:model="no_hp" class="form-input" {{ $editMode ? '' : 'disabled' }}>
                        @error('no_hp') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Bio Singkat</label>
                        <textarea wire:model="bio" class="form-input" rows="4" {{ $editMode ? '' : 'disabled' }} placeholder="Ceritakan sedikit tentang diri Anda..."></textarea>
                        @error('bio') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    @if($editMode)
                        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                            <button wire:click="$set('editMode', false)" class="btn btn-ghost">Batal</button>
                            <button wire:click="save" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    @endif
                </div>

                <!-- Keamanan Tab -->
                <div x-show="tab === 'keamanan'" style="display: none;">
                    <h3 class="section-title" style="font-size: 1.125rem; margin-bottom: 1.5rem;">Ubah Password</h3>
                    
                    @if(session()->has('success_password'))
                        <div style="background: var(--success); color: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                            {{ session('success_password') }}
                        </div>
                    @endif

                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" wire:model="currentPassword" class="form-input">
                        @error('currentPassword') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <label class="form-label">Password Baru</label>
                        <input type="password" wire:model="newPassword" class="form-input">
                        @error('newPassword') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" wire:model="newPasswordConfirmation" class="form-input">
                        @error('newPasswordConfirmation') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div style="text-align: right;">
                        <button wire:click="changePassword" class="btn btn-primary">Ubah Password</button>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div class="card" style="background: linear-gradient(135deg, var(--teal), var(--teal-dark)); color: white; border: none;">
                    <div style="font-size: 2rem; font-weight: bold; margin-bottom: 0.5rem;">{{ $stats['kelas_count'] }}</div>
                    <div style="opacity: 0.9;">Mata Kuliah Diikuti</div>
                </div>
                <div class="card" style="background: var(--bg-card); border-left: 4px solid var(--teal);">
                    <div style="font-size: 2rem; font-weight: bold; color: var(--teal); margin-bottom: 0.5rem;">{{ $stats['tugas_selesai'] }}</div>
                    <div style="color: var(--text-secondary);">Tugas Selesai</div>
                </div>
                <div class="card" style="background: var(--bg-card); border-left: 4px solid var(--orange);">
                    <div style="font-size: 2rem; font-weight: bold; color: var(--orange); margin-bottom: 0.5rem;">{{ $stats['kuis_selesai'] }}</div>
                    <div style="color: var(--text-secondary);">Kuis Selesai</div>
                </div>
                <div class="card" style="background: var(--bg-card); border-left: 4px solid var(--success);">
                    <div style="font-size: 2rem; font-weight: bold; color: var(--success); margin-bottom: 0.5rem;">{{ $stats['materi_selesai'] }}</div>
                    <div style="color: var(--text-secondary);">Materi Selesai</div>
                </div>
            </div>
        </div>
    </div>
</div>