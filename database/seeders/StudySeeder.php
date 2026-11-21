<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = \Illuminate\Support\now();

        $study = [
            [
                'id' => 1,
                'protocol_title' => '',
                'start_date' => $timestamp,
            ],
        ];

        $rows = array_map(fn (array $system) => [
            ...$system,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], $study);

        DB::table('studies')->upsert($rows, ['id']);
    }
}
