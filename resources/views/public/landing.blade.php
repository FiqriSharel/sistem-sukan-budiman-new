<x-public-layout title="Sukan Rakyat Kampung Budiman">
    <section class="relative overflow-hidden bg-green-950 text-white">
        <div class="absolute inset-0 opacity-20">
            <div class="h-full w-full bg-[radial-gradient(circle_at_20%_20%,#facc15_0,transparent_24%),linear-gradient(135deg,#14532d_0%,#1c1917_55%,#052e16_100%)]"></div>
        </div>
        <div class="kb-container relative grid gap-10 py-12 md:grid-cols-[1.05fr_0.95fr] md:items-center lg:py-16">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-200">Sukan SULAM Kampung Budiman</p>
                <h1 class="mt-3 max-w-3xl text-4xl font-bold leading-tight sm:text-5xl">Sukan Rakyat Kampung Budiman</h1>
                <p class="mt-4 max-w-2xl text-base leading-7 text-green-50">Daftar sebagai peserta, pilih acara yang sesuai, dan simpan kod pendaftaran untuk semakan. Kategori peserta akan ditentukan secara automatik melalui umur.</p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                    @if ($registrationIsOpen)
                        <a href="{{ route('public.register') }}" class="kb-btn-primary bg-amber-400 text-green-950 hover:bg-amber-300">Daftar Peserta</a>
                    @else
                        <span class="inline-flex items-center justify-center rounded-lg bg-stone-200 px-4 py-2 text-sm font-semibold text-stone-700">Pendaftaran Ditutup</span>
                    @endif
                    <a href="{{ route('public.check') }}" class="kb-btn-secondary border-green-100 bg-white/10 text-white hover:bg-white/20">Semak Pendaftaran</a>
                </div>

                <div class="mt-8 grid gap-3 text-sm sm:grid-cols-3">
                    <div class="border-l-2 border-amber-300 pl-3">
                        <p class="text-green-100">Tarikh</p>
                        <p class="font-semibold">{{ ! empty($settings['event_date']) ? \Illuminate\Support\Carbon::parse($settings['event_date'])->format('d/m/Y') : 'Akan dimaklumkan' }}</p>
                    </div>
                    <div class="border-l-2 border-amber-300 pl-3">
                        <p class="text-green-100">Masa</p>
                        <p class="font-semibold">{{ $settings['event_time'] ?? 'Akan dimaklumkan' }}</p>
                    </div>
                    <div class="border-l-2 border-amber-300 pl-3">
                        <p class="text-green-100">Tempat</p>
                        <p class="font-semibold">{{ $settings['event_venue'] ?? 'Kampung Budiman' }}</p>
                    </div>
                </div>
            </div>

            <div class="rounded-lg border border-green-800 bg-green-900/75 p-5 shadow-2xl shadow-green-950/30">
                <div class="rounded-lg bg-green-950/60 p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm text-green-100">Status pendaftaran</p>
                            <p class="mt-1 text-2xl font-bold">{{ $registrationIsOpen ? 'Dibuka' : 'Ditutup' }}</p>
                        </div>
                        <span class="rounded-full {{ $registrationIsOpen ? 'bg-emerald-200 text-emerald-950' : 'bg-stone-200 text-stone-800' }} px-3 py-1 text-xs font-bold uppercase">
                            {{ $registrationIsOpen ? 'Aktif' : 'Tutup' }}
                        </span>
                    </div>
                    @if (! empty($settings['registration_deadline']))
                        <p class="mt-4 rounded-lg bg-white/10 p-3 text-sm text-green-50">Tarikh akhir: {{ \Illuminate\Support\Carbon::parse($settings['registration_deadline'])->format('d/m/Y h:i A') }}</p>
                    @endif
                </div>

                <div class="mt-5 grid grid-cols-2 gap-3 text-center">
                    <div class="rounded-lg border border-green-700 bg-white/10 p-4">
                        <p class="text-2xl font-bold">{{ $sports->count() }}</p>
                        <p class="mt-1 text-xs text-green-100">Acara aktif</p>
                    </div>
                    <div class="rounded-lg border border-green-700 bg-white/10 p-4">
                        <p class="text-2xl font-bold">{{ $houses->count() }}</p>
                        <p class="mt-1 text-xs text-green-100">Rumah sukan</p>
                    </div>
                </div>

                <div class="mt-5 rounded-lg bg-amber-100 p-4 text-sm leading-6 text-amber-950">
                    Peserta awam tidak perlu log masuk. Jika ada kesilapan selepas menghantar borang, sila hubungi urusetia untuk pembetulan.
                </div>
            </div>
        </div>
    </section>

    <section class="kb-container py-10">
        <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-green-800">Aliran Pendaftaran</p>
                <h2 class="text-2xl font-bold text-green-950">Mudah untuk peserta, tersusun untuk urusetia</h2>
            </div>
            <a href="{{ route('public.check') }}" class="text-sm font-semibold text-green-800 hover:text-green-950">Semak kod pendaftaran</a>
        </div>
        <div class="grid gap-4 md:grid-cols-3">
            <div class="kb-card p-5">
                <p class="font-semibold text-green-900">1. Isi Borang</p>
                <p class="mt-2 text-sm text-stone-600">Masukkan nama, umur, nombor telefon, rumah sukan dan acara pilihan.</p>
            </div>
            <div class="kb-card p-5">
                <p class="font-semibold text-green-900">2. Simpan Kod</p>
                <p class="mt-2 text-sm text-stone-600">Kod pendaftaran unik akan dipaparkan selepas borang dihantar.</p>
            </div>
            <div class="kb-card p-5">
                <p class="font-semibold text-green-900">3. Hadir Awal</p>
                <p class="mt-2 text-sm text-stone-600">Urusetia akan mengurus pengesahan peserta, acara dan senarai menunggu.</p>
            </div>
        </div>
    </section>

    <section class="bg-white py-10">
        <div class="kb-container">
            <div class="mb-5">
                <p class="text-sm font-semibold uppercase tracking-wide text-green-800">Acara Sukan</p>
                <h2 class="text-2xl font-bold text-green-950">Pilihan acara yang tersedia</h2>
                <p class="mt-2 max-w-2xl text-sm text-stone-600">Acara Kanak-Kanak dan Dewasa dipadankan mengikut umur. Acara Terbuka boleh disertai semua peserta.</p>
            </div>
            <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($sports as $sport)
                    <div class="rounded-lg border border-stone-200 bg-stone-50 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-green-950">{{ $sport->name }}</p>
                                <p class="mt-1 text-sm text-stone-600">{{ $sport->category }} · {{ $sport->duration_minutes ?? '-' }} minit</p>
                            </div>
                            <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800">{{ $sport->availabilityLabel() }}</span>
                        </div>
                        <p class="mt-3 text-xs text-stone-500">Kapasiti: {{ $sport->max_players_per_group ? $sport->max_players_per_group.' peserta/kumpulan' : 'Tiada had' }}</p>
                    </div>
                @empty
                    <div class="kb-card p-5 text-sm text-stone-600">Belum ada acara aktif.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="kb-container py-10">
        <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-start">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-green-800">Rumah Sukan</p>
                <h2 class="text-2xl font-bold text-green-950">Pilih rumah sukan semasa mendaftar</h2>
                <p class="mt-2 text-sm leading-6 text-stone-600">Rumah sukan membantu urusetia menyusun peserta dan laporan acara dengan lebih cepat.</p>
            </div>
            <div class="grid gap-3 sm:grid-cols-2">
                @foreach ($houses as $house)
                    <div class="flex items-center gap-3 rounded-lg border border-stone-200 bg-white p-4">
                        <span class="h-8 w-8 rounded" style="background: {{ $house->color ?? '#15803d' }}"></span>
                        <div>
                            <p class="font-semibold text-stone-900">{{ $house->name }}</p>
                            <p class="text-xs text-stone-500">{{ $house->description ?? 'Rumah sukan Kampung Budiman' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-public-layout>
