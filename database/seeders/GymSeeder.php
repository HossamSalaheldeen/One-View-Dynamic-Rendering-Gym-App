<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        Gym::factory(10)->create();
        $gym = Gym::create([
            'name' => 'Fitness',
            'city_id' => 1,
        ]);

        $gym->cover()->create(['path' => 'default-cover.jpg']);
    }
}
