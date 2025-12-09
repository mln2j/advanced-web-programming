<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.tasks.update', $task) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium mb-1">Nastavnik</label>
                            <select name="user_id" class="w-full border-gray-300 rounded-md">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" @selected(old('user_id', $task->user_id) == $teacher->id)>
                                        {{ $teacher->name }} ({{ $teacher->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Naziv (HR)</label>
                                <input type="text"
                                       name="title_hr"
                                       value="{{ old('title_hr', $task->title_hr) }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Naziv (EN)</label>
                                <input type="text"
                                       name="title_en"
                                       value="{{ old('title_en', $task->title_en) }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Opis (HR)</label>
                                <textarea name="description_hr"
                                          class="w-full border-gray-300 rounded-md">{{ old('description_hr', $task->description_hr) }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Opis (EN)</label>
                                <textarea name="description_en"
                                          class="w-full border-gray-300 rounded-md">{{ old('description_en', $task->description_en) }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Tip studija</label>
                            @php $currentType = old('study_type', $task->study_type); @endphp
                            <select name="study_type" class="w-full border-gray-300 rounded-md">
                                <option value="strucni" @selected($currentType === 'strucni')>Stručni</option>
                                <option value="preddiplomski" @selected($currentType === 'preddiplomski')>Preddiplomski</option>
                                <option value="diplomski" @selected($currentType === 'diplomski')>Diplomski</option>
                            </select>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <x-primary-button>
                                Save
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
