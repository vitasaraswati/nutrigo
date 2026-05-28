<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Food;
use App\Models\FoodHistory;
use App\Models\Article;
use Carbon\Carbon;

class DashboardController extends Controller {

    public function index() {
        $userQuery = User::where('is_admin', false);

        $stats = [
            'total_users'    => (clone $userQuery)->count(),
            'total_foods'    => Food::count(),
            'total_articles' => Article::count(),
            'total_logs'     => FoodHistory::whereDate('created_at', today())->count(),
            'active_today'   => (clone $userQuery)->whereDate('last_activity', today())->count(),
        ];

        $recentUsers = (clone $userQuery)->latest()->take(5)->get();

        $now = now();
        $monthlyUsers = collect(range(5, 0))->map(function ($monthOffset) use ($now, $userQuery) {
            $month = $now->copy()->subMonths($monthOffset);
            $start = $month->copy()->startOfMonth();
            $end = $month->copy()->endOfMonth();

            return [
                'label' => $month->format('M'),
                'count' => (clone $userQuery)->whereBetween('created_at', [$start, $end])->count(),
            ];
        });

        $peakMonthly = max(1, $monthlyUsers->max('count'));
        $monthlyUsers = $monthlyUsers->map(fn ($item) => [
            'label' => $item['label'],
            'count' => $item['count'],
            'height' => (int) round(($item['count'] / $peakMonthly) * 100),
        ]);

        $idealCount = (clone $userQuery)->whereBetween('bmi', [18.5, 24.9])->count();
        $overweightCount = (clone $userQuery)->where('bmi', '>=', 25)->count();
        $underweightCount = (clone $userQuery)->whereNotNull('bmi')->where('bmi', '<', 18.5)->count();
        $totalBmi = max(1, $idealCount + $overweightCount + $underweightCount);

        $bmiDistribution = [
            'ideal' => [
                'count' => $idealCount,
                'percent' => (int) round(($idealCount / $totalBmi) * 100),
            ],
            'overweight' => [
                'count' => $overweightCount,
                'percent' => (int) round(($overweightCount / $totalBmi) * 100),
            ],
            'underweight' => [
                'count' => $underweightCount,
                'percent' => (int) round(($underweightCount / $totalBmi) * 100),
            ],
        ];

        $recentActivities = collect();

        $userActivities = (clone $userQuery)
            ->latest()
            ->take(3)
            ->get()
            ->map(fn ($user) => [
                'title' => "User baru bergabung: {$user->name}",
                'subtitle' => 'Menyelesaikan registrasi profil',
                'time' => $user->created_at,
                'accent' => '#9abc05',
                'icon' => 'user',
            ]);

        $articleActivities = Article::latest()
            ->take(2)
            ->get()
            ->map(fn ($article) => [
                'title' => 'Artikel baru dipublikasikan',
                'subtitle' => str($article->title)->limit(40),
                'time' => $article->created_at,
                'accent' => '#f1c926',
                'icon' => 'article',
            ]);

        $foodActivities = Food::latest('updated_at')
            ->take(2)
            ->get()
            ->map(fn ($food) => [
                'title' => 'Data makanan diperbarui',
                'subtitle' => "Update kalori pada item '{$food->name}'",
                'time' => $food->updated_at,
                'accent' => '#d52518',
                'icon' => 'food',
            ]);

        $recentActivities = $recentActivities
            ->merge($userActivities)
            ->merge($articleActivities)
            ->merge($foodActivities)
            ->sortByDesc(fn ($activity) => $activity['time'] ?? Carbon::now())
            ->take(6)
            ->values();

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'monthlyUsers',
            'bmiDistribution',
            'recentActivities'
        ));
    }
}