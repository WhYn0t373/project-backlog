@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-4 bg-blue-100 border border-blue-200 rounded">
            <p class="text-blue-800">
                A verification email has been sent to your email address. Please verify before logging in.
            </p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input id="email" type="email" name="email" required autofocus
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" type="password" name="password" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"/>
        </div>

        <div class="mb-4 flex items-center">
            <input id="remember" type="checkbox" name="remember"
                class="mr-2 rounded">
            <label for="remember" class="text-sm text-gray-600">Remember Me</label>
        </div>

        <div class="flex items-center justify-between">
            <button type="submit"
                class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                Log In
            </button>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-indigo-600 hover:underline">
                    Forgot Your Password?
                </a>
            @endif
        </div>
    </form>
</div>
@endsection