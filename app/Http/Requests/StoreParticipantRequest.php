<?php

namespace App\Http\Requests;

use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreParticipantRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isChild = (int) $this->input('age') < Participant::CHILD_AGE_THRESHOLD || $this->input('category') === 'Kanak-Kanak';

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('participants')->where(fn ($query) => $query->where('phone', $this->input('phone'))),
            ],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^(\\+?6?01)[0-46-9]-?[0-9]{7,8}$/'],
            'category' => ['required', Rule::in(['Kanak-Kanak', 'Dewasa', 'Terbuka'])],
            'house_id' => ['required', 'exists:houses,id'],
            'sport_id' => ['nullable', 'exists:sports,id'],
            'guardian_name' => [$isChild ? 'required' : 'nullable', 'string', 'max:255'],
            'guardian_phone' => [$isChild ? 'required' : 'nullable', 'string', 'max:20', 'regex:/^(\\+?6?01)[0-46-9]-?[0-9]{7,8}$/'],
            'guardian_relationship' => [$isChild ? 'required' : 'nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'Pendaftaran dengan nama dan nombor telefon ini telah wujud.',
            '*.required' => 'Medan ini wajib diisi.',
            '*.regex' => 'Sila masukkan nombor telefon Malaysia yang sah.',
        ];
    }
}
