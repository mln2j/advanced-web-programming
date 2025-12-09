<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Moji radovi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-0 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3">Naziv</th>
                            <th class="px-6 py-3">Tip studija</th>
                            @if(auth()->user()->role === 'admin')
                                <th class="px-6 py-3">Nastavnik</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-3">{{ $task->title_hr }}</td>
                                <td class="px-6 py-3">{{ $task->study_type }}</td>
                                @if(auth()->user()->role === 'admin')
                                    <td class="px-6 py-3">{{ $task->user->name }}</td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="mt-4">
                <a href="{{ route('tasks.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-500">
                    + Novi rad
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
