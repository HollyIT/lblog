<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Rules\AssignTagsRule;
use App\Rules\PublishedAtRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostSaveRequest extends FormRequest
{
    /**
     * @var Post
     */
    protected mixed $resource;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $this->resource = $this->route('post', new Post());

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => 'required|string',
            'description' => 'required|string',
            'body' => 'required|string',
            'body_format' => ['required', 'string', Rule::in(array_keys(config('posts.formats')))],
            'image' => 'sometimes|exists:images',
            'published' => ['sometimes', 'nullable', new PublishedAtRule()],
            'tags' => ['sometimes', 'nullable', 'array', new AssignTagsRule()],
        ];

        if (\Auth::user()->can('assignOwner', $this->resource)) {
            $rules['user'] = 'sometimes|exists:users,id';
        }

        return $rules;
    }
}
