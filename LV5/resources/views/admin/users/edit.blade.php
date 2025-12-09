<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block text-sm font-medium mb-1">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Role</label>
                            <select name="role" class="w-full border-gray-300 rounded-md">
                                <option value="admin" @selected($user->role === 'admin')>admin</option>
                                <option value="nastavnik" @selected($user->role === 'nastavnik')>nastavnik</option>
                                <option value="student" @selected($user->role === 'student')>student</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">New Password (optional)</label>
                            <input type="password" name="password" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="w-full border-gray-300 rounded-md">
                        </div>

                        <x-primary-button class="mt-4 mt-lg-6">
                            Save
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
