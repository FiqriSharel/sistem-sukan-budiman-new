<?php

namespace App\Http\Requests;

use App\Models\Participant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminParticipantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $participantId = $this->route('participant')?->id;
        $isChild = (int) $this->input('age') < Participant::CHILD_AGE_THRESHOLD || $this->input('category') === 'Kanak-Kanak';

        return [
            'name' => ['required', 'string', 'max:255'],
            'age' => ['required', 'integer', 'min:1', 'max:120'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^(\\+?6?01)[0-46-9]-?[0-9]{7,8}$/',
                Rule::unique('participants')->where(fn ($query) => $query->where('name', $this->input('name')))->ignore($participantId),
            ],
            'category' => ['required', Rule::in(['Kanak-Kanak', 'Dewasa', 'Terbuka'])],
            'house_id' => ['required', 'exists:houses,id'],
            'status' => ['required', Rule::in(['Aktif', 'Dibatalkan'])],
            'sport_ids' => ['nullable', 'array'],
            'sport_ids.*' => ['exists:sports,id'],
            'sport_status' => ['nullable', Rule::in(['Menunggu', 'Diterima', 'Ditolak', 'Senarai Menunggu', 'Dibatalkan'])],
            'notes' => ['nullable', 'string'],
            'guardian_name' => [$isChild ? 'required' : 'nullable', 'string', 'max:255'],
            'guardian_phone' => [$isChild ? 'required' : 'nullable', 'string', 'max:20', 'regex:/^(\\+?6?01)[0-46-9]-?[0-9]{7,8}$/'],
            'guardian_relationship' => [$isChild ? 'required' : 'nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            '*.required' => 'Medan ini wajib diisi.',
            '*.regex' => 'Sila masukkan nombor telefon Malaysia yang sah.',
            'phone.unique' => 'Rekod peserta dengan nama dan nombor telefon ini telah wujud.',
        ];
    }
}
