<x-guest-layout>
    <x-slot name="title">Reset Password</x-slot>

    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">

            {{-- Branding & Header --}}
            <div class="text-center space-y-2">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm">SD</div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">SupportDesk</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 pt-2">Reset your password</h1>
                <p class="text-xs text-gray-500 leading-relaxed">
                    {{ __('Forgot your password? No problem. Enter your email address and we will send you a password reset link.') }}
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email Address')" />
                    <x-text-input id="email"
                                  type="email"
                                  name="email"
                                  :value="old('email')"
                                  placeholder="name@supportdesk.test"
                                  required autofocus />
                    <x-input-error :messages="$errors->get('email')" />
                </div>

                <div class="pt-2">
                    <x-primary-button class="w-full py-2.5">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </div>

                <div class="text-center pt-2">
                    <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                        ← Back to Sign In
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-guest-layout>

