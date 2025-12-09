<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Role</label>
                            <select name="role" class="w-full border-gray-300 rounded-md">
                                <option value="admin">admin</option>
                                <option value="nastavnik">nastavnik</option>
                                <option value="student">student</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Password</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-md">
                        </div>

                        <x-primary-button class="mt-4 mt-lg-6">
                            Create
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
