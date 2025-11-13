<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Profile
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">

                @if(session('status'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" class="w-full border p-2 rounded"
                               value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" class="w-full border p-2 rounded"
                               value="{{ old('email', $user->email) }}">
                    </div>

                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">
                        Save Changes
                    </button>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
