<x-guest-layout>
    <x-slot name="title">Register Employee Account</x-slot>

    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">

            {{-- Header & Branding --}}
            <div class="text-center space-y-2">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm">SD</div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">SupportDesk</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 pt-2">Create Employee Account</h1>
                <p class="text-xs text-gray-500">Sign up to submit and monitor your workplace IT tickets.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                {{-- Name --}}
                <div>
                    <x-input-label for="name" :value="__('Full Name')" />
                    <x-text-input id="name"
                                  type="text"
                                  name="name"
                                  :value="old('name')"
                                  placeholder="Jane Doe"
                                  required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" />
                </div>

                {{-- Email Address --}}
                <div>
                    <x-input-label for="email" :value="__('Work Email Address')" />
                    <x-text-input id="email"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  placeholder="jane.doe@supportdesk.test"
                                  required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                {{-- Password --}}
                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password"
                                  type="password"
                                  name="password"
                                  placeholder="••••••••"
                                  required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" />
                </div>

                {{-- Confirm Password --}}
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation"
                                  type="password"
                                  name="password_confirmation"
                                  placeholder="••••••••"
                                  required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full py-2.5">
                        {{ __('Create Account') }}
                    </x-primary-button>
                </div>

                <div class="text-center pt-2">
                    <p class="text-xs text-gray-500">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>

