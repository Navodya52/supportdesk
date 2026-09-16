<x-guest-layout>
    <x-slot name="title">Verify Email</x-slot>

    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 space-y-6">

            {{-- Branding & Header --}}
            <div class="text-center space-y-2">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm">SD</div>
                    <span class="text-xl font-bold tracking-tight text-gray-900">SupportDesk</span>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-gray-900 pt-2">Verify your email</h1>
                <p class="text-xs text-gray-500 leading-relaxed">
                    {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
                </p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="flex items-center gap-2.5 text-xs text-green-800 bg-green-50 border border-green-200 rounded-lg p-3">
                    <svg class="w-4 h-4 shrink-0 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ __('A new verification link has been sent to your email address.') }}</span>
                </div>
            @endif

            <div class="space-y-3 pt-2">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <x-primary-button class="w-full py-2.5">
                        {{ __('Resend Verification Email') }}
                    </x-primary-button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="text-center">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-gray-500 hover:text-gray-800 hover:underline">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>

