<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('projects.update', $project) }}">
                    @csrf
                    @method('PUT')

                    <x-input-label for="naziv_projekta" :value="__('Project Name')" />
                    <x-text-input id="naziv_projekta" class="block mt-1 w-full" type="text" name="naziv_projekta" :value="old('naziv_projekta', $project->naziv_projekta)" required autofocus />

                    <x-input-label for="opis_projekta" :value="__('Description')" class="mt-4"/>
                    <textarea name="opis_projekta" id="opis_projekta" class="block mt-1 w-full">{{ old('opis_projekta', $project->opis_projekta) }}</textarea>

                    <x-input-label for="cijena_projekta" :value="__('Price')" class="mt-4"/>
                    <x-text-input id="cijena_projekta" class="block mt-1 w-full" type="number" step="0.01" name="cijena_projekta" :value="old('cijena_projekta', $project->cijena_projekta)" />

                    <x-input-label for="datum_pocetka" :value="__('Start Date')" class="mt-4"/>
                    <x-text-input id="datum_pocetka" class="block mt-1 w-full" type="date" name="datum_pocetka" :value="old('datum_pocetka', $project->datum_pocetka)" required />

                    <x-input-label for="datum_zavrsetka" :value="__('End Date')" class="mt-4"/>
                    <x-text-input id="datum_zavrsetka" class="block mt-1 w-full" type="date" name="datum_zavrsetka" :value="old('datum_zavrsetka', $project->datum_zavrsetka)" />

                    <x-input-label for="clanovi" :value="__('Team Members')" class="mt-4"/>
                    <select name="clanovi[]" id="clanovi" class="block mt-1 w-full" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}"
                                    @if($project->clanovi->pluck('id')->contains($user->id)) selected @endif
                            >{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <x-input-label for="obavljeni_poslovi" :value="__('Done Tasks')" class="mt-4"/>
                    <textarea name="obavljeni_poslovi" id="obavljeni_poslovi" class="block mt-1 w-full">{{ old('obavljeni_poslovi', $project->obavljeni_poslovi) }}</textarea>

                    <x-primary-button class="mt-4">{{ __('Update') }}</x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
