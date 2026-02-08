<?php 

/*
 * This file is part of the Cloudinary Laravel Package.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    */

    // ❌ ของเดิม: 'cloud_url' => env('CLOUDINARY_URL'),
    // ✅ ของใหม่: ใส่รหัสตรงๆ (สังเกตว่าไม่มี < > และไม่มีคำว่า CLOUDINARY_URL=)
    'cloud_url' => 'cloudinary://333297192191222:z7O07VHR_tO1TJa1VaU85Q2HeTM@daprovw5s',

    /*
    |--------------------------------------------------------------------------
    | Notification URL (อันนี้ปล่อยไว้เหมือนเดิมได้ หรือถ้าไม่ได้ใช้ก็ปล่อยว่าง)
    |--------------------------------------------------------------------------
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),

    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

];