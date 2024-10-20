<?php 

namespace News\ValidationRequests;

use News\Abstracts\FormRequest;

class PasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Allow all users to authorize this request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required', // Token is required for password reset
            'email' => 'required|email|exists:users,email', // Ensure the email exists in the users table
            'password' => 'required|confirmed|min:8', // Password must be confirmed and at least 8 characters
        ];
    }
}
