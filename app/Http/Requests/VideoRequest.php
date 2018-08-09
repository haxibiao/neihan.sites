<?php

namespace App\Http\Requests;

use App\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {   
        $video_id = $this->route('video');
        $user = Auth::user();
        $post = Video::where('user_id',$user->id)->whereId($video_id)->first();
        
        return $user->is_editor || $post;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'categories' => 'required',
            // 'description' => 'required|min:10',
        ];
    }
}
