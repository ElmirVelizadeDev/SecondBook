<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class MailConfigurationService
{
    public function apply(): void
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        $settings = Setting::query()
            ->where('group_name', 'email')
            ->get()
            ->keyBy('key');

        $value = function (
            string $key,
            mixed $default = null
        ) use ($settings): mixed {
            $setting = $settings->get($key);

            if (!$setting) {
                return $default;
            }

            return match ($setting->type) {
                'boolean' => filter_var(
                    $setting->value,
                    FILTER_VALIDATE_BOOLEAN
                ),
                'integer' => (int) $setting->value,
                'json' => json_decode(
                    $setting->value,
                    true
                ) ?? $default,
                default => $setting->value,
            };
        };

        $mailer = $value(
            'mail_mailer',
            env('MAIL_MAILER', 'smtp')
        );

        $host = $value(
            'mail_host',
            env('MAIL_HOST', 'smtp.mailgun.org')
        );

        $port = $value(
            'mail_port',
            env('MAIL_PORT', 587)
        );

        $encryption = $value(
            'mail_encryption',
            env('MAIL_ENCRYPTION', 'tls')
        );

        $username = $value(
            'mail_username',
            env('MAIL_USERNAME')
        );

        $password = $value('mail_password');

        if ($password !== null && $password !== '') {
            try {
                $password = Crypt::decryptString($password);
            } catch (\Throwable) {
                $password = null;
            }
        }

        $fromAddress = $value(
            'mail_from_address',
            env('MAIL_FROM_ADDRESS', 'hello@example.com')
        );

        $fromName = $value(
            'mail_from_name',
            env('MAIL_FROM_NAME', 'SecondBook')
        );

        $replyToAddress = $value(
            'mail_reply_to_address'
        );

        $replyToName = $value(
            'mail_reply_to_name'
        );

        Config::set(
            'mail.default',
            $mailer
        );

        Config::set(
            'mail.mailers.smtp.transport',
            'smtp'
        );

        Config::set(
            'mail.mailers.smtp.host',
            $host
        );

        Config::set(
            'mail.mailers.smtp.port',
            $port
        );

        Config::set(
            'mail.mailers.smtp.encryption',
            $encryption === 'none'
                ? null
                : $encryption
        );

        Config::set(
            'mail.mailers.smtp.username',
            $username
        );

        Config::set(
            'mail.mailers.smtp.password',
            $password
        );

        Config::set(
            'mail.from.address',
            $fromAddress
        );

        Config::set(
            'mail.from.name',
            $fromName
        );

        if ($replyToAddress) {
            Config::set(
                'mail.reply_to',
                [
                    'address' => $replyToAddress,
                    'name' => $replyToName,
                ]
            );
        } else {
            Config::set(
                'mail.reply_to',
                []
            );
        }

        Mail::purge('smtp');
    }
}
