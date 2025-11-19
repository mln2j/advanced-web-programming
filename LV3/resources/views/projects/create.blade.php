<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Project') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                <form method="POST" action="{{ route('projects.store') }}">
                    @csrf

                    <x-input-label for="naziv_projekta" :value="__('Naziv projekta')" />
                    <x-text-input id="naziv_projekta" class="block mt-1 w-full" type="text" name="naziv_projekta" required autofocus />

                    <x-input-label for="opis_projekta" :value="__('Opis')" class="mt-4"/>
                    <textarea name="opis_projekta" id="opis_projekta" class="block mt-1 w-full"></textarea>

                    <x-input-label for="cijena_projekta" :value="__('Cijena')" class="mt-4"/>
                    <x-text-input id="cijena_projekta" class="block mt-1 w-full" type="number" step="0.01" name="cijena_projekta" />

                    <x-input-label for="datum_pocetka" :value="__('Datum početka')" class="mt-4"/>
                    <x-text-input id="datum_pocetka" class="block mt-1 w-full" type="date" name="datum_pocetka" required />

                    <x-input-label for="datum_zavrsetka" :value="__('Datum završetka')" class="mt-4"/>
                    <x-text-input id="datum_zavrsetka" class="block mt-1 w-full" type="date" name="datum_zavrsetka" />

                    <x-input-label for="obavljeni_poslovi" :value="__('Obavljeni poslovi')" class="mt-4"/>
                    <textarea name="obavljeni_poslovi" id="obavljeni_poslovi" class="block mt-1 w-full"></textarea>


                    <x-input-label for="clanovi" :value="__('Članovi tima')" class="mt-4"/>
                    <select name="clanovi[]" id="clanovi" class="block mt-1 w-full" multiple>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('projects.index') }}">
                            <x-secondary-button>← {{ __('Back to Projects') }}</x-secondary-button>
                        </a>
                        <x-primary-button>{{ __('Create') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
