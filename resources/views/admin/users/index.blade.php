@extends('layouts.admin')
@section('title','Kelola User')
@section('page-title','Kelola User')

@section('content')
@if($errors->createUser->any())
    <div class="mb-4 rounded-2xl border border-[#f4c2be] bg-[#fff1f0] px-4 py-3 text-sm text-[#b42318]">
        <p class="mb-1 font-semibold">Gagal menambahkan user:</p>
        <ul class="list-disc space-y-0.5 pl-5">
            @foreach($errors->createUser->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<section class="mb-5 overflow-hidden rounded-3xl border border-[#d4e892] bg-gradient-to-r from-[#9abc05] via-[#779f11] to-[#6a8d0d] px-8 py-6 text-white shadow-[0_14px_28px_-16px_rgba(24,84,32,0.6)]">
    <h2 class="text-4xl font-extrabold tracking-tight text-[#f1f8ca]">Kelola Pengguna</h2>
    <p class="mt-1 text-sm text-[#e7f5bf]">Lihat, edit, dan kelola seluruh data pengguna NutriGo</p>
</section>

<section class="mb-5 rounded-2xl border border-[#eadfce] bg-white p-4 shadow-sm">
    @php
        $hasActiveFilter = request()->filled('search') || request()->filled('status');
    @endphp
    <form method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-center">
        <div class="flex-[1.2]">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari nama atau email..."
                class="w-full rounded-full border border-[#f3dede] bg-[#fbefef] px-4 py-3 text-sm text-[#8d8d8d] outline-none ring-[#f96015] transition focus:ring-2 lg:max-w-xl"
            >
        </div>

        <div class="flex items-center gap-2 lg:flex-nowrap">
            <div class="relative">
                <select name="status" class="rounded-full border border-[#e7dfd5] bg-[#f7f0e4] py-3 pl-4 pr-28 text-sm font-medium text-[#6b7280] outline-none ring-[#9abc05] focus:ring-2">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="setup" {{ request('status') === 'setup' ? 'selected' : '' }}>Setup</option>
                </select>

                <div class="absolute inset-y-0 right-1 flex items-center gap-1.5">
                    <button type="submit" class="rounded-full bg-[#f96015] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-[#e55310]">
                        Filter
                    </button>

                    @if($hasActiveFilter)
                        <a href="{{ route('admin.users.index') }}" class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-[#e7dfd5] bg-white text-[#a59c94] transition hover:border-[#d52518] hover:text-[#d52518]" title="Hapus filter">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 6L6 18"></path>
                                <path d="M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </div>
            </div>

            <button type="button" id="openCreateUserModal" class="rounded-full bg-[#185420] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#123b18]">
                + Tambah User
            </button>
        </div>
    </form>
</section>

<div id="createUserOverlay" class="fixed inset-0 z-40 hidden bg-black/35"></div>
<div id="createUserModal" class="fixed left-1/2 top-1/2 z-50 hidden w-[92%] max-w-lg -translate-x-1/2 -translate-y-1/2 rounded-3xl border border-[#eadfce] bg-white p-6 shadow-2xl">
    <div class="mb-4 flex items-start justify-between gap-4">
        <div>
            <h3 class="text-xl font-extrabold text-[#2f2422]">Tambah User Baru</h3>
            <p class="text-sm text-[#7f746d]">Isi data inti. Profil kesehatan dilanjutkan user saat login pertama.</p>
        </div>
        <button type="button" id="closeCreateUserModal" class="rounded-full p-2 text-[#2f2422] transition hover:bg-[#f5f2ef]" aria-label="Tutup modal tambah user">
            <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
        </button>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
        @csrf

        <div>
            <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
        </div>

        <div>
            <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Role</label>
            <select name="role" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
                <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
            <div>
                <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Password Sementara</label>
                <div class="relative">
                    <input id="createPassword" type="password" name="password" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 pr-11 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
                    <button type="button" data-password-toggle="createPassword" class="absolute inset-y-0 right-2 inline-flex items-center rounded-lg px-2 text-[#8b7d78] hover:bg-[#f7f0e4]" aria-label="Tampilkan password sementara">
                        <svg data-icon-eye="open" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg data-icon-eye="closed" viewBox="0 0 24 24" class="hidden h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.96 10.96 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 015.06-5.94"></path><path d="M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.8 21.8 0 01-3.17 4.5"></path><path d="M14.12 14.12a3 3 0 01-4.24-4.24"></path><path d="M1 1l22 22"></path></svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-[#998e88]">Minimal 8 karakter.</p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Konfirmasi Password</label>
                <div class="relative">
                    <input id="createPasswordConfirmation" type="password" name="password_confirmation" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 pr-11 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
                    <button type="button" data-password-toggle="createPasswordConfirmation" class="absolute inset-y-0 right-2 inline-flex items-center rounded-lg px-2 text-[#8b7d78] hover:bg-[#f7f0e4]" aria-label="Tampilkan konfirmasi password">
                        <svg data-icon-eye="open" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg data-icon-eye="closed" viewBox="0 0 24 24" class="hidden h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.96 10.96 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 015.06-5.94"></path><path d="M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.8 21.8 0 01-3.17 4.5"></path><path d="M14.12 14.12a3 3 0 01-4.24-4.24"></path><path d="M1 1l22 22"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-2">
            <button type="submit" class="rounded-xl bg-[#f96015] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#e55310]">Simpan User</button>
        </div>
    </form>
</div>

<section class="overflow-hidden rounded-3xl border border-[#eadfce] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-[#ecd8d4] bg-[#fbf7f2] text-xs uppercase tracking-wider text-[#9e847e]">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Tanggal Daftar</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f2e8e3]">
                @forelse($users as $u)
                    @php
                        $editPayload = [
                            'id' => $u->id,
                            'name' => $u->name,
                            'email' => $u->email,
                            'nickname' => $u->nickname,
                            'birth_date' => optional($u->birth_date)->format('Y-m-d'),
                            'age' => $u->getAge(),
                            'gender' => $u->gender,
                            'city' => $u->city,
                            'province' => $u->province,
                            'height_cm' => $u->height_cm,
                            'weight_kg' => $u->weight_kg,
                            'bmi' => $u->bmi,
                            'daily_calorie_needs' => $u->daily_calorie_needs,
                            'activity_level' => $u->activity_level,
                            'onboarding_completed' => (bool) $u->onboarding_completed,
                            'allergies' => $u->allergies->pluck('allergen')->values()->all(),
                            'member_since' => optional($u->created_at)->isoFormat('MMM YYYY'),
                            'update_url' => route('admin.users.update', $u),
                        ];
                    @endphp

                    <tr class="hover:bg-[#fffdfa]">
                        <td class="px-6 py-4 font-semibold text-[#d9b8b0]">{{ str_pad((string) (($users->currentPage() - 1) * $users->perPage() + $loop->iteration), 2, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#185420] text-sm font-bold text-white">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </span>
                                <span class="font-semibold text-[#3f3a39]">{{ $u->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-[#57534e]">{{ $u->email }}</td>
                        <td class="px-6 py-4 text-[#57534e]">{{ $u->created_at?->isoFormat('D MMM YYYY') }}</td>
                        <td class="px-6 py-4">
                            @if($u->onboarding_completed)
                                <span class="rounded-full bg-[#e7efd5] px-3 py-1 text-xs font-semibold text-[#7a9708]">Aktif</span>
                            @else
                                <span class="rounded-full bg-[#fde6e6] px-3 py-1 text-xs font-semibold text-[#d52518]">Setup</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.users.show', $u) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-[#86a636] text-[#6f8d19] transition hover:bg-[#edf5db]" title="Lihat detail">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                </a>

                                <button
                                    type="button"
                                    data-edit-user='@json($editPayload)'
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-[#f96015] text-[#f96015] transition hover:bg-[#fff1ea]"
                                    title="Edit user"
                                >
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>
                                </button>

                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}" onsubmit="return confirm('Yakin hapus user {{ $u->name }}?')" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#d52518] text-white transition hover:bg-[#bf1f14]" title="Hapus user">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-sm text-[#b1a9a3]">Tidak ada user ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-3 border-t border-[#f2e8e3] px-6 py-4 text-sm text-[#766f69] md:flex-row md:items-center md:justify-between">
        <p>
            Menampilkan {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} pengguna
        </p>
        <div>{{ $users->withQueryString()->links() }}</div>
    </div>
</section>

<div id="userDrawerOverlay" class="fixed inset-0 z-40 hidden bg-black/35"></div>

<aside id="userDrawer" class="fixed right-0 top-0 z-50 h-full w-full max-w-xl translate-x-full overflow-y-auto border-l border-[#eadfce] bg-[#f5f2ef] shadow-2xl transition-transform duration-300">
    <div class="sticky top-0 z-10 flex items-center justify-between border-b border-[#eadfce] bg-white px-6 py-5">
        <h3 class="text-xl font-extrabold text-[#2f2422]">Detail Pengguna</h3>
        <button type="button" id="closeUserDrawer" class="rounded-full p-2 text-[#2f2422] hover:bg-[#f3f3f3]" aria-label="Tutup drawer">
            <svg viewBox="0 0 24 24" class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
        </button>
    </div>

    <form id="userEditForm" method="POST" class="p-6">
        @csrf
        @method('PUT')
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="page" value="{{ request('page', 1) }}">

        <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm">
            <div class="mx-auto mb-3 flex h-24 w-24 items-center justify-center rounded-full border-4 border-[#9abc05] bg-[#f3e8cc] text-3xl font-extrabold text-[#185420]" id="drawerAvatar">
                A
            </div>
            <h4 id="drawerNameHeading" class="text-center text-2xl font-extrabold text-[#2f2422]">Nama User</h4>
            <p id="drawerMembership" class="text-center text-sm text-[#5f4540]">Member sejak -</p>
        </div>

        <div class="grid grid-cols-2 gap-3">
            <label class="rounded-2xl bg-[#f8e9e7] p-4 text-center">
                <span class="text-xs font-bold uppercase tracking-wide text-[#dfb7b0]">Berat Badan</span>
                <input id="drawerWeight" name="weight_kg" type="number" step="0.1" min="10" max="500" class="mt-2 w-full bg-transparent text-center text-2xl font-extrabold text-[#4a6200] outline-none" placeholder="0">
                <span class="text-sm text-[#4a6200]">kg</span>
            </label>
            <label class="rounded-2xl bg-[#f8e9e7] p-4 text-center">
                <span class="text-xs font-bold uppercase tracking-wide text-[#dfb7b0]">Tinggi Badan</span>
                <input id="drawerHeight" name="height_cm" type="number" step="0.1" min="40" max="300" class="mt-2 w-full bg-transparent text-center text-2xl font-extrabold text-[#4a6200] outline-none" placeholder="0">
                <span class="text-sm text-[#4a6200]">cm</span>
            </label>
            <label class="rounded-2xl bg-[#f8e9e7] p-4 text-center">
                <span class="text-xs font-bold uppercase tracking-wide text-[#dfb7b0]">Usia</span>
                <input id="drawerAge" type="text" readonly class="mt-2 w-full bg-transparent text-center text-2xl font-extrabold text-[#4a6200] outline-none" placeholder="-">
                <span class="text-sm text-[#4a6200]">thn</span>
            </label>
            <label class="rounded-2xl bg-[#f8e9e7] p-4 text-center">
                <span class="text-xs font-bold uppercase tracking-wide text-[#dfb7b0]">Gender</span>
                <select id="drawerGender" name="gender" class="mt-2 w-full bg-transparent text-center text-xl font-extrabold text-[#4a6200] outline-none">
                    <option value="">-</option>
                    <option value="male">Laki-laki</option>
                    <option value="female">Wanita</option>
                </select>
            </label>
        </div>

        <div class="mt-6 rounded-2xl bg-[#e7efd3] p-4">
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold text-[#4a6200]">Hasil BMI</p>
                <span id="drawerBmiBadge" class="rounded-full bg-[#6b8e00] px-4 py-1 text-sm font-bold text-white">-</span>
            </div>
            <p id="drawerBmiValue" class="mt-2 text-3xl font-extrabold text-[#4a6200]">-</p>
        </div>

        <div class="mt-5 rounded-2xl bg-white p-4 shadow-sm">
            <p class="text-lg font-bold text-[#2f2422]">Kebutuhan Kalori Harian</p>
            <p class="mt-1 text-4xl font-extrabold text-[#4a6200]"><span id="drawerCalories">-</span> <span class="text-xl font-semibold text-[#5f4540]">kkal/hari</span></p>
        </div>

        <div class="mt-5 rounded-2xl bg-white p-4 shadow-sm">
            <label class="block text-lg font-bold text-[#2f2422]">Tingkat Aktivitas</label>
            <select id="drawerActivity" name="activity_level" class="mt-3 w-full rounded-full border border-[#dce9b8] bg-[#e7efd3] px-4 py-2 text-sm font-semibold text-[#4a6200] outline-none ring-[#9abc05] focus:ring-2">
                <option value="sedentary">Sedentary</option>
                <option value="light">Light</option>
                <option value="moderate">Sedang</option>
                <option value="active">Active</option>
                <option value="very_active">Very Active</option>
            </select>
        </div>

        <div class="mt-5 rounded-2xl bg-white p-4 shadow-sm">
            <p class="text-lg font-bold text-[#2f2422]">Alergi & Pantangan</p>
            <div id="drawerAllergies" class="mt-3 flex flex-wrap gap-2 text-sm"></div>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 rounded-2xl bg-white p-4 shadow-sm">
            <label>
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Nama</span>
                <input id="drawerName" name="name" type="text" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
            </label>
            <label>
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Nickname</span>
                <input id="drawerNickname" name="nickname" type="text" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2">
            </label>
            <label class="col-span-2">
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Email</span>
                <input id="drawerEmail" name="email" type="email" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
            </label>
            <label>
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Tanggal Lahir</span>
                <input id="drawerBirthDate" name="birth_date" type="date" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2">
            </label>
            <label>
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Status Onboarding</span>
                <select id="drawerOnboarding" name="onboarding_completed" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2">
                    <option value="0">Setup</option>
                    <option value="1">Aktif</option>
                </select>
            </label>
            <label>
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Kota</span>
                <input id="drawerCity" name="city" type="text" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2">
            </label>
            <label>
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Provinsi</span>
                <input id="drawerProvince" name="province" type="text" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2">
            </label>
            <label class="col-span-2 md:col-span-1">
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Password Baru</span>
                <div class="relative">
                    <input id="drawerPassword" name="password" type="password" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 pr-11 text-sm outline-none ring-[#9abc05] focus:ring-2" placeholder="Kosongkan jika tidak diubah">
                    <button
                        type="button"
                        data-password-toggle="drawerPassword"
                        class="absolute inset-y-0 right-2 inline-flex items-center rounded-lg px-2 text-[#8b7d78] hover:bg-[#f7f0e4]"
                        aria-label="Tampilkan password baru"
                    >
                        <svg data-icon-eye="open" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-icon-eye="closed" viewBox="0 0 24 24" class="hidden h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.96 10.96 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 015.06-5.94"></path>
                            <path d="M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.8 21.8 0 01-3.17 4.5"></path>
                            <path d="M14.12 14.12a3 3 0 01-4.24-4.24"></path>
                            <path d="M1 1l22 22"></path>
                        </svg>
                    </button>
                </div>
            </label>
            <label class="col-span-2 md:col-span-1">
                <span class="mb-1 block text-sm font-semibold text-[#8b7d78]">Konfirmasi Password</span>
                <div class="relative">
                    <input id="drawerPasswordConfirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 pr-11 text-sm outline-none ring-[#9abc05] focus:ring-2" placeholder="Ulangi password baru">
                    <button
                        type="button"
                        data-password-toggle="drawerPasswordConfirmation"
                        class="absolute inset-y-0 right-2 inline-flex items-center rounded-lg px-2 text-[#8b7d78] hover:bg-[#f7f0e4]"
                        aria-label="Tampilkan konfirmasi password"
                    >
                        <svg data-icon-eye="open" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg data-icon-eye="closed" viewBox="0 0 24 24" class="hidden h-4 w-4" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17.94 17.94A10.96 10.96 0 0112 19c-7 0-11-7-11-7a21.77 21.77 0 015.06-5.94"></path>
                            <path d="M9.9 4.24A10.94 10.94 0 0112 4c7 0 11 7 11 7a21.8 21.8 0 01-3.17 4.5"></path>
                            <path d="M14.12 14.12a3 3 0 01-4.24-4.24"></path>
                            <path d="M1 1l22 22"></path>
                        </svg>
                    </button>
                </div>
            </label>
        </div>

        <button class="mt-6 w-full rounded-2xl bg-[#f96015] px-5 py-3 text-base font-bold text-white transition hover:bg-[#e55310]">Simpan Perubahan</button>
    </form>
</aside>
@endsection

@push('scripts')
<script>
    (() => {
        const drawer = document.getElementById('userDrawer');
        const overlay = document.getElementById('userDrawerOverlay');
        const closeBtn = document.getElementById('closeUserDrawer');
        const form = document.getElementById('userEditForm');
        const allergyWrap = document.getElementById('drawerAllergies');
        const createUserModal = document.getElementById('createUserModal');
        const createUserOverlay = document.getElementById('createUserOverlay');
        const openCreateUserModalBtn = document.getElementById('openCreateUserModal');
        const closeCreateUserModalBtn = document.getElementById('closeCreateUserModal');
        const hasCreateError = {{ $errors->createUser->any() ? 'true' : 'false' }};

        const fields = {
            avatar: document.getElementById('drawerAvatar'),
            nameHeading: document.getElementById('drawerNameHeading'),
            membership: document.getElementById('drawerMembership'),
            bmiValue: document.getElementById('drawerBmiValue'),
            bmiBadge: document.getElementById('drawerBmiBadge'),
            calories: document.getElementById('drawerCalories'),
            name: document.getElementById('drawerName'),
            email: document.getElementById('drawerEmail'),
            nickname: document.getElementById('drawerNickname'),
            birthDate: document.getElementById('drawerBirthDate'),
            age: document.getElementById('drawerAge'),
            gender: document.getElementById('drawerGender'),
            city: document.getElementById('drawerCity'),
            province: document.getElementById('drawerProvince'),
            height: document.getElementById('drawerHeight'),
            weight: document.getElementById('drawerWeight'),
            activity: document.getElementById('drawerActivity'),
            onboarding: document.getElementById('drawerOnboarding'),
            password: document.getElementById('drawerPassword'),
            passwordConfirmation: document.getElementById('drawerPasswordConfirmation'),
        };

        const openDrawer = () => {
            overlay.classList.remove('hidden');
            drawer.classList.remove('translate-x-full');
            document.body.classList.add('overflow-hidden');
        };

        const closeDrawer = () => {
            overlay.classList.add('hidden');
            drawer.classList.add('translate-x-full');
            document.body.classList.remove('overflow-hidden');
        };

        const openCreateModal = () => {
            createUserOverlay?.classList.remove('hidden');
            createUserModal?.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeCreateModal = () => {
            createUserOverlay?.classList.add('hidden');
            createUserModal?.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        const bmiCategory = (bmi) => {
            if (!bmi || Number.isNaN(Number(bmi))) return '-';
            const val = Number(bmi);
            if (val < 18.5) return 'Underweight';
            if (val < 25) return 'Ideal';
            if (val < 30) return 'Overweight';
            return 'Obesitas';
        };

        const renderAllergies = (allergies) => {
            allergyWrap.innerHTML = '';
            if (!Array.isArray(allergies) || allergies.length === 0) {
                const empty = document.createElement('span');
                empty.className = 'text-sm text-[#9c918d]';
                empty.textContent = 'Tidak ada alergi.';
                allergyWrap.appendChild(empty);
                return;
            }

            allergies.forEach((allergy) => {
                const badge = document.createElement('span');
                badge.className = 'rounded-full bg-[#f9d9d6] px-3 py-1 text-xs font-semibold text-[#d52518]';
                badge.textContent = allergy;
                allergyWrap.appendChild(badge);
            });
        };

        const fillDrawer = (user) => {
            form.action = user.update_url;

            fields.avatar.textContent = (user.name || 'U').charAt(0).toUpperCase();
            fields.nameHeading.textContent = user.name || '-';
            fields.membership.textContent = `Member sejak ${user.member_since || '-'}`;

            fields.bmiValue.textContent = user.bmi ?? '-';
            fields.bmiBadge.textContent = bmiCategory(user.bmi);
            fields.calories.textContent = user.daily_calorie_needs ? Number(user.daily_calorie_needs).toLocaleString('id-ID') : '-';

            fields.name.value = user.name || '';
            fields.email.value = user.email || '';
            fields.nickname.value = user.nickname || '';
            fields.birthDate.value = user.birth_date || '';
            fields.age.value = user.age ? `${user.age}` : '-';
            fields.gender.value = user.gender || '';
            fields.city.value = user.city || '';
            fields.province.value = user.province || '';
            fields.height.value = user.height_cm || '';
            fields.weight.value = user.weight_kg || '';
            fields.activity.value = user.activity_level || 'moderate';
            fields.onboarding.value = user.onboarding_completed ? '1' : '0';
            fields.password.value = '';
            fields.passwordConfirmation.value = '';

            renderAllergies(user.allergies);
        };

        document.querySelectorAll('[data-edit-user]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const raw = btn.getAttribute('data-edit-user');
                if (!raw) return;

                try {
                    const user = JSON.parse(raw);
                    fillDrawer(user);
                    openDrawer();
                } catch (_e) {
                    // Ignore malformed payload
                }
            });
        });

        closeBtn.addEventListener('click', closeDrawer);
        overlay.addEventListener('click', closeDrawer);

        openCreateUserModalBtn?.addEventListener('click', openCreateModal);
        closeCreateUserModalBtn?.addEventListener('click', closeCreateModal);
        createUserOverlay?.addEventListener('click', closeCreateModal);

        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeDrawer();
                closeCreateModal();
            }
        });

        document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-password-toggle');
                if (!targetId) return;

                const input = document.getElementById(targetId);
                if (!input) return;

                const nextType = input.type === 'password' ? 'text' : 'password';
                input.type = nextType;

                const openIcon = btn.querySelector('[data-icon-eye="open"]');
                const closedIcon = btn.querySelector('[data-icon-eye="closed"]');
                const isVisible = nextType === 'text';

                openIcon?.classList.toggle('hidden', isVisible);
                closedIcon?.classList.toggle('hidden', !isVisible);

                if (targetId === 'drawerPassword') {
                    btn.setAttribute('aria-label', isVisible ? 'Sembunyikan password baru' : 'Tampilkan password baru');
                } else {
                    btn.setAttribute('aria-label', isVisible ? 'Sembunyikan konfirmasi password' : 'Tampilkan konfirmasi password');
                }
            });
        });

        if (hasCreateError) {
            openCreateModal();
        }
    })();
</script>
@endpush