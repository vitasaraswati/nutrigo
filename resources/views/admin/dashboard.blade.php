@extends('layouts.admin')
@section('title','Dashboard Admin')
@section('page-title','Dashboard Admin')

@section('content')
@php
    $ideal = $bmiDistribution['ideal']['percent'];
    $over = $bmiDistribution['overweight']['percent'];
    $under = $bmiDistribution['underweight']['percent'];
@endphp

<div class="space-y-6">
    <section class="relative overflow-hidden rounded-3xl border border-[#d4e892] bg-gradient-to-r from-[#9abc05] via-[#6f9f10] to-[#185420] px-6 py-7 text-white shadow-[0_16px_30px_-16px_rgba(24,84,32,0.65)] sm:px-8">
        <div class="pointer-events-none absolute -right-8 -top-8 h-36 w-36 rounded-full bg-white/20 blur-2xl"></div>
        <div class="relative">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-white/85">Dashboard Admin NutriGo</p>
            <h2 class="mt-2 text-xl font-extrabold sm:text-2xl">Pantau aktivitas sistem dan kelola data dengan mudah</h2>
            <p class="mt-2 text-sm text-white/80">Ringkasan real-time user, konten, log konsumsi, dan status BMI pengguna.</p>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label'=>'Total User','value'=>number_format($stats['total_users']),'icon'=>'👤','card'=>'bg-white','dot'=>'bg-[#f1c926]','text'=>'text-[#185420]'],
            ['label'=>'Total Makanan','value'=>number_format($stats['total_foods']),'icon'=>'🍽️','card'=>'bg-white','dot'=>'bg-[#f96015]','text'=>'text-[#185420]'],
            ['label'=>'Total Artikel','value'=>number_format($stats['total_articles']),'icon'=>'📄','card'=>'bg-white','dot'=>'bg-[#9abc05]','text'=>'text-[#185420]'],
            ['label'=>'User Aktif Hari Ini','value'=>number_format($stats['active_today']),'icon'=>'📈','card'=>'bg-[#d52518]','dot'=>'bg-white/30','text'=>'text-white'],
        ] as $card)
            <article class="{{ $card['card'] }} rounded-2xl border border-[#eadfce] p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl {{ $card['dot'] }} text-lg">
                        {{ $card['icon'] }}
                    </span>
                    @if($card['label'] === 'User Aktif Hari Ini')
                        <span class="h-2.5 w-2.5 rounded-full bg-white"></span>
                    @endif
                </div>
                <p class="mt-5 text-sm font-medium {{ $card['text'] }} {{ $card['label'] === 'User Aktif Hari Ini' ? 'text-white/80' : 'text-[#8c9487]' }}">{{ $card['label'] }}</p>
                <p class="mt-1 text-2xl font-extrabold {{ $card['text'] }}">{{ $card['value'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-3">
        <article class="rounded-2xl border border-[#eadfce] bg-white p-6 shadow-sm xl:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <h3 class="text-sm font-bold text-[#185420]">Grafik User Baru</h3>
                <span class="rounded-lg bg-[#f7f0e4] px-3 py-1 text-xs font-semibold text-[#9a8b79]">Last 6 Months</span>
            </div>

            <div class="h-56 rounded-xl bg-[#faf8f4] p-4">
                <canvas id="monthlyUsersChart" aria-label="Grafik user baru per bulan" role="img"></canvas>
            </div>
        </article>

        <article class="rounded-2xl border border-[#eadfce] bg-white p-6 shadow-sm">
            <h3 class="text-sm font-bold text-[#185420]">Distribusi BMI</h3>

            <div class="mt-6 flex items-center justify-center">
                <div class="relative h-40 w-40">
                    <svg viewBox="0 0 36 36" class="h-40 w-40 -rotate-90">
                        <circle cx="18" cy="18" r="14.5" fill="none" stroke="#f2efe8" stroke-width="4"></circle>
                        <circle cx="18" cy="18" r="14.5" fill="none" stroke="#9abc05" stroke-width="4" stroke-dasharray="{{ $ideal }} 100" stroke-linecap="round"></circle>
                        <circle cx="18" cy="18" r="14.5" fill="none" stroke="#f1c926" stroke-width="4" stroke-dasharray="{{ $over }} 100" stroke-dashoffset="-{{ $ideal }}" stroke-linecap="round"></circle>
                        <circle cx="18" cy="18" r="14.5" fill="none" stroke="#d52518" stroke-width="4" stroke-dasharray="{{ $under }} 100" stroke-dashoffset="-{{ $ideal + $over }}" stroke-linecap="round"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <p class="text-2xl font-extrabold text-[#185420]">{{ $ideal }}%</p>
                        <p class="text-[11px] font-semibold text-[#9ca3af]">Total User Ideal</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 space-y-2 text-sm">
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-[#6b7280]"><span class="h-2.5 w-2.5 rounded-full bg-[#9abc05]"></span>Ideal</span>
                    <span class="font-semibold text-[#185420]">{{ $ideal }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-[#6b7280]"><span class="h-2.5 w-2.5 rounded-full bg-[#f1c926]"></span>Overweight</span>
                    <span class="font-semibold text-[#185420]">{{ $over }}%</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="flex items-center gap-2 text-[#6b7280]"><span class="h-2.5 w-2.5 rounded-full bg-[#d52518]"></span>Underweight</span>
                    <span class="font-semibold text-[#185420]">{{ $under }}%</span>
                </div>
            </div>
        </article>
    </section>

    <section class="rounded-2xl border border-[#eadfce] bg-white p-6 shadow-sm">
        <h3 class="text-sm font-bold text-[#185420]">Log Aktivitas Terbaru</h3>
        <div class="mt-5 space-y-4">
            @forelse($recentActivities as $activity)
                <article class="flex items-start justify-between gap-4 border-b border-[#f1ece3] pb-4 last:border-0 last:pb-0">
                    <div class="flex items-start gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full" style="background-color: {{ $activity['accent'] }}"></span>
                        <div>
                            <p class="text-sm font-semibold text-[#3f4b3f]">{{ $activity['title'] }}</p>
                            <p class="text-xs text-[#97a095]">{{ $activity['subtitle'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-[#b0b7af]">{{ $activity['time']?->diffForHumans() ?? '-' }}</span>
                </article>
            @empty
                <p class="text-sm text-[#9ca3af]">Belum ada aktivitas terbaru.</p>
            @endforelse
        </div>
    </section>

    <section class="rounded-2xl border border-[#eadfce] bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#185420]">User Terbaru</h3>
            <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-[#f96015] hover:underline">Lihat Semua</a>
        </div>

        <div class="space-y-3">
            @forelse($recentUsers as $u)
                <div class="flex items-center gap-3 rounded-xl bg-[#faf8f4] px-4 py-3">
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#f1c926] text-sm font-bold text-[#185420]">
                        {{ strtoupper(substr($u->name, 0, 1)) }}
                    </span>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-[#3f4b3f]">{{ $u->name }}</p>
                        <p class="text-xs text-[#97a095]">{{ $u->email }}</p>
                    </div>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $u->onboarding_completed ? 'bg-[#e3f1bf] text-[#185420]' : 'bg-[#ffe5d6] text-[#f96015]' }}">
                        {{ $u->onboarding_completed ? 'Aktif' : 'Setup' }}
                    </span>
                </div>
            @empty
                <p class="text-sm text-[#9ca3af]">Belum ada user baru.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    (() => {
        const canvas = document.getElementById('monthlyUsersChart');
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }

        const labels = @json($monthlyUsers->pluck('label')->values());
        const values = @json($monthlyUsers->pluck('count')->values());

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'User Baru',
                    data: values,
                    borderRadius: 12,
                    borderSkipped: false,
                    backgroundColor: ['#cde69f', '#bdda73', '#a9cb56', '#91b93a', '#72a425', '#185420'],
                    hoverBackgroundColor: '#f96015'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        displayColors: false,
                        backgroundColor: '#185420',
                        titleColor: '#f3e8cc',
                        bodyColor: '#ffffff',
                        callbacks: {
                            label: (context) => ` ${context.parsed.y} user`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { weight: 600 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#efe8dc' },
                        ticks: {
                            color: '#94a3b8',
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    })();
</script>
@endpush