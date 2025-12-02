<x-filament-panels::page>
    <div class="space-y-6">
        @if($isLoading)
            <div class="flex justify-center items-center py-12">
                <x-filament::loading-indicator class="h-12 w-12" />
            </div>
        @elseif($this->isNotLoggedIn())
            {{-- Not Logged In - Show QR Code --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="text-center">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                        {{ __('whatsapp_setting.qr_code.title') }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        {{ $this->getMessage() ?? __('whatsapp_setting.qr_code.description') }}
                    </p>

                    @if($this->getQrCode())
                        <div class="flex justify-center mb-6">
                            <div class="bg-white p-4 rounded-lg shadow-lg inline-block">
                                <img
                                    src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(300)->generate($this->getQrCode())) }}"
                                    alt="WhatsApp QR Code"
                                    class="w-72 h-72"
                                />
                            </div>
                        </div>

                        <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                            <x-filament::loading-indicator class="h-4 w-4" wire:poll.5s="checkStatus" />
                            <span>{{ __('whatsapp_setting.qr_code.auto_refresh') }}</span>
                        </div>
                    @else
                        <div class="text-red-600 dark:text-red-400">
                            {{ __('whatsapp_setting.qr_code.not_available') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                            {{ __('whatsapp_setting.instructions.title') }}
                        </h3>
                        <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                            <ol class="list-decimal list-inside space-y-1">
                                <li>{{ __('whatsapp_setting.instructions.step_1') }}</li>
                                <li>{{ __('whatsapp_setting.instructions.step_2') }}</li>
                                <li>{{ __('whatsapp_setting.instructions.step_3') }}</li>
                                <li>{{ __('whatsapp_setting.instructions.step_4') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

        @elseif($this->isLoggedIn())
            {{-- Logged In - Show User Info --}}
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center space-x-6">
                    @if($this->getUser()['profilePicture'])
                        <img
                            src="{{ $this->getUser()['profilePicture'] }}"
                            alt="{{ $this->getUser()['name'] }}"
                            class="w-24 h-24 rounded-full object-cover border-4 border-green-500"
                        />
                    @else
                        <div class="w-24 h-24 rounded-full bg-green-500 flex items-center justify-center border-4 border-green-600">
                            <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    @endif

                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $this->getUser()['name'] ?? '-' }}
                            </h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                                {{ __('whatsapp_setting.status.connected') }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span class="font-mono">{{ $this->getUser()['phone'] ?? '-' }}</span>
                            </div>

                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                                <span class="font-mono text-sm">{{ $this->getUser()['id'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 dark:text-green-300">
                            {{ __('whatsapp_setting.status.connected_description') }}
                        </p>
                    </div>
                </div>
            </div>

        @else
            {{-- Unknown Status --}}
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">
                            {{ __('whatsapp_setting.status.unknown') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Auto-refresh for QR code --}}
    @if($this->isNotLoggedIn())
        <div wire:poll.10s="checkStatus"></div>
    @endif
</x-filament-panels::page>
