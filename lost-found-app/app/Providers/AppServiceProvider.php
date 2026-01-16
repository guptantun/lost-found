<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 👈 1. เพิ่มบรรทัดนี้ที่ด้านบนสุด (ใต้ namespace)

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 👇 2. เพิ่มก้อนนี้ลงไปในฟังก์ชัน boot
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}