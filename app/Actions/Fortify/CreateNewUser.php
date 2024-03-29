<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[ぁ-んァ-ヶ一-龥々|A-Z|a-z|_|0-9]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'pen_name' => ['required', 'max:255', 'unique:users','regex:/^[a-zA-Z0-9_]+$/'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'pen_name' => $input['pen_name'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
