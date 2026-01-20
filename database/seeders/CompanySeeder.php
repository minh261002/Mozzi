<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::firstOrCreate([
            'code' => 'ITS',
            'name' => 'CÔNG TY TNHH CÔNG NGHỆ VÀ HỆ THỐNG THÔNG TIN TOÀN DIỆN',
            'en_name' => 'DIGITAL TECHNOLOGY & INFORMATION SYSTEMS CO., LTD',
            'short_name' => 'CÔNG TY TNHH ITS',
            'en_short_name' => 'ITS CO., LTD',
            'phone' => '0943263274',
            'legal_address' => 'Tòa nhà Vietnam Airlines, 108A Hồng Hà, Phường 2, Quận Tân Bình, Thành phố Hồ Chí Minh',
            'en_legal_address' => 'Vietnam Airlines Building, 108A Hong Ha, Ward 2, Tan Binh District, Ho Chi Minh City',
            'representative' => 'Trần Công Minh',
            'en_representative' => 'Tran Cong Minh',
            'tax_code' => '8934502598',
            'email' => 'contact@itsystem.vn',
            'website' => 'https://itsystem.vn',
            'currency' => 'VND',
            'timezone' => 'Asia/Ho_Chi_Minh',
        ]);
    }
}
