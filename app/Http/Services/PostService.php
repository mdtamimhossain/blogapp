<?php

namespace App\Http\Services;


use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostService extends Service
{

    /**
     * @param array $data
     * @return array
     */
    public function getList (): array
    {
        try {
            $data = Post::where('user_id', Auth::id())->get();
            return  $this->responseSuccess('Done!',['posts'=> $data]);
        }
        catch (\Exception $exception) {
            return $this->responseError($exception->getMessage());
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        try {
            Post::create([
                'user_id' => Auth::id(),
                'title' => $data['title'],
                'content' => $data['content']
               ]);
            return  $this->responseSuccess("Post added successfully.");
        }
        catch (\Exception $exception){
            return $this->responseError($exception->getMessage());
        }
    }

}
