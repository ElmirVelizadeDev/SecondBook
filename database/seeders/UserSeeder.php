<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'SecondBook Admin',
                'first_name' => 'SecondBook',
                'last_name' => 'Admin',
                'username' => 'secondbook_admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'profile_photo' => null,
                'profile_visibility' => true,
                'receive_email_notifications' => true,
                'receive_order_updates' => true,
                'receive_promotional_emails' => false,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Sellers - 25
        |--------------------------------------------------------------------------
        */

        $sellers = [
            [
                'first_name' => 'Ali',
                'last_name' => 'Mammadov',
                'username' => 'seller_ali',
                'email' => 'seller.ali@secondbook.test',
                'phone' => '+994501000001',
            ],
            [
                'first_name' => 'Nigar',
                'last_name' => 'Hasanli',
                'username' => 'seller_nigar',
                'email' => 'seller.nigar@secondbook.test',
                'phone' => '+994501000002',
            ],
            [
                'first_name' => 'Rauf',
                'last_name' => 'Karimov',
                'username' => 'seller_rauf',
                'email' => 'seller.rauf@secondbook.test',
                'phone' => '+994501000003',
            ],
            [
                'first_name' => 'Aysel',
                'last_name' => 'Quliyeva',
                'username' => 'seller_aysel',
                'email' => 'seller.aysel@secondbook.test',
                'phone' => '+994501000004',
            ],
            [
                'first_name' => 'Murad',
                'last_name' => 'Aliyev',
                'username' => 'seller_murad',
                'email' => 'seller.murad@secondbook.test',
                'phone' => '+994501000005',
            ],
            [
                'first_name' => 'Leyla',
                'last_name' => 'Huseynova',
                'username' => 'seller_leyla',
                'email' => 'seller.leyla@secondbook.test',
                'phone' => '+994501000006',
            ],
            [
                'first_name' => 'Kamran',
                'last_name' => 'Ismayilov',
                'username' => 'seller_kamran',
                'email' => 'seller.kamran@secondbook.test',
                'phone' => '+994501000007',
            ],
            [
                'first_name' => 'Sabina',
                'last_name' => 'Aliyeva',
                'username' => 'seller_sabina',
                'email' => 'seller.sabina@secondbook.test',
                'phone' => '+994501000008',
            ],
            [
                'first_name' => 'Orkhan',
                'last_name' => 'Safarov',
                'username' => 'seller_orkhan',
                'email' => 'seller.orkhan@secondbook.test',
                'phone' => '+994501000009',
            ],
            [
                'first_name' => 'Zehra',
                'last_name' => 'Abbasova',
                'username' => 'seller_zehra',
                'email' => 'seller.zehra@secondbook.test',
                'phone' => '+994501000010',
            ],
            [
                'first_name' => 'Tural',
                'last_name' => 'Rahimov',
                'username' => 'seller_tural',
                'email' => 'seller.tural@secondbook.test',
                'phone' => '+994501000011',
            ],
            [
                'first_name' => 'Gunay',
                'last_name' => 'Mehdiyeva',
                'username' => 'seller_gunay',
                'email' => 'seller.gunay@secondbook.test',
                'phone' => '+994501000012',
            ],
            [
                'first_name' => 'Elvin',
                'last_name' => 'Jafarov',
                'username' => 'seller_elvin',
                'email' => 'seller.elvin@secondbook.test',
                'phone' => '+994501000013',
            ],
            [
                'first_name' => 'Narmin',
                'last_name' => 'Ismayilova',
                'username' => 'seller_narmin',
                'email' => 'seller.narmin@secondbook.test',
                'phone' => '+994501000014',
            ],
            [
                'first_name' => 'Samir',
                'last_name' => 'Hajiyev',
                'username' => 'seller_samir',
                'email' => 'seller.samir@secondbook.test',
                'phone' => '+994501000015',
            ],
            [
                'first_name' => 'Amin',
                'last_name' => 'Rustamov',
                'username' => 'seller_amin',
                'email' => 'seller.amin@secondbook.test',
                'phone' => '+994501000016',
            ],
            [
                'first_name' => 'Lamia',
                'last_name' => 'Isgandarova',
                'username' => 'seller_lamia',
                'email' => 'seller.lamia@secondbook.test',
                'phone' => '+994501000017',
            ],
            [
                'first_name' => 'Javid',
                'last_name' => 'Mammadli',
                'username' => 'seller_javid',
                'email' => 'seller.javid@secondbook.test',
                'phone' => '+994501000018',
            ],
            [
                'first_name' => 'Fidan',
                'last_name' => 'Huseynli',
                'username' => 'seller_fidan',
                'email' => 'seller.fidan@secondbook.test',
                'phone' => '+994501000019',
            ],
            [
                'first_name' => 'Eldar',
                'last_name' => 'Guliyev',
                'username' => 'seller_eldar',
                'email' => 'seller.eldar@secondbook.test',
                'phone' => '+994501000020',
            ],
            [
                'first_name' => 'Amina',
                'last_name' => 'Aliyeva',
                'username' => 'seller_amina',
                'email' => 'seller.amina@secondbook.test',
                'phone' => '+994501000021',
            ],
            [
                'first_name' => 'Farhad',
                'last_name' => 'Nabiyev',
                'username' => 'seller_farhad',
                'email' => 'seller.farhad@secondbook.test',
                'phone' => '+994501000022',
            ],
            [
                'first_name' => 'Gunel',
                'last_name' => 'Mammadova',
                'username' => 'seller_gunel',
                'email' => 'seller.gunel@secondbook.test',
                'phone' => '+994501000023',
            ],
            [
                'first_name' => 'Ramil',
                'last_name' => 'Aliyev',
                'username' => 'seller_ramil',
                'email' => 'seller.ramil@secondbook.test',
                'phone' => '+994501000024',
            ],
            [
                'first_name' => 'Sevda',
                'last_name' => 'Karimova',
                'username' => 'seller_sevda',
                'email' => 'seller.sevda@secondbook.test',
                'phone' => '+994501000025',
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
                    'password' => Hash::make('password'),
                    'role' => 'seller',
                    'status' => 'active',
                    'phone' => $seller['phone'],
                    'profile_photo' => null,
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
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'status' => 'active',
                    'profile_photo' => null,
                    'email_verified_at' => now(),
                    'profile_visibility' => true,
                    'receive_email_notifications' => true,
                    'receive_order_updates' => true,
                    'receive_promotional_emails' => false,
                ]
            );
        }

        $this->command->info(
            'Users, 25 sellers and 20 buyers seeded successfully.'
        );
    }
}
