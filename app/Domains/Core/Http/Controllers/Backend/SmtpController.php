<?php

namespace App\Domains\Core\Http\Controllers\Backend;

use App\Domains\Core\Http\Requests\Setting\StoreSmtpRequest;
use App\Http\Controllers\Controller;
use App\Notifications\TestSmtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;
use function view;

class SmtpController extends Controller
{
    public function index()
    {
        return view('backend.core.setting.smtp');
    }

    public function edit()
    {
        return view('backend.core.setting.smtp_edit');
    }

    public function store(StoreSmtpRequest $request)
    {

        $this->save_options_array(Arr::dot($request->validated()));
        return redirect()->route('admin.smtp.index')->withFlashSuccess(__('The SMTP configuration has been updated successfully'));
    }

    public function sendTesting(Request $request)
    {
        $user = $request->user();
        Notification::route('mail', $user->email)->notify(new TestSmtpNotification());
        return back()->withFlashSuccess(__('Test email has been sent to '.$user->email));
    }
}
