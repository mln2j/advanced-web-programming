<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Projects') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6">
                <ul>
                    @forelse($projects as $project)
                        <li class="mb-2 flex justify-between items-center p-3 bg-white shadow-sm sm:rounded-lg">
                            <span>
                                {{ $project->naziv_projekta }}
                                (Voditelj: {{ $project->voditelj->name }})
                            </span>
                            <div>
                                <a href="{{ route('projects.show', $project) }}">
                                    <x-secondary-button>{{ __('Details') }}</x-secondary-button>
                                </a>
                                <a href="{{ route('projects.edit', $project) }}">
                                    <x-primary-button>{{ __('Edit') }}</x-primary-button>
                                </a>
                                <form method="POST" action="{{ route('projects.destroy', $project) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button onclick="return confirm('Jesi li siguran da želiš obrisati projekt?')">
                                        {{ __('Delete') }}
                                    </x-danger-button>
                                </form>

                            </div>
                        </li>
                    @empty
                        <li>{{ __('No projects found.') }}</li>
                    @endforelse
                </ul>
            </div>
            <a href="{{ route('projects.create') }}">
                <x-primary-button>{{ __('New Project') }}</x-primary-button>
            </a>
        </div>
    </div>
</x-app-layout>
