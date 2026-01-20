<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BoardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrCreate(
            ['email' => 'johndoe@gmail.com'],
            [
                'name' => 'John Doe',
                'password' => bcrypt('password'), // Default password
            ]
        );

        $boardsData = File::get(database_path('seeders/data/board.json'));
        $boards = json_decode($boardsData, true);

        foreach ($boards as $boardData) {
            $tasks = $boardData['tasks'] ?? [];
            unset($boardData['tasks']); // Remove tasks from board data

            $board = $user->boards()->create($boardData);

            foreach ($tasks as $taskData) {
                $board->tasks()->create($taskData);
            }
        }
    }
}