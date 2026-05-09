@if (session('success'))
    <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm font-semibold text-green-800">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm font-semibold text-red-800">{{ session('error') }}</div>
@endif
