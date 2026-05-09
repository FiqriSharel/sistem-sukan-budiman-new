@csrf
@if ($house->exists)
    @method('PUT')
@endif

<div class="grid gap-4 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="kb-label" for="name">Nama rumah sukan</label>
        <input class="kb-input" id="name" name="name" value="{{ old('name', $house->name) }}" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="kb-label" for="color">Warna</label>
        <input class="kb-input h-11" id="color" name="color" type="color" value="{{ old('color', $house->color ?: '#15803d') }}">
    </div>
    <div class="sm:col-span-2">
        <label class="kb-label" for="description">Penerangan</label>
        <textarea class="kb-input" id="description" name="description" rows="3">{{ old('description', $house->description) }}</textarea>
    </div>
</div>
<div class="mt-6 flex justify-end gap-3 border-t border-stone-200 pt-5">
    <a href="{{ route('admin.houses.index') }}" class="kb-btn-secondary">Batal</a>
    <button class="kb-btn-primary" type="submit">Simpan</button>
</div>
