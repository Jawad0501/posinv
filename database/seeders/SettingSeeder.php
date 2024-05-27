<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['key' => 'app_title', 'value' => 'POSINV'],
            ['key' => 'app_description', 'value' => 'Ultimate solution for POS and Inventory Management'],
            ['key' => 'email', 'value' => 'contact.lilliputdigital@gmail.com'],
            ['key' => 'phone', 'value' => '+880 1234 567890'],
            ['key' => 'light_logo', 'value' => 'setting/light_logo.png'],
            ['key' => 'dark_logo', 'value' => 'setting/dark_logo.png'],
            ['key' => 'favicon', 'value' => 'setting/favicon.png'],
            ['key' => 'invoice_logo', 'value' => 'setting/dark_logo.png'],
            ['key' => 'default_image', 'value' => 'setting/default-image.jpg'],
            ['key' => 'timezone', 'value' => 'Asia/Dhaka'],
            ['key' => 'date_format', 'value' => 'd-m-Y'],
            ['key' => 'currency_symbol', 'value' => 'AED'],
            ['key' => 'currency_code', 'value' => 'AED'],
            ['key' => 'currency_position', 'value' => 'After Amount'],
            ['key' => 'service_charge', 'value' => 0.00],
            ['key' => 'delivery_charge', 'value' => 0.00],
            ['key' => 'cash_in_hand', 'value' => 0.00],
            ['key' => 'address', 'value' => 'Address...'],
            ['key' => 'copyright', 'value' => 'All rights reserved @ Lilliput Digital'],
            ['key' => 'supplier_upload_file_sample', 'value' => 'supplier_upload_file_sample.xlxs'],
            ['key' => 'ingredient_upload_file_sample', 'value' => 'ingredient_upload_file_sample.xlxs'],
            ['key' => 'opening_purchase_stock', 'value' => 20],
            ['key' => 'opening_sale_stock', 'value' => 20],
            ['key' => 'mail', 'value' => json_encode([
                'mailer' => 'smtp',
                'encryption' => 'tls',
                'port' => '2525',
                'host' => 'smtp.mailtrap.io',
                'username' => 'abunowshadmdjawad@gmail.com',
                'password' => 'ljgzxjkdwzxdwlqo',
                'from_address' => 'contact.lilliputdigital@gmail.com',
                'from_name' => 'Lilliput Digital',
            ])],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], ['value' => $setting['value']]);
        }

        \Illuminate\Support\Facades\File::copyDirectory(database_path('fakes/setting'), storage_path('app/public/setting'));
    }
}
