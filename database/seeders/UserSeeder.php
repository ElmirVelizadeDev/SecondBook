<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Admin
        |--------------------------------------------------------------------------
        */

        User::updateOrCreate(
            ['email' => 'admin@secondbook.test'],
            [
                'name' => 'SecondBook Admin',
                'first_name' => 'SecondBook',
                'last_name' => 'Admin',
                'username' => 'admin',
                'password' => 'password',
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'profile_visibility' => true,
                'receive_email_notifications' => true,
                'receive_order_updates' => true,
                'receive_promotional_emails' => false,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Sellers - 15
        |--------------------------------------------------------------------------
        */

        $sellers = [
            [
                'first_name' => 'Ali',
                'last_name' => 'Mammadov',
                'username' => 'seller_ali',
                'email' => 'seller.ali@secondbook.test',
                'phone' => '+994501000001',
                'photo' => 'https://randomuser.me/api/portraits/men/32.jpg',
            ],
            [
                'first_name' => 'Nigar',
                'last_name' => 'Hasanli',
                'username' => 'seller_nigar',
                'email' => 'seller.nigar@secondbook.test',
                'phone' => '+994501000002',
                'photo' => 'https://randomuser.me/api/portraits/women/44.jpg',
            ],
            [
                'first_name' => 'Rauf',
                'last_name' => 'Karimov',
                'username' => 'seller_rauf',
                'email' => 'seller.rauf@secondbook.test',
                'phone' => '+994501000003',
                'photo' => 'https://randomuser.me/api/portraits/men/46.jpg',
            ],
            [
                'first_name' => 'Aysel',
                'last_name' => 'Quliyeva',
                'username' => 'seller_aysel',
                'email' => 'seller.aysel@secondbook.test',
                'phone' => '+994501000004',
                'photo' => 'https://randomuser.me/api/portraits/women/65.jpg',
            ],
            [
                'first_name' => 'Murad',
                'last_name' => 'Aliyev',
                'username' => 'seller_murad',
                'email' => 'seller.murad@secondbook.test',
                'phone' => '+994501000005',
                'photo' => 'https://randomuser.me/api/portraits/men/75.jpg',
            ],
            [
                'first_name' => 'Leyla',
                'last_name' => 'Huseynova',
                'username' => 'seller_leyla',
                'email' => 'seller.leyla@secondbook.test',
                'phone' => '+994501000006',
                'photo' => 'https://randomuser.me/api/portraits/women/68.jpg',
            ],
            [
                'first_name' => 'Kamran',
                'last_name' => 'Ismayilov',
                'username' => 'seller_kamran',
                'email' => 'seller.kamran@secondbook.test',
                'phone' => '+994501000007',
                'photo' => 'https://randomuser.me/api/portraits/men/52.jpg',
            ],
            [
                'first_name' => 'Sabina',
                'last_name' => 'Aliyeva',
                'username' => 'seller_sabina',
                'email' => 'seller.sabina@secondbook.test',
                'phone' => '+994501000008',
                'photo' => 'https://randomuser.me/api/portraits/women/49.jpg',
            ],
            [
                'first_name' => 'Orkhan',
                'last_name' => 'Safarov',
                'username' => 'seller_orkhan',
                'email' => 'seller.orkhan@secondbook.test',
                'phone' => '+994501000009',
                'photo' => 'https://randomuser.me/api/portraits/men/41.jpg',
            ],
            [
                'first_name' => 'Zehra',
                'last_name' => 'Abbasova',
                'username' => 'seller_zehra',
                'email' => 'seller.zehra@secondbook.test',
                'phone' => '+994501000010',
                'photo' => 'https://randomuser.me/api/portraits/women/33.jpg',
            ],
            [
                'first_name' => 'Tural',
                'last_name' => 'Rahimov',
                'username' => 'seller_tural',
                'email' => 'seller.tural@secondbook.test',
                'phone' => '+994501000011',
                'photo' => 'https://randomuser.me/api/portraits/men/36.jpg',
            ],
            [
                'first_name' => 'Gunay',
                'last_name' => 'Mehdiyeva',
                'username' => 'seller_gunay',
                'email' => 'seller.gunay@secondbook.test',
                'phone' => '+994501000012',
                'photo' => 'https://randomuser.me/api/portraits/women/24.jpg',
            ],
            [
                'first_name' => 'Elvin',
                'last_name' => 'Jafarov',
                'username' => 'seller_elvin',
                'email' => 'seller.elvin@secondbook.test',
                'phone' => '+994501000013',
                'photo' => 'https://randomuser.me/api/portraits/men/22.jpg',
            ],
            [
                'first_name' => 'Narmin',
                'last_name' => 'Ismayilova',
                'username' => 'seller_narmin',
                'email' => 'seller.narmin@secondbook.test',
                'phone' => '+994501000014',
                'photo' => 'https://randomuser.me/api/portraits/women/29.jpg',
            ],
            [
                'first_name' => 'Samir',
                'last_name' => 'Hajiyev',
                'username' => 'seller_samir',
                'email' => 'seller.samir@secondbook.test',
                'phone' => '+994501000015',
                'photo' => 'https://randomuser.me/api/portraits/men/61.jpg',
            ],
        ];

        foreach ($sellers as $seller) {
            User::updateOrCreate(
                ['email' => $seller['email']],
                [
                    'name' => $seller['first_name'] . ' ' . $seller['last_name'],
                    'first_name' => $seller['first_name'],
                    'last_name' => $seller['last_name'],
                    'username' => $seller['username'],
                    'password' => 'password',
                    'role' => 'seller',
                    'status' => 'active',
                    'phone' => $seller['phone'],
                    'profile_photo' => $seller['photo'],
                    'email_verified_at' => now(),
                    'profile_visibility' => true,
                    'receive_email_notifications' => true,
                    'receive_order_updates' => true,
                    'receive_promotional_emails' => false,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Buyers - 20
        |--------------------------------------------------------------------------
        */

        $buyers = [
            ['Elvin', 'Aliyev', 'elvin_aliyev', 'elvin@example.com'],
            ['Aysel', 'Mammadova', 'aysel_mammadova', 'aysel@example.com'],
            ['Murad', 'Hasanov', 'murad_hasanov', 'murad@example.com'],
            ['Nigar', 'Rahimova', 'nigar_rahimova', 'nigar@example.com'],
            ['Tural', 'Karimov', 'tural_karimov', 'tural@example.com'],
            ['Leyla', 'Huseynova', 'leyla_huseynova', 'leyla@example.com'],
            ['Kamran', 'Ismayilov', 'kamran_ismayilov', 'kamran@example.com'],
            ['Sabina', 'Aliyeva', 'sabina_aliyeva', 'sabina@example.com'],
            ['Orkhan', 'Safarov', 'orkhan_safarov', 'orkhan@example.com'],
            ['Zehra', 'Abbasova', 'zehra_abbasova', 'zehra@example.com'],
            ['Rashad', 'Mammadli', 'rashad_mammadli', 'rashad@example.com'],
            ['Gunay', 'Aliyeva', 'gunay_aliyeva', 'gunay@example.com'],
            ['Nurlan', 'Huseynov', 'nurlan_huseynov', 'nurlan@example.com'],
            ['Amina', 'Karimova', 'amina_karimova', 'amina@example.com'],
            ['Farid', 'Jafarov', 'farid_jafarov', 'farid@example.com'],
            ['Lala', 'Mehdiyeva', 'lala_mehdiyeva', 'lala@example.com'],
            ['Emin', 'Ismayilov', 'emin_ismayilov', 'emin@example.com'],
            ['Arzu', 'Hajiyeva', 'arzu_hajiyeva', 'arzu@example.com'],
            ['Vusal', 'Rahmanov', 'vusal_rahmanov', 'vusal@example.com'],
            ['Diana', 'Aliyeva', 'diana_aliyeva', 'diana@example.com'],
        ];

        foreach ($buyers as $buyer) {
            User::updateOrCreate(
                ['email' => $buyer[3]],
                [
                    'name' => $buyer[0] . ' ' . $buyer[1],
                    'first_name' => $buyer[0],
                    'last_name' => $buyer[1],
                    'username' => $buyer[2],
                    'password' => 'password',
                    'role' => 'user',
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'profile_visibility' => true,
                    'receive_email_notifications' => true,
                    'receive_order_updates' => true,
                    'receive_promotional_emails' => false,
                ]
            );
        }

        $this->command->info(
            'Users, 15 sellers and 20 buyers seeded successfully.'
        );
    }
}

