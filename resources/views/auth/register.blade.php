<x-guest-layout>
    @push('styles')
    <style>
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        @keyframes blob { 0% { transform: translate(0px, 0px) scale(1); } 33% { transform: translate(30px, -50px) scale(1.1); } 66% { transform: translate(-20px, 20px) scale(0.9); } 100% { transform: translate(0px, 0px) scale(1); } }
        html, body { height: 100%; }
    </style>
    @endpush

    {{-- Div utama untuk background gradient dan pemusatan konten --}}
    <div class="min-h-screen flex flex-col items-center justify-center p-4
                bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50
                dark:from-gray-800 dark:via-black dark:to-gray-800
                transition-colors duration-300 overflow-hidden relative">

        {{-- Decorative elements (blobs) --}}
        <div class="fixed top-20 right-20 w-64 h-64 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-30 animate-blob"></div>
        <div class="fixed bottom-20 left-20 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-30 animate-blob animation-delay-2000"></div>
        <div class="fixed bottom-40 right-40 w-64 h-64 bg-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-30 animate-blob animation-delay-4000"></div>

        <div class="w-full max-w-md z-10">
            {{-- Logo LabSys dan Judul Aplikasi --}}
            <div class="text-center mb-8">
                <div class="inline-block p-3 sm:p-4 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-3 sm:mb-4 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" sm:width="48" sm:height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white lucide lucide-beaker"><path d="M4.5 3h15"/><path d="M6 3v16a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V3"/><path d="M6 14h12"/></svg>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400">LabSys</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1 sm:mt-2 text-sm sm:text-base">Laboratory Information System</p>
            </div>

            <x-authentication-card>
                <x-slot name="logo">
                    <!-- Slot logo disediakan tapi dikosongkan -->
                </x-slot>

                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>
                <div class="p-6 pt-8 space-y-1">
                    <h2 class="text-xl sm:text-2xl font-bold text-center text-gray-900 dark:text-black">Create Account</h2>
                    <p class="text-center text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                        Register for a new student account to access the system
                    </p>
                </div>

                <x-validation-errors class="mb-4 px-6" />
                @if (session('success')) <div class="mb-4 px-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert"><p>{{ session('success') }}</p></div> @endif
                @if (session('error')) <div class="mb-4 px-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert"><p>{{ session('error') }}</p></div> @endif

                <form method="POST" action="{{ route('register') }}" class="p-6 pt-2 sm:pt-4">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <x-label for="npm" value="{{ __('NPM (Student ID)') }}" class="dark:text-gray-300 mb-1 text-sm" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 lucide lucide-at-sign"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg></span>
                                <x-input id="npm" name="npm" type="text" placeholder="Enter your NPM" :value="old('npm')" required class="block w-full h-11 sm:h-12 pl-10 pr-3 py-2 text-sm sm:text-base dark:bg-gray-500 dark:text-white dark:placeholder-gray-400 @error('npm') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500" />
                            </div>
                            <x-input-error for="npm" class="mt-1 text-xs" />
                        </div>

                        <div>
                            <x-label for="name" value="{{ __('Full Name') }}" class="dark:text-gray-300 mb-1 text-sm" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 lucide lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>
                                <x-input id="name" class="block w-full h-11 sm:h-12 pl-10 pr-3 py-2 text-sm sm:text-base dark:bg-gray-500 dark:text-white dark:placeholder-gray-400 @error('name') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Enter your full name" />
                            </div>
                             <x-input-error for="name" class="mt-1 text-xs" />
                        </div>

                        <div>
                            <x-label for="email" value="{{ __('Email Address') }}" class="dark:text-gray-300 mb-1 text-sm" />
                             <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 lucide lucide-mail"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg></span>
                                <x-input id="email" class="block w-full h-11 sm:h-12 pl-10 pr-3 py-2 text-sm sm:text-base dark:bg-gray-500 dark:text-white dark:placeholder-gray-400 @error('email') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Enter your email address"/>
                            </div>
                            <x-input-error for="email" class="mt-1 text-xs" />
                        </div>

                        <div x-data="{ showPasswordRegister: false }">
                            <x-label for="password" value="{{ __('Password') }}" class="dark:text-gray-300 mb-1 text-sm" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 lucide lucide-lock"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                                <input id="password" name="password" x-bind:type="showPasswordRegister ? 'text' : 'password'" placeholder="Create a password" required autocomplete="new-password"
                                       class="block w-full h-11 sm:h-12 pl-10 pr-10 py-2 text-sm sm:text-base dark:bg-gray-500 dark:text-white dark:placeholder-gray-400 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @else border-gray-300 dark:border-gray-600 @enderror"/>
                                <button type="button" @click="showPasswordRegister = !showPasswordRegister" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg x-show="!showPasswordRegister" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="showPasswordRegister" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                </button>
                            </div>
                           <x-input-error for="password" class="mt-1 text-xs" />
                        </div>

                        <div x-data="{ showConfirmPasswordRegister: false }">
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" class="dark:text-gray-300 mb-1 text-sm" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 lucide lucide-lock"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                                <input id="password_confirmation" name="password_confirmation" x-bind:type="showConfirmPasswordRegister ? 'text' : 'password'" placeholder="Confirm your password" required autocomplete="new-password"
                                       class="block w-full h-11 sm:h-12 pl-10 pr-10 py-2 text-sm sm:text-base dark:bg-gray-500 dark:text-white dark:placeholder-gray-400 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 dark:border-gray-600"/>
                                 <button type="button" @click="showConfirmPasswordRegister = !showConfirmPasswordRegister" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg x-show="!showConfirmPasswordRegister" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="showConfirmPasswordRegister" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                        <div class="mt-4">
                            <x-label for="terms">
                                <div class="flex items-center">
                                    <x-checkbox name="terms" id="terms" required />
                                    <div class="ms-2 text-sm text-gray-600 dark:text-gray-300">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                'terms_of_service' => '<a target="blank" href="'.route('terms.show').'" class="underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'._('Terms of Service').'</a>',
                                                'privacy_policy' => '<a target="blank" href="'.route('policy.show').'" class="underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'._('Privacy Policy').'</a>',
                                        ]) !!}
                                    </div>
                                </div>
                                <x-input-error for="terms" class="mt-2" />
                            </x-label>
                        </div>
                    @endif

                    <div class="flex items-center justify-end mt-6">
                        <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>
                        <button type="submit" class="ms-4 h-11 sm:h-12 inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm sm:text-base font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 lucide lucide-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                            {{ __('Register') }}
                        </button>
                    </div>
                </form>
            </x-authentication-card>
        </div>
    </div>
</x-guest-layout>