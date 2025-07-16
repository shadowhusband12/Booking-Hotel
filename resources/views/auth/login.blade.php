<x-guest-layout>
    @section('title', $title)
    @section('description',
        'Jelajahi berbagai pilihan kamar yang sesuai dengan kebutuhan Anda. Dari liburan keluarga hingga
        perjalanan bisnis, kami punya yang Anda cari.')

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required
                    autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="relative mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <div class="flex items-center">
                    <x-text-input id="password" class="block w-full pr-10" type="password" name="password" required
                        autocomplete="current-password" />
                    <div class="absolute inset-y-0 right-0 top-5 m-0 flex items-center pr-3">
                        <label for="togglePassword" class="cursor-pointer">
                            <i data-feather="eye-off" class="w-5 text-xs text-gray-600 dark:text-gray-400"
                                id="eye-open"></i>
                            <i data-feather="eye" class="hidden w-5 text-xs text-gray-600 dark:text-gray-400"
                                id="eye-closed"></i>
                            <input type="checkbox" class="hidden" id="togglePassword" />
                        </label>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>


            <!-- Remember Me -->
            <div class="mt-4 flex justify-between">
                <label for="remember_me" class="flex-none items-center">
                    <input id="remember_me" type="checkbox"
                        class="flex-none rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500 dark:border-gray-700 dark:bg-gray-900 dark:focus:ring-orange-600 dark:focus:ring-offset-gray-800"
                        name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="flex-col rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <div class="mt-4 flex items-center justify-end">
                <a class="flex-col rounded-md text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    href="{{ route('register') }}">
                    {{ __('Register') }}
                </a>
                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </x-guest-layout>
