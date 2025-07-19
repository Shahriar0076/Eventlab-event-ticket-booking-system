<?php

namespace App\Constants;

class FileInfo
{

    /*
    |--------------------------------------------------------------------------
    | File Information
    |--------------------------------------------------------------------------
    |
    | This class basically contain the path of files and size of images.
    | All information are stored as an array. Developer will be able to access
    | this info as method and property using FileManager class.
    |
    */

    public function fileInfo()
    {
        $data['withdrawVerify'] = [
            'path' => 'assets/images/verify/withdraw'
        ];
        $data['depositVerify'] = [
            'path'      => 'assets/images/verify/deposit'
        ];
        $data['verify'] = [
            'path'      => 'assets/verify'
        ];
        $data['default'] = [
            'path'      => 'assets/images/default.png',
        ];
        $data['withdrawMethod'] = [
            'path'      => 'assets/images/withdraw/method',
            'size'      => '800x800',
        ];
        $data['ticket'] = [
            'path'      => 'assets/support',
        ];
        $data['logo_icon'] = [
            'path'      => 'assets/images/logo_icon',
        ];
        $data['favicon'] = [
            'size'      => '128x128',
        ];
        $data['extensions'] = [
            'path'      => 'assets/images/extensions',
            'size'      => '36x36',
        ];
        $data['seo'] = [
            'path'      => 'assets/images/seo',
            'size'      => '1180x600',
        ];
        $data['userProfile'] = [
            'path'      => 'assets/images/user/profile',
            'size'      => '350x300',
        ];
        $data['adminProfile'] = [
            'path'      => 'assets/admin/images/profile',
            'size'      => '400x400',
        ];
        $data['push'] = [
            'path'      => 'assets/images/push_notification',
        ];
        $data['appPurchase'] = [
            'path'      => 'assets/in_app_purchase_config',
        ];
        $data['maintenance'] = [
            'path'      => 'assets/images/maintenance',
            'size'      => '660x325',
        ];
        $data['language'] = [
            'path' => 'assets/images/language',
            'size' => '50x50'
        ];
        $data['gateway'] = [
            'path' => 'assets/images/gateway',
            'size' => ''
        ];
        $data['withdrawMethod'] = [
            'path' => 'assets/images/withdraw_method',
            'size' => ''
        ];

        $data['location'] = [
            'path'      => 'assets/images/location',
            'size'      => '420x395',
        ];

        $data['category'] = [
            'path'      => 'assets/images/category',
            'size'      => '200x200',
        ];

        $data['organizerProfile'] = [
            'path'      => 'assets/images/organizer/profile',
            'size'      => '215x215',
        ];

        $data['organizerCover'] = [
            'path'      => 'assets/images/organizer/cover',
            'size'      => '1920x460',
        ];

        $data['eventCover'] = [
            'path'      => 'assets/images/event/cover',
            'size'      => '1300x520',
            'thumb'      => '325x130',
        ];

        $data['eventGallery'] = [
            'path'      => 'assets/images/event/gallery',
            'size'      => '1000x1000',
        ];

        $data['eventSpeaker'] = [
            'path'      => 'assets/images/event/speaker',
            'size'      => '500x500',
        ];

        return $data;
    }
}
