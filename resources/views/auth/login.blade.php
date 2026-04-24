@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10">
    <h2 class="text-2xl font-semibold mb-6">Login</h2>

    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <!-- @csrf -->

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium">Email</label>
            <input id="email" name="email" type="email" required
                   value="{{ old('email') }}"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium">Password</label>
            <input id="password" name="password" type="password" required
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="remember" class="inline-flex items-center">
                <input id="remember" name="remember" type="checkbox"
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <span class="ml-2 text-sm">Remember me</span>
            </label>
        </div>

        <div>
            <button type="submit"
                    class="w-full py-2 px-4 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                Login
            </button>
        </div>
    </form>
</div>
@endsection