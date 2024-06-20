<?php

namespace Database\Seeders;

use App\Models\PostModel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $users = User::all();

        if ($users->isEmpty()) {
            // Seed users first if no users exist
            $this->call(UsersTableSeeder::class);
            $users = User::all();
        }

        PostModel::factory()->count(10)->create([
            'user_id' => $users->random()->id,
        ]);
    }
}
