<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@unsulbar.ac.id'],
            [
                'name' => 'Admin PTI',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        // 2. Dosen (3 orang)
        $dosenNames = ['Dr. Budi Santoso, M.Kom', 'Siti Aminah, S.Kom., M.T.', 'Andi Rahman, M.Cs.'];
        foreach ($dosenNames as $index => $name) {
            $dosen = User::firstOrCreate(
                ['email' => 'dosen' . ($index + 1) . '@unsulbar.ac.id'],
                [
                    'name' => $name,
                    'nim' => '19800101200501100' . ($index + 1), // NIDN dummy
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $dosen->assignRole('dosen');
        }

        // 3. Mahasiswa (20 orang)
        for ($i = 1; $i <= 20; $i++) {
            $nim = 'D022' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $mhs = User::firstOrCreate(
                ['email' => "mhs{$i}@unsulbar.ac.id"],
                [
                    'name' => "Mahasiswa PTI {$i}",
                    'nim' => $nim,
                    'angkatan' => 2022,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $mhs->assignRole('mahasiswa');
        }
    }
}
