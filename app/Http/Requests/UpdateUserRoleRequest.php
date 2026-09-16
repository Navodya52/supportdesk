<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $targetUser = $this->route('user');

        return $targetUser && $this->user()?->can('updateRole', $targetUser);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'role' => ['required', 'string', 'in:'.User::ROLE_ADMIN.','.User::ROLE_AGENT.','.User::ROLE_EMPLOYEE],
            'is_active' => ['required', 'boolean'],
        ];
    }
}
