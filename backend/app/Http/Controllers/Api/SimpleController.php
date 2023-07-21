<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Simple;
use LaravelFCM\Facades\FCM;
use App\Http\Requests\StoreRequest;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;

class SimpleController extends Controller
{
    public function store(StoreRequest $request) {
        $post = Simple::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'body' => $request->body,
        ]);

        //Instead of title 
        $user = User::findOrFail($post->user_id);
        $username = $user->name;
        $body = $post->title;
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20); 
        $option = $optionBuilder->build();
        $notificationBuilder = new PayloadNotificationBuilder($username);
        $notificationBuilder->setBody($body)
        ->setSound('default');
        $notification = $notificationBuilder->build();

        $fcmTokens = User::where('id', '<>', auth()->id())->pluck('device_key')->toArray();
        FCM::sendTo($fcmTokens, $option, $notification, null);

        return response()->json([
            'success' => 'Created successfully.'
        ], 200);
    }

    public function index() {
        $postPerPage = 5;
        $posts = Simple::sort()
            ->paginate($postPerPage);
        $pageCount = count(Simple::all()) / $postPerPage;

        return response()->json([
            'post' => $posts,
            'page_count' => ceil($pageCount)
        ], 200);
    }

    public function edit($id)
    {
        $post = Simple::findOrFail($id);

        return response()->json([
            'post' => $post,
        ]);
    }

    public function update(StoreRequest $request) {
        $post = Simple::findOrFail($request->id);
        $post->update([
            'title' => $request->title,
            'body' => $request->body,
        ]);

        return response()->json([
            'success' => 'Updated successfully.',
            'post' => $post,
        ], 200);
    }

    public function destroy($id) {
        Simple::findOrFail($id)->delete();

        return response()->json([
            'success' => 'Post deleted successfully.'
        ], 200);
    }
}
