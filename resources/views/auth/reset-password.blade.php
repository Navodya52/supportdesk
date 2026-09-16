<x-guest-layout>
    <x-slot name="title">Set New Password</x-slot>

    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">

            {{-- Branding & Header --}}
            <div class="text-center space-y-2">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm">SD</div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">SupportDesk</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 pt-2">Set new password</h1>
                <p class="text-xs text-gray-500">Choose a new password to secure your account.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email"
                                  type="email"
                                  name="email"
                                  :value="old('email', $request->email)"
                                  required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('New Password')" />
                    <x-text-input id="password"
                                  type="password"
                                  name="password"
                                  placeholder="••••••••"
                                  required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm New Password')" />
                    <x-text-input id="password_confirmation"
                                  type="password"
                                  name="password_confirmation"
                                  placeholder="••••••••"
                                  required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full py-2.5">
                        {{ __('Reset Password') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>

