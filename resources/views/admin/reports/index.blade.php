<x-app-layout>
    <x-slot name="header"><h1 class="text-2xl font-bold text-green-950">Laporan & Eksport</h1></x-slot>
    <div class="kb-container py-6">
        <form method="GET" action="{{ route('admin.reports.export') }}" class="kb-card grid gap-4 p-5 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <label class="kb-label" for="house_id">Rumah sukan</label>
                <select class="kb-input" id="house_id" name="house_id">
                    <option value="">Semua</option>
                    @foreach ($houses as $house)
                        <option value="{{ $house->id }}">{{ $house->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="kb-label" for="category">Kategori</label>
                <select class="kb-input" id="category" name="category">
                    <option value="">Semua</option>
                    @foreach (['Kanak-Kanak', 'Dewasa', 'Terbuka'] as $category)
                        <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="kb-label" for="sport_id">Acara</label>
                <select class="kb-input" id="sport_id" name="sport_id">
                    <option value="">Semua</option>
                    @foreach ($sports as $sport)
                        <option value="{{ $sport->id }}">{{ $sport->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button class="kb-btn-primary w-full" type="submit">Eksport CSV</button>
            </div>
        </form>
        <div class="kb-card mt-5 p-5 text-sm text-stone-600">
            Senarai eksport mengandungi kod pendaftaran, butiran peserta, rumah sukan, penjaga dan acara yang dipilih.
        </div>
    </div>
</x-app-layout>
