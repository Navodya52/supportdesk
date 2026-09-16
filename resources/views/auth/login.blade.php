<x-guest-layout>
    <x-slot name="title">Sign In</x-slot>

    <div class="w-full max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden grid grid-cols-1 lg:grid-cols-12">

            {{-- Left / Brand Area --}}
            <div class="lg:col-span-5 bg-gray-900 text-white p-8 lg:p-10 flex flex-col justify-between border-b lg:border-b-0 lg:border-r border-gray-800">
                <div class="space-y-6">
                    {{-- Logo & Product Name --}}
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-500 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-sm shrink-0">SD</div>
                        <div>
                            <span class="text-xl font-bold tracking-tight text-white block">SupportDesk</span>
                            <span class="text-xs font-medium text-gray-400 block">IT Helpdesk & Operations</span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="space-y-3 pt-2">
                        <h2 class="text-lg font-semibold text-white">Internal IT Support Portal</h2>
                        <p class="text-sm text-gray-300 leading-relaxed">
                            Report issues, track ticket progress, and collaborate directly with your support engineers.
                        </p>
                    </div>

                    {{-- Features List --}}
                    <div class="space-y-3 pt-2 text-xs text-gray-300">
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Rapid ticket submission & automated priority queues</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Role-based access for Employees, Agents & Admins</span>
                        </div>
                        <div class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-indigo-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Real-time resolution history & team discussion</span>
                        </div>
                    </div>
                </div>

                {{-- Demo Accounts Helper for Quick Testing --}}
                <div class="mt-8 pt-6 border-t border-gray-800">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2.5">Quick Demo Accounts</p>
                    <div class="space-y-1.5 text-xs text-gray-300">
                        <div class="flex items-center justify-between p-1.5 rounded bg-gray-800/70 border border-gray-700/60 cursor-pointer hover:bg-gray-700/70 transition-colors"
                             onclick="fillDemo('admin@supportdesk.test')">
                            <span class="font-medium text-purple-300">Admin:</span>
                            <span class="font-mono text-gray-300">admin@supportdesk.test</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 rounded bg-gray-800/70 border border-gray-700/60 cursor-pointer hover:bg-gray-700/70 transition-colors"
                             onclick="fillDemo('agent@supportdesk.test')">
                            <span class="font-medium text-indigo-300">Agent:</span>
                            <span class="font-mono text-gray-300">agent@supportdesk.test</span>
                        </div>
                        <div class="flex items-center justify-between p-1.5 rounded bg-gray-800/70 border border-gray-700/60 cursor-pointer hover:bg-gray-700/70 transition-colors"
                             onclick="fillDemo('employee@supportdesk.test')">
                            <span class="font-medium text-sky-300">Employee:</span>
                            <span class="font-mono text-gray-300">employee@supportdesk.test</span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2 text-center">Password for all: <code class="text-gray-200">password</code></p>
                </div>
            </div>

            {{-- Right / Login Form --}}
            <div class="lg:col-span-7 p-8 lg:p-10 flex flex-col justify-center bg-white">
                <div class="max-w-md w-full mx-auto space-y-6">

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Sign in to your account</h1>
                        <p class="text-sm text-gray-500 mt-1">Please enter your credentials to access the support desk.</p>
                    </div>

                    {{-- Session Status Flash --}}
                    <x-auth-session-status :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        {{-- Email Address --}}
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" />
                            <x-text-input id="email"
                                          type="email"
                                          name="email"
                                          :value="old('email')"
                                          placeholder="name@supportdesk.test"
                                          required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <x-input-label for="password" :value="__('Password')" class="mb-0" />
                                @if (Route::has('password.request'))
                                    <a class="text-xs text-indigo-600 hover:text-indigo-800 font-medium hover:underline focus:outline-none focus:ring-1 focus:ring-indigo-500 rounded"
                                       href="{{ route('password.request') }}">
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif
                            </div>
                            <x-text-input id="password"
                                          type="password"
                                          name="password"
                                          placeholder="••••••••"
                                          required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center justify-between pt-1">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-4 h-4"
                                       name="remember">
                                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me on this device') }}</span>
                            </label>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <x-primary-button class="w-full py-2.5">
                                {{ __('Sign In') }}
                            </x-primary-button>
                        </div>

                        {{-- Registration Link --}}
                        @if (Route::has('register'))
                            <div class="text-center pt-2">
                                <p class="text-xs text-gray-500">
                                    Don't have an account yet?
                                    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-800 hover:underline">
                                        Register as Employee
                                    </a>
                                </p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function fillDemo(email) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = 'password';
            }
        }
    </script>
</x-guest-layout>

