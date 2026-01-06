<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VillaSeeder extends Seeder
{
    public function run()
    {
        // Create sample villas
        $now = date('Y-m-d H:i:s');

        $villas = [
            [
                'name' => 'Villa Kota Bunga Ade',
                'slug' => Str::slug('Villa Kota Bunga Ade'),
                'capacity' => 6,
                'base_price' => 5104000,
                'rooms_total' => 3,
                'description' => 'Comfortable villa close to the city with garden and pool.',
                'status' => 'active',
                'thumbnail_path' => null,
                'images' => json_encode([]),
                'closed_dates' => json_encode([]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Villa Puncak Harmony',
                'slug' => Str::slug('Villa Puncak Harmony'),
                'capacity' => 8,
                'base_price' => 7200000,
                'rooms_total' => 4,
                'description' => 'Spacious villa with panoramic mountain views.',
                'status' => 'active',
                'thumbnail_path' => null,
                'images' => json_encode([]),
                'closed_dates' => json_encode([]),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($villas as $v) {
            $villa = \DB::table('villas')->where('slug', $v['slug'])->first();
            if (!$villa) {
                $villaId = \DB::table('villas')->insertGetId($v);

                // add a room type
                $roomTypeId = \DB::table('villa_room_types')->insertGetId([
                    'villa_id' => $villaId,
                    'name' => 'Standard',
                    'capacity' => $v['capacity'],
                    'price' => $v['base_price'],
                    'rooms_count' => $v['rooms_total'],
                    'amenities' => 'wifi,pool,parking',
                    'description' => 'Standard room type',
                    'status' => 'available',
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // image
                \DB::table('villa_images')->insert([
                    'villa_id' => $villaId,
                    'url' => '/storage/villa/sample-' . $villaId . '.jpg',
                    'alt' => $v['name'] . ' photo',
                    'is_primary' => 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // mark a few availabilities for next 7 days
                for ($d = 0; $d < 7; $d++) {
                    $date = date('Y-m-d', strtotime("+{$d} days"));
                    \DB::table('room_type_availabilities')->insert([
                        'villa_room_type_id' => $roomTypeId,
                        'date' => $date,
                        'available_count' => 3,
                        'price_override' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        // Create a sample booking for user id 1 if exists
        $user = \DB::table('users')->first();
        if ($user) {
            $firstVilla = \DB::table('villas')->first();
            $firstRoomType = \DB::table('villa_room_types')->where('villa_id', $firstVilla->id)->first();
            if ($firstVilla && $firstRoomType) {
                $existingBooking = \DB::table('bookings')->where('user_id', $user->id)->first();
                if (!$existingBooking) {
                    \DB::table('bookings')->insert([
                        'user_id' => $user->id,
                        'villa_id' => $firstVilla->id,
                        'villa_room_type_id' => $firstRoomType->id,
                        'booking_code' => 'BK' . time(),
                        'check_in_date' => date('Y-m-d', strtotime('+1 day')),
                        'check_out_date' => date('Y-m-d', strtotime('+3 days')),
                        'nights' => 2,
                        'rooms_booked' => 1,
                        'guests' => 2,
                        'total_price' => $firstVilla->base_price,
                        'status' => 'pending',
                        'is_verified' => 0,
                        'payment_status' => 'unpaid',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
