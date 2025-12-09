<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Prijave za rad:') }} {{ $task->title_hr }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
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
                            <th class="px-6 py-3">Student</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3 text-right">Akcije</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($applications as $application)
                            <tr class="border-t border-gray-200 dark:border-gray-700">
                                <td class="px-6 py-3">{{ $application->student->name }}</td>
                                <td class="px-6 py-3">{{ $application->student->email }}</td>
                                <td class="px-6 py-3">{{ $application->status }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-end gap-2">
                                        @if($application->status !== 'accepted')
                                            <form method="POST" action="{{ route('applications.accept', $application) }}"
                                                  onsubmit="return confirm('Prihvatiti ovog studenta na rad?');">
                                                @csrf
                                                <x-primary-button>
                                                    Prihvati
                                                </x-primary-button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
