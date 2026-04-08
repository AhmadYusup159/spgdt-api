<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['ambulance', 'emergency', 'fire', 'police', 'medical'];
        $statuses = ['pending', 'in-progress', 'completed', 'cancelled'];
        $cities = ['Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang', 'Yogyakarta'];

        $reports = [];

        for ($i = 1; $i <= 20; $i++) {
            $reports[] = [
                'type' => $types[array_rand($types)],
                'status' => $statuses[array_rand($statuses)],
                'city' => $cities[array_rand($cities)],
                'date' => now()->subDays(rand(0, 30))->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Report::insert($reports);
    }
}
