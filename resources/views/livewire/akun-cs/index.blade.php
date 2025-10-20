<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @forelse ($users as $user)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex flex-col">

                    <div class="p-6 flex items-center space-x-4">
                        @if ($user->profile_photo_url)
                        <img class="h-16 w-16 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                        @else
                        <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                            <x-icons.person class="h-10 w-10 text-gray-500" />
                        </div>
                        @endif
                        <div>
                            <p class="text-lg font-bold text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->kode_cs }}</p>
                        </div>
                    </div>

                    <div class="px-6 py-4 border-t border-b border-gray-200 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Closing</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $user->closing }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500">Poin</p>

                            @if ($user->poinDifference > 0)
                            {{-- POIN LEBIH (HIJAU) --}}
                            <p class="text-2xl font-bold text-green-600">{{ number_format($user->poin, 1, ',', '.') }}</p>
                            <span class="text-sm font-medium text-green-600">(+{{ number_format($user->poinDifference, 1, ',', '.') }})</span>

                            @elseif ($user->poinDifference < 0)
                                {{-- POIN KURANG (MERAH) --}}
                                <p class="text-2xl font-bold text-red-600">{{ number_format($user->poin, 1, ',', '.') }}</p>
                                <span class="text-sm font-medium text-red-600">({{ number_format($user->poinDifference, 1, ',', '.') }})</span>

                                @else
                                {{-- POIN PAS (NEUTRAL) --}}
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($user->poin, 1, ',', '.') }}</p>
                                @endif
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="flex justify-between text-sm font-medium text-gray-500 mb-1">
                            <span>Progress Poin</span>
                            <span>{{ number_format($user->poin, 1, ',', '.') }} / {{ number_format($targetPoin, 0) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full"
                                style="width: {{ $user->poinPercentage }}%; background-color: hsl({{ $user->poinColorHue }}, 90%, 45%);">
                            </div>
                        </div>
                    </div>

                </div>
                @empty
                <div class="lg:col-span-3 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 text-center">
                        Belum ada data Akun CS untuk ditampilkan.
                    </div>
                </div>
                @endforelse

            </div>

        </div>
    </div>
</div>