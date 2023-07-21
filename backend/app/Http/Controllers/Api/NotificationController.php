<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function store(Request $request) {
        User::findOrfail(auth()->id())->update([
            'device_key' => $request->token,
        ]);
    }
}
