<?php

namespace App\Http\Controllers\Api;

use Throwable;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use LaravelFCM\Facades\FCM;
use App\Models\CategoryPost;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use LaravelFCM\Message\OptionsBuilder;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use LaravelFCM\Message\PayloadNotificationBuilder;

class PostController extends Controller
{
    public function getCategories()
    {
        $categories = Category::all()->pluck('name');

        return response()->json($categories);
    }

    public function index() {
        $postPerPage = 5;
        $posts = Post::with('categories')
            ->filter(request('search'))
            ->sort()
            ->paginate($postPerPage);
        $pageCount = count(Post::all()) / $postPerPage;

        return response()->json([
            'post' => $posts,
            'page_count' => ceil($pageCount)
        ], 200);
    }

    public function store(PostStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $file_path = "";
            if($request->file()) {
                $file_name = time().'_'.$request->file->getClientOriginalName();
                $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public');            
            }
            $post = Post::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'image' =>  '/storage/' . $file_path,
            ]);

            $categories = \explode(',', $request->categories);
            foreach($categories as $categoryName) {
                $category = Category::where('name', $categoryName)->first();
                CategoryPost::create([
                    'post_id' => $post->id,
                    'category_id' => $category->id,
                ]);
            }

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
            Notification::create([
                'post_id' => $post->id,
                'user_id' => auth()->id(),
            ]);
            DB::commit();
            
            return response()->json([
                'success' => 'Post created successfully.'
            ], 200);
        } 
        catch (Throwable $th) {
            Log::info('Post Creation failed', ['error' => $th->getMessage()]);
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Post Creation failed] Message: ' . $th->getMessage());
            DB::rollBack();
        }
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);
        $selectedCategories = $post->categories->pluck('name');
        $categories = Category::all()->pluck('name');

        return \response()->json([
            'post' => $post,
            'categories' => $categories,
            'selectedCategories' => $selectedCategories,
        ]);
    }

    public function update(PostUpdateRequest $request, string $id)
    {
        try {
            DB::beginTransaction();
            $post = Post::findOrFail($id);
            $post->title = $request->title;
            if($request->file()) {
                unlink(public_path($post->image));
                $file_name = time() . '_' . $request->file->getClientOriginalName();
                $file_path = $request->file('file')->storeAs('uploads', $file_name, 'public'); 
                $post->image = '/storage/' . $file_path;
            }
            $post->save();

            CategoryPost::where('post_id', $post->id)->delete();
            $categories = \explode(',', $request->categories[0]);
            foreach($categories as $categoryName) {
                $category = Category::where('name', $categoryName)->first();
                CategoryPost::create([
                    'post_id' => $post->id,
                    'category_id' => $category->id,
                ]);
            }
            DB::commit();
        }
        catch (Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Post Edition failed] Message: ' . $th->getMessage());
            DB::rollBack();
        }
        
        return response()->json("Post updated successfully.");
    }

    public function destroy($id)
    {
        try{
            DB::beginTransaction();
            $post = Post::findOrFail($id);
            unlink(public_path($post->image));
            CategoryPost::where('post_id', $post->id)->delete();
            $post->delete();
            DB::commit();

            return response()->json([
                'success' => 'Post deleted successfully.'
            ], 200);
        } catch(Throwable $th) {
            Log::error(__CLASS__ . '::' . __FUNCTION__ . '[line: ' . __LINE__ . '][Post deletion failed] Message: ' . $th->getMessage());
            DB::rollBack();
        }        
    }
}
