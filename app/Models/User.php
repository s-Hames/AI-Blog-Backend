<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @property string $name
 * @property string $email
 * @property string $password
 */

class User extends Authenticatable
{
    const COLUMN_NAME = 'name';
    const COLUMN_EMAIL = 'email';
    const COLUMN_PASSWORD = 'password';

    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        self::COLUMN_NAME,
        self::COLUMN_EMAIL,
        self::COLUMN_PASSWORD,
    ];

    protected $hidden = [
        self::COLUMN_PASSWORD,
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    static function registerUser(array $inputData)
    {

        $data = Validator::make($inputData, [
            self::COLUMN_NAME => 'required|string|max:255',
            self::COLUMN_EMAIL => 'required|string|email|max:255|unique:users',
            self::COLUMN_PASSWORD => 'required|string|min:8',
        ])->validated();


        $model = new self();
        $model->name = $data[self::COLUMN_NAME] ?? "";
        $model->email = $data[self::COLUMN_EMAIL];
        $model->password = Hash::make($data[self::COLUMN_PASSWORD]);
        $model->save();
        return $model;

    }
}
