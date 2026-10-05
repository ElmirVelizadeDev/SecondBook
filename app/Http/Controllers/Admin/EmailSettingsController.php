<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ActivityLogService;
use App\Services\MailConfigurationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class EmailSettingsController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService,
        protected MailConfigurationService $mailConfigurationService
    ) {
    }

    /**
     * Display email settings.
     */
    public function index()
    {
        $settings = Setting::query()
            ->where('group_name', 'email')
            ->get()
            ->keyBy('key');

        return view(
            'Admin.email-settings.index',
            compact('settings')
        );
    }

    /**
     * Update email settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'mail_enabled' => [
                'nullable',
                'boolean',
            ],
            'mail_mailer' => [
                'required',
                'in:smtp,log',
            ],
            'mail_host' => [
                'required_if:mail_mailer,smtp',
                'nullable',
                'string',
                'max:255',
            ],
            'mail_port' => [
                'required_if:mail_mailer,smtp',
                'nullable',
                'integer',
                'min:1',
                'max:65535',
            ],
            'mail_username' => [
                'nullable',
                'string',
                'max:255',
            ],
            'mail_password' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'mail_encryption' => [
                'nullable',
                'in:none,tls,ssl',
            ],
            'mail_from_address' => [
                'required',
                'email',
                'max:255',
            ],
            'mail_from_name' => [
                'required',
                'string',
                'max:255',
            ],
            'mail_reply_to_address' => [
                'nullable',
                'email',
                'max:255',
            ],
            'mail_reply_to_name' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | General Email Settings
        |--------------------------------------------------------------------------
        */

        Setting::put(
            'mail_enabled',
            $request->boolean('mail_enabled'),
            'email',
            'boolean'
        );

        Setting::put(
            'mail_mailer',
            $validated['mail_mailer'],
            'email',
            'text'
        );

        Setting::put(
            'mail_host',
            $validated['mail_host'] ?? '',
            'email',
            'text'
        );

        Setting::put(
            'mail_port',
            $validated['mail_port'] ?? 587,
            'email',
            'integer'
        );

        Setting::put(
            'mail_username',
            $validated['mail_username'] ?? '',
            'email',
            'text'
        );

        /*
        |--------------------------------------------------------------------------
        | SMTP Password
        |--------------------------------------------------------------------------
        |
        | Password is only updated when a new value is provided.
        | Existing password remains untouched when the field is empty.
        |
        */

        if ($request->filled('mail_password')) {
            Setting::put(
                'mail_password',
                Crypt::encryptString(
                    $request->input('mail_password')
                ),
                'email',
                'text'
            );
        }

        Setting::put(
            'mail_encryption',
            $validated['mail_encryption'] ?? 'tls',
            'email',
            'text'
        );

        /*
        |--------------------------------------------------------------------------
        | Sender Settings
        |--------------------------------------------------------------------------
        */

        Setting::put(
            'mail_from_address',
            $validated['mail_from_address'],
            'email',
            'text'
        );

        Setting::put(
            'mail_from_name',
            $validated['mail_from_name'],
            'email',
            'text'
        );

        /*
        |--------------------------------------------------------------------------
        | Reply-To Settings
        |--------------------------------------------------------------------------
        */

        Setting::put(
            'mail_reply_to_address',
            $validated['mail_reply_to_address'] ?? '',
            'email',
            'text'
        );

        Setting::put(
            'mail_reply_to_name',
            $validated['mail_reply_to_name'] ?? '',
            'email',
            'text'
        );

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */

        $this->activityLogService->log(
            'updated',
            'Email Settings',
            'Email configuration was updated.'
        );

        return redirect()
            ->route('admin.email-settings.index')
            ->with(
                'success',
                'Email settings updated successfully.'
            );
    }

    /**
     * Send a test email.
     */
    public function test(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'test_email' => [
                    'required',
                    'email',
                    'max:255',
                ],
            ]
        );

        $validator->validate();

        try {
            /*
            |--------------------------------------------------------------------------
            | Apply Current Database Settings
            |--------------------------------------------------------------------------
            */

            $this->mailConfigurationService->apply();

            $mailer = Setting::get(
                'mail_mailer',
                'smtp'
            );

            if ($mailer !== 'smtp') {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'Test email requires the SMTP mailer.'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Send Test Email
            |--------------------------------------------------------------------------
            */

            Mail::mailer('smtp')->raw(
                "This is a test email from SecondBook.\n\n"
                . "If you received this message, your email "
                . "configuration is working correctly.",
                function ($message) use ($request) {
                    $message
                        ->to($request->input('test_email'))
                        ->subject('SecondBook Email Configuration Test');
                }
            );

            /*
            |--------------------------------------------------------------------------
            | Activity Log
            |--------------------------------------------------------------------------
            */

            $this->activityLogService->log(
                'created',
                'Email Settings',
                'Test email sent to '
                . $request->input('test_email')
                . '.'
            );

            return back()
                ->with(
                    'success',
                    'Test email sent successfully.'
                );
        } catch (Throwable $exception) {
            report($exception);

            $this->activityLogService->log(
                'created',
                'Email Settings',
                'Test email failed for '
                . $request->input('test_email')
                . '.'
            );

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Test email could not be sent. '
                    . 'Please check your SMTP settings.'
                );
        }
    }
}
