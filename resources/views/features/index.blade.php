<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Features
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('message'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @livewire('features-table')
            @livewire('feature-form')
        </div>
    </div>
</x-app-layout>