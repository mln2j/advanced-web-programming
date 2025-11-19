<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $project->naziv_projekta }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <strong>Opis:</strong> {{ $project->opis_projekta }}<br>
                <strong>Cijena:</strong> {{ $project->cijena_projekta }}<br>
                <strong>Obavljeni poslovi:</strong> {{ $project->obavljeni_poslovi }}<br>
                <strong>Datum početka:</strong> {{ $project->datum_pocetka }}<br>
                <strong>Datum završetka:</strong> {{ $project->datum_zavrsetka }}<br>
                <strong>Voditelj:</strong> {{ $project->voditelj->name }}<br>
                <strong>Članovi tima:</strong>
                <ul class="ml-4">
                    @foreach($project->clanovi as $clan)
                        <li>{{ $clan->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="mt-4">
                <a href="{{ route('projects.edit', $project) }}"><x-primary-button>Uredi</x-primary-button></a>
            </div>
        </div>
    </div>
</x-app-layout>
