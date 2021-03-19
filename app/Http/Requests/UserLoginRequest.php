<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserLoginRequest extends FormRequest
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
        $fieldType = $this->input('account_data');
        $accountDataValidation = "required";
        if (filter_var($fieldType, FILTER_VALIDATE_EMAIL)) {
            $accountDataValidation .= "|exists:users,email";
        } else {
            $accountDataValidation .= "|exists:users,username";
        }

        return [
            "account_data" => $accountDataValidation,
            "password" => "required|min:8",
            "device_name" => "required"
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
            "exists" => ":attribute tidak terdaftar",
            'required' => ':attribute tidak boleh kosong',
            "min" => ":attribute terlalu pendek"
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            "account_data" => "Username atau Email"
        ];
    }



    protected function failedValidation(Validator $validator)
    {
        $errors = array();
        foreach ($validator->errors()->all() as $value) {
            array_push($errors, ucfirst($value));
        }
        throw new HttpResponseException(response()->json([
            "data" => null,
            "errors" => implode(',', $errors),
            "is_error" => true,
            "message" => "Gagal login",
        ], 422));
    }
}
