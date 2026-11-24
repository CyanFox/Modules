<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Exceptions\SettingNotFoundException;

class CoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws SettingNotFoundException
     */
    public function run(): void
    {
        $this->call([
            SettingsSeeder::class,
            PermissionsSeeder::class,
        ]);
    }
}
