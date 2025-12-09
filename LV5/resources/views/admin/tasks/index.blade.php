<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Management') }}
            </h2>
        </div>
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
                            <th class="px-6 py-3">Nastavnik</th>
                            <th class="px-6 py-3">Tip studija</th>
                            <th class="px-6 py-3">Akcije</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tasks as $task)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-3">{{ $task->title_hr }}</td>
                                <td class="px-6 py-3">{{ $task->user->name }}</td>
                                <td class="px-6 py-3">{{ $task->study_type }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.tasks.edit', $task) }}"
                                           class="inline-flex items-center px-3 py-1.5 border border-yellow-500 text-xs font-semibold rounded-md text-yellow-600 hover:bg-yellow-500 hover:text-white transition">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.tasks.destroy', $task) }}"
                                              method="POST"
                                              onsubmit="return confirm('Delete this task?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 border border-red-600 text-xs font-semibold rounded-md text-red-600 hover:bg-red-600 hover:text-white transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>


                <div class="mt-4">
                    <a href="{{ route('admin.tasks.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-500">
                        + Novi rad
                    </a>
                </div>
        </div>
    </div>
</x-app-layout>
