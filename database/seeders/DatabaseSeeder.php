<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Unit::create([
            'unit'  => 'pcs'
        ]);

        Category::create([
            'category'  => 'drink'
        ]);

        Product::create([
            'product_code'      => 'PRD-0001',
            'product_name'      => 'Cofee Kapal Api',
            'description'       => 'This is Cofee Kapal Api',
            'minimum_stock'     => 5,
            'category_id'       => 1,
            'unit_id'           => 1
        ]);
        Product::create([
            'product_code'      => 'PRD-0002',
            'product_name'      => 'Cofee Cap Cangkir',
            'description'       => 'This is Cofee Cap Cangkir',
            'minimum_stock'     => 5,
            'category_id'       => 1,
            'unit_id'           => 1
        ]);

        Role::create([
            'role'  => 'Superadmin'
        ]);
        Role::create([
            'role'  => 'Kepala Gudang'
        ]);
        Role::create([
            'role'  => 'Admin Gudang'
        ]);

        User::create([
            'name'      => 'Superadmin',
            'username'  => 'superadmin',
            'password'  => Hash::Make('1234'),
            'role_id'   => 1
        ]);
        User::create([
            'name'      => 'Kepala Gudang',
            'username'  => 'kepalagudang',
            'password'  => Hash::Make('1234'),
            'role_id'   => 2
        ]);
        User::create([
            'name'      => 'Admin',
            'username'  => 'admin',
            'password'  => Hash::Make('1234'),
            'role_id'   => 3
        ]);

        Customer::create([
            'customer'  => "CV Berniaga Indonesia",
            'address'   => "Sleman, Yogyakarta"
        ]);
        Customer::create([
            'customer'  => "PT Sumber Agung",
            'address'   => "Purwodadi, Purworejo"
        ]);

        Supplier::create([
            'supplier'  => "PT Indofood CBP Sukses Makmur",
            'address'   => "Bekasi, Jawa Barat"
        ]);
        Supplier::create([
            'supplier'  => "PT Wings Food",
            'address'   => "Karawang, Jawa Barat"
        ]);
    }
}