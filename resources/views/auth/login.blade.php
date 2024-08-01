<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.4.1/css/bootstrap.min.css">

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <x-primary-button class="btn btn-block btn-info">
            {{ __('Log in') }}
        </x-primary-button>

        <hr>

         <!-- Google Login -->
        <a href="{{ route('auth.google') }}" class="btn btn-danger rounded-md btn-block">
            <i class="fab fa-google fa-fw"></i> Login with Google
          </a>
        
        <!-- Facebook Login -->
          <a href="{{ url('auth/facebook') }}" class="btn btn-primary rounded-md btn-block">
            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
          </a>

          <hr>

        <!-- Forget Password -->

        <div class="text-center">
            @if (Route::has('password.request'))
                <a class="font-time" href="{{ route('password.request') }}">{{ __('Forgot password?') }} </a>
            @endif
        </div>

         <!-- Register -->

        <div class="text-center">
          <a class="font-time" href="{{ route('register') }}">Create an Account!</a>
        </div>
    </form>
</x-guest-layout>
