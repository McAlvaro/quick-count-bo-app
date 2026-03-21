<?php

namespace Database\Seeders;

use App\Models\CandidateType;
use Illuminate\Database\Seeder;

class CandidateTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['GOBERNADOR', 'ALCALDE'];

        foreach ($types as $type) {
            CandidateType::firstOrCreate(['name' => $type]);
        }
    }
}
