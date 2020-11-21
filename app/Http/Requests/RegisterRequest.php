<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
     *  Validation rules to be applied to the input.
     *
     *  @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:4|max:20',
            'email'  =>  'required|email|unique:users,email',
            'password'      =>  'required|min:8|max:32'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */


    /**
     * Show validation errors
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => -3,
            'message' => implode(', ', $validator->errors()->all()),
        ], 200));
    }
}
