<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UserValidate extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'=>['required','string','max:50','unique:users,email'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
       
        if (!$this->expectsJson()) {
            $mergedResponse = [
                'message' => 'Validation error',
                'status' => false,
                'data' => $validator->errors(),
            ];
            return response()->json($mergedResponse, 200);
        }
    
        parent::failedValidation($validator);
    }
}
