@php
    $isEdit = $participant->exists;
@endphp

@csrf
@if ($isEdit)
    @method('PUT')
@endif

<div class="grid gap-4 sm:grid-cols-2" x-data="{ age: '{{ old('age', $participant->age) }}', category: '{{ old('category', $participant->category) }}' }">
    <div class="sm:col-span-2">
        <label class="kb-label" for="name">Nama peserta</label>
        <input class="kb-input" id="name" name="name" value="{{ old('name', $participant->name) }}" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="kb-label" for="age">Umur</label>
        <input class="kb-input" id="age" name="age" type="number" min="1" max="120" x-model="age" value="{{ old('age', $participant->age) }}" required>
        @error('age') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="kb-label" for="phone">Nombor telefon</label>
        <input class="kb-input" id="phone" name="phone" value="{{ old('phone', $participant->phone) }}" required>
        @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="kb-label" for="category">Kategori</label>
        <select class="kb-input" id="category" name="category" x-model="category" required>
            <option value="">Pilih kategori</option>
            @foreach (['Kanak-Kanak', 'Dewasa', 'Terbuka'] as $category)
                <option value="{{ $category }}" @selected(old('category', $participant->category) === $category)>{{ $category }}</option>
            @endforeach
        </select>
        @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="kb-label" for="house_id">Rumah sukan</label>
        <select class="kb-input" id="house_id" name="house_id" required>
            <option value="">Pilih rumah</option>
            @foreach ($houses as $house)
                <option value="{{ $house->id }}" @selected(old('house_id', $participant->house_id) == $house->id)>{{ $house->name }}</option>
            @endforeach
        </select>
        @error('house_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="kb-label" for="status">Status peserta</label>
        <select class="kb-input" id="status" name="status" required>
            @foreach (['Aktif', 'Dibatalkan'] as $status)
                <option value="{{ $status }}" @selected(old('status', $participant->status ?: 'Aktif') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="kb-label" for="sport_status">Status acara</label>
        <select class="kb-input" id="sport_status" name="sport_status">
            @foreach (['Menunggu', 'Diterima', 'Ditolak', 'Senarai Menunggu', 'Dibatalkan'] as $status)
                <option value="{{ $status }}" @selected(old('sport_status') === $status)>{{ $status }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="kb-label">Acara sukan</label>
        <div class="grid gap-2 rounded-lg border border-stone-200 bg-stone-50 p-3 sm:grid-cols-2">
            @foreach ($sports as $sport)
                <label class="flex gap-2 text-sm">
                    <input type="checkbox" name="sport_ids[]" value="{{ $sport->id }}" @checked(in_array($sport->id, old('sport_ids', $selectedSports), true))>
                    <span>{{ $sport->name }} <span class="text-stone-500">({{ $sport->category }})</span></span>
                </label>
            @endforeach
        </div>
    </div>

    <div class="sm:col-span-2 rounded-lg border border-amber-200 bg-amber-50 p-4" x-show="Number(age) > 0 && (Number(age) < {{ \App\Models\Participant::CHILD_AGE_THRESHOLD }} || category === 'Kanak-Kanak')">
        <h2 class="font-semibold text-amber-950">Maklumat Penjaga</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label class="kb-label" for="guardian_name">Nama penjaga</label>
                <input class="kb-input" id="guardian_name" name="guardian_name" value="{{ old('guardian_name', $participant->guardian?->name) }}">
                @error('guardian_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="kb-label" for="guardian_phone">Telefon penjaga</label>
                <input class="kb-input" id="guardian_phone" name="guardian_phone" value="{{ old('guardian_phone', $participant->guardian?->phone) }}">
                @error('guardian_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="kb-label" for="guardian_relationship">Hubungan</label>
                <input class="kb-input" id="guardian_relationship" name="guardian_relationship" value="{{ old('guardian_relationship', $participant->guardian?->relationship) }}">
                @error('guardian_relationship') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="sm:col-span-2">
        <label class="kb-label" for="notes">Catatan</label>
        <textarea class="kb-input" id="notes" name="notes" rows="3">{{ old('notes', $participant->notes) }}</textarea>
    </div>
</div>

<div class="mt-6 flex justify-end gap-3 border-t border-stone-200 pt-5">
    <a href="{{ route('admin.participants.index') }}" class="kb-btn-secondary">Batal</a>
    <button class="kb-btn-primary" type="submit">Simpan</button>
</div>
