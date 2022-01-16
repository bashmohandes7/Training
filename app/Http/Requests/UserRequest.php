<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    protected function onCreate()
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'min:11', 'max:20', 'unique:users,phone'],
            'job_title' => ['required', 'string', 'max:255'],
            'administration' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255']
        ];
    }
    protected function onUpdate()
    {
        return [
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'email' => ['sometimes','nullable', 'email', 'unique:users,email'],
            'phone' => ['sometimes', 'nullable','string','max:20', 'unique:users,phone'],
            'job_title' => ['sometimes', 'nullable', 'string', 'max:255'],
            'administration' => ['sometimes','nullable', 'string', 'max:255'],
            'password' => ['sometimes', 'nullable', 'string', 'max:255']
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       return request()->isMethod('PUT') || request()->isMethod('PATCH') ?
           $this->onUpdate() : $this->onCreate();
    }
}
