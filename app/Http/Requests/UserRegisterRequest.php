<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "username" => "bail|required|min:6",
            "email" => "bail|required|email",
            "username" => "bail|alpha_num",
            "password" => "bail|required|min:8"
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.email' => 'Email invalid',
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute paling tidak harus sepanjang :min karakter.',
            "alpha_num" => ":attribute hanya boleh berisi huruf atau angka",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = array();
        foreach ($validator->errors()->all() as $key => $value) {
            array_push($errors, ucfirst($value));
        }
        throw new HttpResponseException(response()->json([
            "data" => null,
            "errors" => $errors,
            "is_error" => true,
            "message" => "Gagal didaftarkan",
        ], 422));
    }
}
