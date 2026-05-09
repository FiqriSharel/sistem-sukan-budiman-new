<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminParticipantRequest;
use App\Models\AuditLog;
use App\Models\Guardian;
use App\Models\House;
use App\Models\Participant;
use App\Models\Sport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $participants = Participant::query()
            ->with(['house', 'guardian', 'sportRegistrations.sport'])
            ->when($request->search, fn ($query, $search) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('registration_code', 'like', "%{$search}%");
            }))
            ->when($request->house_id, fn ($query, $houseId) => $query->where('house_id', $houseId))
            ->when($request->category, fn ($query, $category) => $query->where('category', $category))
            ->when($request->status, fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.participants.index', [
            'participants' => $participants,
            'houses' => House::orderBy('name')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.participants.create', [
            'participant' => new Participant(['status' => 'Aktif']),
            'houses' => House::orderBy('name')->get(),
            'sports' => Sport::where('is_active', true)->orderBy('category')->orderBy('name')->get(),
            'selectedSports' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdminParticipantRequest $request)
    {
        $participant = $this->saveParticipant(new Participant([
            'registration_code' => $this->generateRegistrationCode(),
        ]), $request);

        AuditLog::record('create', $participant, null, $participant->toArray());

        return redirect()->route('admin.participants.show', $participant)->with('success', 'Rekod peserta berjaya ditambah.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Participant $participant)
    {
        $participant->load(['house', 'guardian', 'sportRegistrations.sport']);

        return view('admin.participants.show', compact('participant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Participant $participant)
    {
        $participant->load('sportRegistrations');

        return view('admin.participants.edit', [
            'participant' => $participant,
            'houses' => House::orderBy('name')->get(),
            'sports' => Sport::where('is_active', true)->orderBy('category')->orderBy('name')->get(),
            'selectedSports' => $participant->sportRegistrations->pluck('sport_id')->all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdminParticipantRequest $request, Participant $participant)
    {
        $oldValues = $participant->load('guardian', 'sportRegistrations')->toArray();
        $participant = $this->saveParticipant($participant, $request);

        AuditLog::record('update', $participant, $oldValues, $participant->fresh(['guardian', 'sportRegistrations'])->toArray());

        return redirect()->route('admin.participants.show', $participant)->with('success', 'Rekod peserta berjaya dikemaskini.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Participant $participant)
    {
        $oldValues = $participant->toArray();
        $participant->delete();

        AuditLog::record('delete', $participant, $oldValues, null);

        return redirect()->route('admin.participants.index')->with('success', 'Rekod peserta berjaya dipadam.');
    }

    private function saveParticipant(Participant $participant, AdminParticipantRequest $request): Participant
    {
        $data = $request->validated();
        $isChild = (int) $data['age'] < Participant::CHILD_AGE_THRESHOLD || $data['category'] === 'Kanak-Kanak';
        $guardian = $participant->guardian;

        if ($isChild) {
            $guardian = $guardian
                ? tap($guardian)->update([
                    'name' => $data['guardian_name'],
                    'phone' => $data['guardian_phone'],
                    'relationship' => $data['guardian_relationship'],
                ])
                : Guardian::create([
                    'name' => $data['guardian_name'],
                    'phone' => $data['guardian_phone'],
                    'relationship' => $data['guardian_relationship'],
                ]);
        } elseif ($guardian) {
            $guardian->delete();
            $guardian = null;
        }

        $participant->fill([
            'name' => $data['name'],
            'age' => $data['age'],
            'phone' => $data['phone'],
            'category' => $data['category'],
            'house_id' => $data['house_id'],
            'guardian_id' => $guardian?->id,
            'status' => $data['status'],
            'notes' => $data['notes'] ?? null,
        ]);

        if (! $participant->registration_code) {
            $participant->registration_code = $this->generateRegistrationCode();
        }

        $participant->save();

        $sync = collect($data['sport_ids'] ?? [])->mapWithKeys(fn ($sportId) => [
            $sportId => ['status' => $data['sport_status'] ?? 'Menunggu', 'remarks' => null],
        ])->all();
        $participant->sportRegistrations()->whereNotIn('sport_id', array_keys($sync))->delete();
        foreach ($sync as $sportId => $attributes) {
            $participant->sportRegistrations()->updateOrCreate(['sport_id' => $sportId], $attributes);
        }

        return $participant;
    }

    private function generateRegistrationCode(): string
    {
        do {
            $code = 'SRKB-'.now()->format('ymd').'-'.Str::upper(Str::random(5));
        } while (Participant::where('registration_code', $code)->exists());

        return $code;
    }
}
