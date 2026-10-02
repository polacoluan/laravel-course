<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MakeLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'O email é obrigatório',
            'email.email' => 'O email informado não é válido',
            'password.required' => 'A senha é obrigatória',
        ];
    }

    public function attempt(): bool
    {
        if ($user = User::query()
            ->where('email', $this->email)
            ->first()
        ) {
            if (Hash::check($this->password, $user->password)) {

                Auth::login($user);
                $this->session()->regenerate();

                return true;
            }
        }

        return false;
    }
}
