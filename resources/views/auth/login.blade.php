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

    <div class="min-h-screen w-full flex flex-col items-center justify-center p-4
                bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50
                dark:from-slate-900 dark:via-slate-800 dark:to-slate-900
                transition-colors duration-300 overflow-hidden relative">

        {{-- Decorative elements (blobs) --}}
        <div class="fixed top-1/4 -right-10 sm:-right-20 w-52 h-52 sm:w-64 sm:h-64 md:w-96 md:h-96 bg-blue-200 dark:bg-blue-500/70 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-30 animate-blob"></div>
        <div class="fixed bottom-1/4 -left-10 sm:-left-20 w-52 h-52 sm:w-64 sm:h-64 md:w-96 md:h-96 bg-purple-200 dark:bg-purple-500/70 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-30 animate-blob animation-delay-2000"></div>
        <div class="fixed -bottom-5 sm:-bottom-10 right-1/4 w-52 h-52 sm:w-64 sm:h-64 md:w-80 md:h-80 bg-pink-200 dark:bg-pink-500/70 rounded-full mix-blend-multiply filter blur-3xl opacity-40 dark:opacity-30 animate-blob animation-delay-4000"></div>

        <div class="w-full max-w-xs sm:max-w-sm z-10">
            <div class="text-center mb-6 sm:mb-8">
                <div class="inline-block p-3 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl mb-3 shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white"><path d="M4.5 3h15"/><path d="M6 3v16a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V3"/><path d="M6 14h12"/></svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400">LabSys</h1>
                <p class="text-gray-500 dark:text-gray-400 mt-1 text-xs sm:text-sm">Laboratory Information System</p>
            </div>

            <div class="bg-white dark:bg-slate-800 shadow-xl rounded-xl overflow-hidden relative">
                <div class="px-6 py-5 pt-6">
                    {{-- PERBAIKAN 1: Memastikan warna teks Login --}}
                    <h2 class="text-xl font-bold text-center text-gray-900 dark:text-black">Login</h2>
                    <p class="text-center text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Enter your NPM and password to access the system
                    </p>
                </div>

                <x-validation-errors class="mb-3 px-6 text-xs" />
                @session('status') <div class="mb-3 mx-6 font-medium text-xs text-green-600 dark:text-green-400 p-2.5 rounded-md bg-green-50 dark:bg-green-700/30 border border-green-300 dark:border-green-600">{{ $value }}</div> @endsession
                @if (session('success')) <div class="mb-3 mx-6 font-medium text-xs text-green-600 dark:text-green-400 p-2.5 rounded-md bg-green-50 dark:bg-green-700/30 border border-green-300 dark:border-green-600" role="alert">{{ session('success') }}</div> @endif

                <form method="POST" action="{{ route('login') }}" class="px-6 pb-6 pt-1">
                    @csrf
                    @php
                        $inputHeightClass = 'h-9';
                        $inputPaddingYClass = 'py-2';
                        $inputPaddingLeftWithIconClass = 'pl-10';
                        $inputPaddingRightDefaultClass = 'pr-3';
                        $inputPaddingRightWithIconClass = 'pr-10';

                        $roleItemPaddingYClass = $inputPaddingYClass;
                        $roleItemPaddingXClass = 'px-3';
                    @endphp
                    <div class="space-y-4">
                        <div>
                            <x-label for="npm_login_input" value="{{ __('NPM (Student ID)') }}" class="text-gray-600 dark:text-gray-400 mb-1 text-xs" />
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><circle cx="12" cy="12" r="4"/><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"/></svg></span>
                                <x-input id="npm_login_input" name="npm" type="text" placeholder="Enter your NPM" :value="old('npm')" required autofocus
                                         class="block w-full {{ $inputHeightClass }} {{ $inputPaddingYClass }} {{ $inputPaddingLeftWithIconClass }} {{ $inputPaddingRightDefaultClass }} text-sm text-gray-800 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 rounded-md shadow-sm
                                                @error('npm') border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500
                                                @else border-gray-300 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-600 @enderror" />
                            </div>
                             <x-input-error for="npm" class="mt-1 text-xs" />
                        </div>

                        <div x-data="{ showPasswordLogin: false }">
                            <x-label for="password_login_input" value="{{ __('Password') }}" class="text-gray-600 dark:text-gray-400 mb-1 text-xs" />
                            <div class="relative">
                                 <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></span>
                                <input id="password_login_input" name="password" x-bind:type="showPasswordLogin ? 'text' : 'password'" placeholder="Enter your password" required autocomplete="current-password"
                                       class="block w-full {{ $inputHeightClass }} {{ $inputPaddingYClass }} {{ $inputPaddingLeftWithIconClass }} {{ $inputPaddingRightWithIconClass }} text-sm text-gray-800 dark:text-gray-200 bg-white dark:bg-slate-700 placeholder-gray-400 dark:placeholder-gray-500 border rounded-md shadow-sm
                                              @error('password') border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500
                                              @else border-gray-300 dark:border-slate-600 focus:border-blue-500 dark:focus:border-blue-600 focus:ring-1 focus:ring-blue-500 dark:focus:ring-blue-600 @enderror" />
                                <button type="button" @click="showPasswordLogin = !showPasswordLogin" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg x-show="!showPasswordLogin" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <svg x-show="showPasswordLogin" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                </button>
                            </div>
                            <x-input-error for="password" class="mt-1 text-xs" />
                        </div>

                        <div class="space-y-2" x-data="{ selectedRoleLogin: 'mahasiswa' }">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Select Role</label>
                            <div class="space-y-2">
                                <label for="mahasiswa_role_login_ui"
                                       class="flex items-center rounded-md border bg-white dark:bg-slate-700 {{ $inputHeightClass }} {{ $roleItemPaddingYClass }} {{ $roleItemPaddingXClass }} cursor-pointer transition-all duration-150 ease-in-out shadow-sm"
                                       :class="{
                                           'border-blue-500 dark:border-blue-600 ring-1 ring-blue-500 dark:ring-blue-600': selectedRoleLogin === 'mahasiswa',
                                           'border-gray -300 dark:border-slate-600 hover:border-gray-400 dark:hover:border-slate-500': selectedRoleLogin !== 'mahasiswa'
                                       }">
                                    <input type="radio" name="role_ui_selector_login" value="mahasiswa" id="mahasiswa_role_login_ui" x-model="selectedRoleLogin" class="form-radio h-2 w-2 text-blue-600 border-gray-300 dark:border-slate-500 focus:ring-blue-500 dark:focus:ring-offset-slate-800 dark:focus:ring-blue-600 dark:bg-slate-600 dark:checked:bg-blue-500 dark:checked:border-transparent shrink-0">
                                    <div class="flex items-center space-x-2.7 ml-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" :class="selectedRoleLogin === 'mahasiswa' ? 'text-blue-500' : 'text-gray-400 dark:text-black-500'"><path d="M18 20a6 6 0 0 0-12 0"/><circle cx="12" cy="10" r="4"/><circle cx="12" cy="12" r="10"/></svg>
                                        {{-- PERBAIKAN 2: Warna teks role saat terpilih (mode terang tetap gelap) --}}
                                        <span class="text-xs" :class="selectedRoleLogin === 'mahasiswa' ? 'text-gray-800 dark:text-gray-500 font-medium' : 'text-gray-700 dark:text-gray-400'">Mahasiswa (Student)</span>
                                    </div>
                                </label>
                                <label for="aslab_role_login_ui"
                                       class="flex items-center rounded-md border bg-white dark:bg-slate-700 {{ $inputHeightClass }} {{ $roleItemPaddingYClass }} {{ $roleItemPaddingXClass }} cursor-pointer transition-all duration-150 ease-in-out shadow-sm"
                                       :class="{
                                           'border-purple-500 dark:border-purple-600 ring-1 ring-purple-500 dark:ring-purple-600': selectedRoleLogin === 'aslab',
                                           'border-gray-300 dark:border-slate-600 hover:border-gray-400 dark:hover:border-slate-500': selectedRoleLogin !== 'aslab'
                                       }">
                                    <input type="radio" name="role_ui_selector_login" value="aslab" id="aslab_role_login_ui" x-model="selectedRoleLogin" class="form-radio h-2 w-2 text-purple-600 border-gray-300 dark:border-slate-500 focus:ring-purple-500 dark:focus:ring-offset-slate-800 dark:focus:ring-purple-600 dark:bg-slate-600 dark:checked:bg-purple-500 dark:checked:border-transparent shrink-0">
                                    <div class="flex items-center space-x-2.7 ml-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0" :class="selectedRoleLogin === 'aslab' ? 'text-purple-500' : 'text-gray-400 dark:text-gray-500'"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                        <span class="text-xs" :class="selectedRoleLogin === 'aslab' ? 'text-gray-800 dark:text-gray-500 font-medium' : 'text-gray-700 dark:text-gray-400'">Aslab (Lab Assistant)</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full h-10 flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                            Sign In
                        </button>
                    </div>
                </form>

                <div class="px-6 py-4 border-t border-gray-200 dark:border-slate-700/60 mt-4">
                    <p class="text-xs text-center text-gray-500 dark:text-gray-400">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            Sign up
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>


