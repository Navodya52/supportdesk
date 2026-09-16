<x-guest-layout>
    <x-slot name="title">Confirm Password</x-slot>

    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">

            {{-- Branding & Header --}}
            <div class="text-center space-y-2">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm">SD</div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">SupportDesk</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 pt-2">Security Confirmation</h1>
                <p class="text-xs text-gray-500 leading-relaxed">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
                @csrf

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                                  type="password"
                                  name="password"
                                  placeholder="••••••••"
                                  required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full py-2.5">
                        {{ __('Confirm Password') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>

