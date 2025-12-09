<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('My Applications') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="applicationSort()">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 mb-4">
                        Povuci i pusti prijave za promjenu prioriteta (gore = veći prioritet).
                    </p>

                    <ul class="space-y-2"
                        @drop.prevent="onDrop($event)"
                        @dragover.prevent>
                        @foreach($applications as $app)
                            <li class="bg-gray-50 dark:bg-gray-700 px-4 py-2 rounded flex justify-between items-center cursor-move"
                                draggable="true"
                                data-id="{{ $app->id }}"
                                @dragstart="onDragStart($event)"
                                @dragend="onDragEnd">
                                <div>
                                    <div class="font-medium">
                                        {{ app()->getLocale() === 'hr' ? $app->task->title_hr : $app->task->title_en }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Prioritet: {{ $app->priority }} · Status:
                                        @if($app->status === 'accepted')
                                            <span class="text-green-600">prihvaćen</span>
                                        @elseif($app->status === 'rejected')
                                            <span class="text-red-600">odbijen</span>
                                        @else
                                            <span class="text-yellow-600">u obradi</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('applications.destroy', $app) }}"
                                          onsubmit="return confirm('Obrisati prijavu?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 border border-red-600 text-xs font-semibold rounded-md text-red-600 hover:bg-red-600 hover:text-white transition">
                                            Delete
                                        </button>
                                    </form>
                                    <span class="text-xs text-gray-400">
                                        ⇅
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <script>
        function applicationSort() {
            let dragEl = null;

            return {
                onDragStart(event) {
                    dragEl = event.target;
                    event.dataTransfer.effectAllowed = 'move';
                },
                onDragEnd() {
                    dragEl = null;
                    this.saveOrder();
                },
                onDrop(event) {
                    const target = event.target.closest('li');
                    if (!dragEl || !target || dragEl === target) return;

                    const list = target.parentNode;
                    const items = Array.from(list.children);
                    const dragIndex = items.indexOf(dragEl);
                    const targetIndex = items.indexOf(target);

                    if (dragIndex < targetIndex) {
                        list.insertBefore(dragEl, target.nextSibling);
                    } else {
                        list.insertBefore(dragEl, target);
                    }
                },
                saveOrder() {
                    const ids = Array.from(document.querySelectorAll('[data-id]'))
                        .map(el => el.getAttribute('data-id'));

                    fetch('{{ route('applications.reorder') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({order: ids}),
                    });
                }
            }
        }
    </script>
</x-app-layout>
