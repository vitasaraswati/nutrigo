@extends('layouts.admin')
@section('title','Artikel')
@section('page-title','Kelola Artikel')

@section('content')
@php
    $hasActiveFilter = request()->filled('search') || request()->filled('category') || request()->filled('status');
@endphp

<section class="mb-5 overflow-hidden rounded-3xl border border-[#d4e892] bg-gradient-to-r from-[#9abc05] via-[#779f11] to-[#6a8d0d] px-8 py-6 text-white shadow-[0_14px_28px_-16px_rgba(24,84,32,0.6)]">
    <h2 class="text-4xl font-extrabold tracking-tight text-[#f1f8ca]">Kelola Artikel</h2>
    <p class="mt-1 text-sm text-[#e7f5bf]">Atur konten artikel, kategori, dan status publikasi</p>
</section>

<section class="mb-5 rounded-2xl border border-[#eadfce] bg-white p-4 shadow-sm">
    <form method="GET" class="flex flex-col gap-3 lg:flex-row lg:items-center">
        <div class="flex-[1.2]">
            <div class="relative">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari judul atau ringkasan..."
                    class="w-full rounded-full border border-[#f3dede] bg-[#fbefef] px-4 py-3 pr-12 text-sm text-[#8d8d8d] outline-none ring-[#f96015] transition focus:ring-2 lg:max-w-xl"
                >
                @if(request('search'))
                    <a href="{{ route('admin.articles.index', request()->except('search')) }}" class="absolute right-1 top-1/2 inline-flex h-6 w-6 -translate-y-1/2 items-center justify-center rounded-full border border-[#e7dfd5] bg-white text-[#a59c94] transition hover:border-[#d52518] hover:text-[#d52518]" title="Hapus pencarian">
                        <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-2 lg:flex-nowrap">
            <div class="relative">
                <select name="category" class="rounded-full border border-[#e7dfd5] bg-[#f7f0e4] py-3 pl-4 pr-28 text-sm font-medium text-[#6b7280] outline-none ring-[#9abc05] focus:ring-2">
                    <option value="">Semua Kategori</option>
                    @foreach(['nutrisi'=>'Nutrisi','lifestyle'=>'Lifestyle','resep'=>'Resep','kesehatan'=>'Kesehatan'] as $value => $label)
                        <option value="{{ $value }}" {{ request('category') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <div class="absolute inset-y-0 right-1 flex items-center gap-1.5">
                    <button type="submit" class="rounded-full bg-[#f96015] px-3 py-1.5 text-xs font-bold text-white transition hover:bg-[#e55310]">Filter</button>

                    @if($hasActiveFilter)
                        <a href="{{ route('admin.articles.index') }}" class="inline-flex h-6 w-6 items-center justify-center rounded-full border border-[#e7dfd5] bg-white text-[#a59c94] transition hover:border-[#d52518] hover:text-[#d52518]" title="Hapus filter">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18"></path><path d="M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </div>

            <div class="relative">
                <select name="status" class="rounded-full border border-[#e7dfd5] bg-[#f7f0e4] py-3 pl-4 pr-28 text-sm font-medium text-[#6b7280] outline-none ring-[#9abc05] focus:ring-2">
                    <option value="">Semua Status</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>

        <div class="lg:ml-auto">
            <a href="{{ route('admin.articles.create') }}" class="rounded-full bg-[#185420] px-4 py-3 text-sm font-bold text-white transition hover:bg-[#123b18]">
                + Tulis Artikel
            </a>
        </div>
    </form>
</section>

<section class="overflow-hidden rounded-3xl border border-[#eadfce] bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full text-left text-sm">
            <thead class="border-b border-[#ecd8d4] bg-[#fbf7f2] text-xs uppercase tracking-wider text-[#9e847e]">
                <tr>
                    <th class="px-6 py-4">Artikel</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Penulis</th>
                    <th class="px-6 py-4">Baca</th>
                    <th class="px-6 py-4">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#f2e8e3]">
                @forelse($articles as $article)
                    <tr class="hover:bg-[#fffdfa]">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-xl border border-[#eadfce] bg-[#fbf7f2] text-xs font-bold text-[#9e847e]">
                                    @if($article->image)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($article->image) }}" alt="{{ $article->title }}" class="h-full w-full object-cover">
                                    @else
                                        <span>ART</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-[#3f3a39]">{{ $article->title }}</p>
                                    <p class="mt-1 max-w-xl truncate text-xs text-[#8a7f78]">{{ $article->excerpt ?: 'Tidak ada ringkasan' }}</p>
                                    <p class="mt-1 text-xs text-[#b1a9a3]">{{ $article->created_at?->isoFormat('D MMM YYYY') }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="rounded-full bg-[#e7efd5] px-3 py-1 text-xs font-semibold text-[#7a9708]">{{ ucfirst($article->category) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($article->is_published)
                                <span class="rounded-full bg-[#e7efd5] px-3 py-1 text-xs font-semibold text-[#7a9708]">Published</span>
                            @else
                                <span class="rounded-full bg-[#fde6e6] px-3 py-1 text-xs font-semibold text-[#d52518]">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-[#57534e]">{{ $article->author?->name ?? '—' }}</td>
                        <td class="px-6 py-4 text-[#57534e]">{{ $article->read_time }} menit</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.articles.edit', $article) }}" class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-[#f96015] text-[#f96015] transition hover:bg-[#fff1ea]" title="Edit artikel">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>
                                </a>

                                <form method="POST" action="{{ route('admin.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')" class="inline-flex">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#d52518] text-white transition hover:bg-[#bf1f14]" title="Hapus artikel">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6l-1 14H6L5 6"></path><path d="M10 11v6"></path><path d="M14 11v6"></path><path d="M9 6V4h6v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-sm text-[#b1a9a3]">Tidak ada artikel ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-col gap-3 border-t border-[#f2e8e3] px-6 py-4 text-sm text-[#766f69] md:flex-row md:items-center md:justify-between">
        <p>
            Menampilkan {{ $articles->firstItem() ?? 0 }}-{{ $articles->lastItem() ?? 0 }} dari {{ $articles->total() }} artikel
        </p>
        <div>{{ $articles->withQueryString()->links() }}</div>
    </div>
</section>
@endsection