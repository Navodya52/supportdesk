<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTicketRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $ticket = $this->route('ticket');

        return $ticket && $this->user()?->can('update', $ticket);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, list<string>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        // If employee, they can only close or reopen
        if ($user && $user->isEmployee()) {
            return [
                'status' => ['required', 'string', 'in:open,closed'],
            ];
        }

        return [
            'status' => ['required', 'string', 'in:open,in_progress,pending,resolved,closed'],
            'priority' => ['required', 'string', 'in:low,medium,high,critical'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string', 'min:10', 'max:5000'],
            'resolution' => ['nullable', 'string', 'max:5000', 'required_if:status,resolved'],
        ];
    }
}
