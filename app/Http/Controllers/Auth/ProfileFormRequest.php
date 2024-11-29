<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\Unique;
use MoonShine\Http\Requests\MoonShineFormRequest;
use MoonShine\MoonShineAuth;

class ProfileFormRequest extends MoonShineFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return MoonShineAuth::guard()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array{name: string[], username: Unique[]|string[], avatar: string[], password: string}
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'username' => [
                'required',
                Rule::unique(
                    MoonShineAuth::model()?->getTable(),
                    config('moonshine.auth.fields.username', 'email')
                )->ignore(MoonShineAuth::guard()->id()),
            ],
            'avatar' => ['image'],
           // 'password' => 'sometimes|nullable|min:8|required_with:password_repeat|same:password_repeat',
            'password' => ['nullable','required_with:password_repeat','same:password_repeat', Password::min(8)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols(),
            ],
        ];
    }
}
