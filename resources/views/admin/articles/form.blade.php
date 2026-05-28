@extends('layouts.admin')
@section('title', isset($article) ? 'Edit Artikel' : 'Tulis Artikel')
@section('page-title', isset($article) ? 'Edit Artikel' : 'Tulis Artikel Baru')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ isset($article) ? route('admin.articles.update', $article) : route('admin.articles.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($article)) @method('PUT') @endif

        <div class="space-y-5 rounded-3xl border border-[#eadfce] bg-white p-6 shadow-sm">
            <div class="grid gap-5 lg:grid-cols-2">
                <div class="lg:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Judul Artikel *</label>
                    <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" placeholder="Judul yang menarik..." required>
                    @error('title')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Ringkasan</label>
                    <textarea name="excerpt" rows="3" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" placeholder="Ringkasan singkat artikel...">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-[#998e88]">Ringkasan tampil di daftar artikel dan preview konten.</p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Kategori *</label>
                    <select name="category" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" required>
                        @foreach(['nutrisi'=>'Nutrisi','lifestyle'=>'Lifestyle','resep'=>'Resep','kesehatan'=>'Kesehatan'] as $value => $label)
                            <option value="{{ $value }}" {{ old('category', $article->category ?? 'nutrisi') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('category')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Waktu Baca (menit)</label>
                    <input type="number" name="read_time" value="{{ old('read_time', $article->read_time ?? 3) }}" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" min="1" max="60">
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Gambar Artikel</label>
                    <input type="file" name="image" accept="image/*" class="w-full rounded-xl border border-dashed border-[#eadfce] bg-[#fbf7f2] px-3 py-3 text-sm text-[#7f746d] file:mr-4 file:rounded-lg file:border-0 file:bg-[#185420] file:px-4 file:py-2 file:text-sm file:font-bold file:text-white">
                    <p class="mt-1 text-xs text-[#998e88]">Opsional. File akan disimpan ke storage publik.</p>
                    @if(isset($article) && $article->image)
                        <div class="mt-3 flex items-center gap-3 rounded-2xl border border-[#eadfce] bg-[#fffdfa] p-3">
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($article->image) }}" alt="{{ $article->title }}" class="h-16 w-16 rounded-xl object-cover">
                            <div>
                                <p class="text-sm font-semibold text-[#3f3a39]">Gambar saat ini</p>
                                <p class="text-xs text-[#8a7f78]">Upload gambar baru untuk menggantinya.</p>
                            </div>
                        </div>
                    @endif
                    @error('image')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                <div class="lg:col-span-2">
                    <label class="mb-1 block text-sm font-semibold text-[#8b7d78]">Isi Artikel *</label>
                    <textarea name="content" id="content" rows="15" class="w-full rounded-xl border border-[#eadfce] px-3 py-2 text-sm outline-none ring-[#9abc05] focus:ring-2" placeholder="Tulis artikel di sini..." required>{{ old('content', $article->content ?? '') }}</textarea>
                    @error('content')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-[#f2e8e3] pt-4">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_published" value="1"
                        {{ old('is_published', $article->is_published ?? true) ? 'checked' : '' }}
                        class="h-5 w-5 rounded text-[#185420]">
                    <span class="text-sm text-[#57534e]">Publikasikan sekarang</span>
                </label>
            </div>

            <div class="flex gap-3 justify-end pt-2 border-t border-[#f2e8e3]">
                <a href="{{ route('admin.articles.index') }}" class="rounded-xl border border-[#eadfce] px-4 py-2 text-sm font-bold text-[#5f4540] transition hover:bg-[#f7f0e4]">Batal</a>
                <button class="rounded-xl bg-[#f96015] px-4 py-2 text-sm font-bold text-white transition hover:bg-[#e55310]">
                    {{ isset($article) ? 'Simpan Perubahan' : 'Publikasikan' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection