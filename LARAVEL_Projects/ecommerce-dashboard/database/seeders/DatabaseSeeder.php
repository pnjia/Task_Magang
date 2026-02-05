<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $tenant = Tenant::create([
            'name' => 'Toko Komputer',
            'slug' => 'toko-komputer',
        ]);

        User::factory()->create([
            'name' => 'Admin Owner',
            'email' => 'admin@komputer.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'tenant_id' => $tenant->id,
        ]);

        User::factory(3)->create([
            'role' => 'staff',
            'tenant_id' => $tenant->id,
        ]);


        Category::factory(5)->state(['tenant_id' => $tenant->id])->has(Product::factory()->count(5)->state(['tenant_id' => $tenant->id]))->create();

        $this->command->info('Database berhasil diisi!');
        $this->command->info('Login: admin@computer.com / password');
        $this->command->info('Total: 1 Toko, 4 User, 5 Kategori, 25 Produk');
    }
}
