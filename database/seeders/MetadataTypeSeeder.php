<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetadataTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = \Illuminate\Support\now();

        $types = [
            [
                'id' => 1,
                'display' => 'Text Field',
                'value' => 'Text',
            ],
            [
                'id' => 2,
                'display' => 'Date Field',
                'value' => 'Date',
            ],
            [
                'id' => 3,
                'display' => 'Text Area',
                'value' => 'TextArea',
            ],
            [
                'id' => 4,
                'display' => 'Select',
                'value' => 'Select',
            ],
            [
                'id' => 5,
                'display' => 'Checkbox',
                'value' => 'Checkbox',
            ],
        ];

        $rows = array_map(fn (array $system) => [
            ...$system,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ], $types);

        DB::table('metadata_types')->upsert($rows, ['id']);
    }
}
