<?php

namespace App\Services;

use App\Models\SiteSetting;

class MailConfigService
{
    public static function set()
    {
        config([

            'mail.default' => 'smtp',

            'mail.mailers.smtp.transport' => 'smtp',

            'mail.mailers.smtp.host' =>
                SiteSetting::get('smtp_host'),

            'mail.mailers.smtp.port' =>
                SiteSetting::get('smtp_port'),

            'mail.mailers.smtp.username' =>
                SiteSetting::get('smtp_username'),

            'mail.mailers.smtp.password' =>
                SiteSetting::get('smtp_password'),

            'mail.mailers.smtp.encryption' =>
                SiteSetting::get('smtp_encryption'),

            'mail.from.address' =>
                SiteSetting::get('smtp_from_address'),

            'mail.from.name' =>
                SiteSetting::get('smtp_from_name'),

        ]);
    }
}