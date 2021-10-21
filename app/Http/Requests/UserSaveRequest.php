<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserSaveRequest extends FormRequest
{
    protected User $resource;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $this->resource = $this->route()->hasParameter('user') ? $this->route('user') : new User();

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
        ];

        $rules['email'] = ['required', 'email', Rule::unique('users')->ignore($this->resource)];
        if ($this->resource->exists) {
            $rules['password'] = 'sometimes|string|nullable|confirmed';
        } else {
            $rules['password'] = 'required|string|confirmed';
        }

        if (Auth::user()->role === 'admin') {
            $rules['role'] = ['sometimes','nullable', 'string', Rule::in(config('users.roles', []))];
        }
        $rules['avatar'] = 'sometimes|file';

        return $rules;
    }

    public function persist(User $model): User
    {
        $attributes = $this->only(['email', 'name']);
        if ($password = $this->get('password')) {
            $attributes['password'] = Hash::make($password);
        }

        if (Auth::user()->can('assignRole', $model) && $this->has('role')) {
            $attributes['role'] = $this->get('role');
        }

        if ($this->hasFile('avatar')) {
            $model->attachAvatar($this->file('avatar'));
        }
        $model->fill($attributes);
        $model->save();

        return $model;
    }
}
