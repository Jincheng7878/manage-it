<x-guest-layout>
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Register</h2>

        {{-- 错误提示 --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-md">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <label class="block mb-2 text-gray-700 font-medium">Name</label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   class="w-full border rounded-lg p-3 mb-4 focus:ring-indigo-500 focus:border-indigo-500">

            {{-- Email --}}
            <label class="block mb-2 text-gray-700 font-medium">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   class="w-full border rounded-lg p-3 mb-4 focus:ring-indigo-500 focus:border-indigo-500">

            {{-- Password --}}
            <label class="block mb-2 text-gray-700 font-medium">Password</label>
            <input type="password"
                   name="password"
                   required
                   class="w-full border rounded-lg p-3 mb-4 focus:ring-indigo-500 focus:border-indigo-500">

            {{-- Confirm Password --}}
            <label class="block mb-2 text-gray-700 font-medium">Confirm Password</label>
            <input type="password"
                   name="password_confirmation"
                   required
                   class="w-full border rounded-lg p-3 mb-4 focus:ring-indigo-500 focus:border-indigo-500">

            {{-- Register button --}}
            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-500 transition">
                Register
            </button>

            {{-- Login link --}}
            <div class="mt-4 text-right">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline text-sm">
                    Already registered?
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
