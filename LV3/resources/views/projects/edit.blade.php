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

                    @if($isVoditelj)
                        <!-- Za voditelja: normalni inputi -->
                        <x-input-label for="naziv_projekta" :value="__('Naziv projekta')" />
                        <x-text-input id="naziv_projekta" class="block mt-1 w-full" type="text" name="naziv_projekta" :value="old('naziv_projekta', $project->naziv_projekta)" required autofocus />

                        <x-input-label for="opis_projekta" :value="__('Opis')" class="mt-4"/>
                        <textarea name="opis_projekta" id="opis_projekta" class="block mt-1 w-full rounded">{{ old('opis_projekta', $project->opis_projekta) }}</textarea>

                        <x-input-label for="cijena_projekta" :value="__('Cijena')" class="mt-4"/>
                        <x-text-input id="cijena_projekta" class="block mt-1 w-full" type="number" step="0.01" name="cijena_projekta" :value="old('cijena_projekta', $project->cijena_projekta)" />

                        <x-input-label for="datum_pocetka" :value="__('Datum početka')" class="mt-4"/>
                        <x-text-input id="datum_pocetka" class="block mt-1 w-full" type="date" name="datum_pocetka" :value="old('datum_pocetka', $project->datum_pocetka)" required />

                        <x-input-label for="datum_zavrsetka" :value="__('Datum završetka')" class="mt-4"/>
                        <x-text-input id="datum_zavrsetka" class="block mt-1 w-full" type="date" name="datum_zavrsetka" :value="old('datum_zavrsetka', $project->datum_zavrsetka)" />

                        <x-input-label for="obavljeni_poslovi" :value="__('Obavljeni poslovi')" class="mt-4"/>
                        <textarea name="obavljeni_poslovi" id="obavljeni_poslovi" class="block mt-1 w-full rounded">{{ old('obavljeni_poslovi', $project->obavljeni_poslovi) }}</textarea>

                        <x-input-label for="clanovi" :value="__('Članovi tima')" class="mt-4"/>
                        <select name="clanovi[]" id="clanovi" class="block mt-1 w-full" multiple>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}"
                                        @if($project->clanovi->pluck('id')->contains($user->id)) selected @endif
                                >{{ $user->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <!-- Za članove: prikaz tekstualnih podataka -->
                        <x-input-label :value="__('Naziv projekta')" />
                        <div class="block mt-1 w-full bg-gray-100 rounded px-2 py-1">{{ $project->naziv_projekta }}</div>

                        <x-input-label :value="__('Opis')" class="mt-4"/>
                        <div class="block mt-1 w-full bg-gray-100 rounded px-2 py-1">{{ $project->opis_projekta }}</div>

                        <x-input-label :value="__('Cijena')" class="mt-4"/>
                        <div class="block mt-1 w-full bg-gray-100 rounded px-2 py-1">{{ $project->cijena_projekta }}</div>

                        <x-input-label :value="__('Datum početka')" class="mt-4"/>
                        <div class="block mt-1 w-full bg-gray-100 rounded px-2 py-1">{{ $project->datum_pocetka }}</div>

                        <x-input-label :value="__('Datum završetka')" class="mt-4"/>
                        <div class="block mt-1 w-full bg-gray-100 rounded px-2 py-1">{{ $project->datum_zavrsetka }}</div>

                        <x-input-label for="obavljeni_poslovi" :value="__('Obavljeni poslovi')" class="mt-4"/>
                        <textarea name="obavljeni_poslovi" id="obavljeni_poslovi" class="block mt-1 w-full rounded">{{ old('obavljeni_poslovi', $project->obavljeni_poslovi) }}</textarea>

                        <x-input-label :value="__('Članovi tima')" class="mt-4"/>
                        <div class="block mt-1 w-full bg-gray-100 rounded px-2 py-1">
                            @foreach($project->clanovi as $clan)
                                {{ $clan->name }}{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        </div>

                    @endif


                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('projects.index') }}">
                            <x-secondary-button>← {{ __('Back to Projects') }}</x-secondary-button>
                        </a>
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
