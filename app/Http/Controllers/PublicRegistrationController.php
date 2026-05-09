<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreParticipantRequest;
use App\Models\Guardian;
use App\Models\House;
use App\Models\Participant;
use App\Models\Sport;
use App\Models\SportRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicRegistrationController extends Controller
{
    public function landing()
    {
        return view('public.landing');
    }

    public function create()
    {
        return view('public.register', [
            'houses' => House::orderBy('name')->get(),
            'sports' => Sport::where('is_active', true)->orderBy('category')->orderBy('name')->get(),
            'childAgeThreshold' => Participant::CHILD_AGE_THRESHOLD,
        ]);
    }

    public function store(StoreParticipantRequest $request)
    {
        $data = $request->validated();
        $guardian = null;

        if ((int) $data['age'] < Participant::CHILD_AGE_THRESHOLD || $data['category'] === 'Kanak-Kanak') {
            $guardian = Guardian::create([
                'name' => $data['guardian_name'],
                'phone' => $data['guardian_phone'],
                'relationship' => $data['guardian_relationship'],
            ]);
        }

        $participant = Participant::create([
            'registration_code' => $this->generateRegistrationCode(),
            'name' => $data['name'],
            'age' => $data['age'],
            'phone' => $data['phone'],
            'category' => $data['category'],
            'house_id' => $data['house_id'],
            'guardian_id' => $guardian?->id,
            'status' => 'Aktif',
        ]);

        if (! empty($data['sport_id'])) {
            SportRegistration::create([
                'participant_id' => $participant->id,
                'sport_id' => $data['sport_id'],
                'status' => 'Menunggu',
            ]);
        }

        return redirect()->route('public.success', $participant->registration_code);
    }

    public function success(string $registrationCode)
    {
        $participant = Participant::where('registration_code', $registrationCode)->firstOrFail();

        return view('public.success', compact('participant'));
    }

    public function check()
    {
        return view('public.check');
    }

    public function lookup(Request $request)
    {
        $request->validate([
            'search' => ['required', 'string', 'max:255'],
        ], ['search.required' => 'Sila masukkan kod pendaftaran atau nombor telefon.']);

        $search = $request->input('search');

        $participants = Participant::with(['house', 'guardian', 'sportRegistrations.sport'])
            ->where('registration_code', $search)
            ->orWhere('phone', $search)
            ->latest()
            ->get();

        return view('public.status', compact('participants', 'search'));
    }

    public function status(string $registrationCode)
    {
        $participants = Participant::with(['house', 'guardian', 'sportRegistrations.sport'])
            ->where('registration_code', $registrationCode)
            ->get();

        abort_if($participants->isEmpty(), 404);

        return view('public.status', ['participants' => $participants, 'search' => $registrationCode]);
    }

    private function generateRegistrationCode(): string
    {
        do {
            $code = 'SRKB-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (Participant::where('registration_code', $code)->exists());

        return $code;
    }
}
