<?php

namespace Database\Seeders;

use App\Models\Job;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = [
            'fullstack',
            'frontend',
            'backend'
        ];

        foreach($jobs as $job){
            Job::create([
                'name' => $job
            ]);
        }
    }
}
