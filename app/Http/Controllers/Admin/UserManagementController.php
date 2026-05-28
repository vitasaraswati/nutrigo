<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller {

    public function index(Request $request) {
        $status = $request->string('status')->toString();

        $users = User::query()
            ->where('is_admin', false)
            ->with('allergies')
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->string('search')->toString();
                $q->where(function ($sq) use ($term) {
                    $sq->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->when($status === 'active', fn ($q) => $q->where('onboarding_completed', true))
            ->when($status === 'setup', fn ($q) => $q->where('onboarding_completed', false))
            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request) {
        $data = $request->validateWithBag(
            'createUser',
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'role' => ['required', 'in:user,admin'],
            ],
            [
                'required' => ':attribute wajib diisi.',
                'email' => 'Format :attribute tidak valid.',
                'max' => ':attribute maksimal :max karakter.',
                'min' => ':attribute minimal :min karakter.',
                'in' => ':attribute tidak valid.',
                'unique' => ':attribute sudah digunakan.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ],
            [
                'name' => 'Nama',
                'email' => 'Email',
                'password' => 'Password',
                'role' => 'Role',
            ]
        );

        $isAdmin = ($data['role'] ?? 'user') === 'admin';

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'is_admin' => $isAdmin,
            'onboarding_completed' => false,
            'onboarding_step' => 0,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User baru berhasil ditambahkan. User dapat melanjutkan pengisian profil saat login pertama.');
    }

    public function update(Request $request, User $user) {
        if ($user->isAdmin()) {
            abort(403, 'Tidak bisa mengubah data admin dari menu ini.');
        }

        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'nickname' => ['nullable', 'string', 'max:255'],
                'birth_date' => ['nullable', 'date'],
                'gender' => ['nullable', 'in:male,female'],
                'city' => ['nullable', 'string', 'max:255'],
                'province' => ['nullable', 'string', 'max:255'],
                'height_cm' => ['nullable', 'numeric', 'min:40', 'max:300'],
                'weight_kg' => ['nullable', 'numeric', 'min:10', 'max:500'],
                'activity_level' => ['nullable', 'in:sedentary,light,moderate,active,very_active'],
                'onboarding_completed' => ['nullable', 'in:0,1'],
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ],
            [
                'required' => ':attribute wajib diisi.',
                'email' => 'Format :attribute tidak valid.',
                'max' => ':attribute maksimal :max karakter.',
                'min' => ':attribute minimal :min karakter.',
                'date' => 'Format :attribute tidak valid.',
                'numeric' => ':attribute harus berupa angka.',
                'in' => ':attribute tidak valid.',
                'unique' => ':attribute sudah digunakan.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'height_cm.min' => 'Tinggi badan minimal :min cm.',
                'height_cm.max' => 'Tinggi badan maksimal :max cm.',
                'weight_kg.min' => 'Berat badan minimal :min kg.',
                'weight_kg.max' => 'Berat badan maksimal :max kg.',
            ],
            [
                'name' => 'Nama',
                'email' => 'Email',
                'nickname' => 'Nickname',
                'birth_date' => 'Tanggal lahir',
                'gender' => 'Gender',
                'city' => 'Kota',
                'province' => 'Provinsi',
                'height_cm' => 'Tinggi badan',
                'weight_kg' => 'Berat badan',
                'activity_level' => 'Tingkat aktivitas',
                'onboarding_completed' => 'Status onboarding',
                'password' => 'Password',
            ]
        );

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $data['onboarding_completed'] = ($request->input('onboarding_completed') === '1');

        if (!empty($data['height_cm']) && !empty($data['weight_kg'])) {
            $heightM = ((float) $data['height_cm']) / 100;
            if ($heightM > 0) {
                $data['bmi'] = round(((float) $data['weight_kg']) / ($heightM * $heightM), 1);
            }
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index', $request->only('search', 'status', 'page'))
            ->with('success', 'Data user berhasil diperbarui.');
    }

    public function show(User $user) {
        $user->load('allergies','medicalNeeds','foodHistories.food');
        return view('admin.users.show', compact('user'));
    }

    public function destroy(User $user) {
        if ($user->isAdmin()) abort(403, 'Tidak bisa hapus admin.');
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}