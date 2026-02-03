<?php

/*
 * This file is part of the Cloudinary Laravel Package.
 *
 * (c) Cloudinary <support@cloudinary.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud
    | service that offers a solution to a web application's entire image
    | management pipeline.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud
    | service that offers a solution to a web application's entire image
    | management pipeline.
    |
    */

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary Upload settings.
    |
    */

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),

    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

];