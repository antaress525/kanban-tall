<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Board;

class TestNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1️⃣ Création de John
        $john = User::create([
            'name' => 'John Doe',
            'email' => 'john@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // 2️⃣ Création des 5 utilisateurs
        $users = collect([
            'Alice Martin',
            'David Bernard',
            'Sophie Leroy',
            'Lucas Moreau',
            'Emma Petit',
        ])->map(function ($name) {
            return User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@gmail.com',
                'password' => Hash::make('password'),
            ]);
        });

        // 3️⃣ Création des 3 boards pour John
        $boards = collect([
            'Product Roadmap 2026',
            'Marketing Campaign Q1',
            'Mobile App Redesign',
        ])->map(function ($title) use ($john) {
            return Board::create([
                'name' => $title,
                'user_id' => $john->id,
            ]);
        });

        // 4️⃣ Attacher les 5 utilisateurs comme membres
        foreach ($boards as $board) {
            $board->members()->attach($users->pluck('id'));
        }
    }
}
