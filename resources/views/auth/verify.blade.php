@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-semibold mb-4 text-gray-800">Please verify your email</h1>
    <p class="mb-6 text-gray-600">
        A verification link has been sent to the email address you provided. Please check your inbox and click on the link to activate your account.
    </p>
    <p class="text-gray-600">
        If you did not receive the email, you can
        <a href="{{ route('verification.resend') }}" id="resend-link" class="text-indigo-600 hover:underline">
            resend the verification email
        </a>.
    </p>

    <form id="resend-form" action="{{ route('verification.resend') }}" method="POST" class="hidden">
        @csrf
    </form>
</div>
@endsection