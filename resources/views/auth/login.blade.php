<x-guest-layout>
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
        <h2 class="text-3xl font-bold text-center mb-6 text-gray-800">Login</h2>

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

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <label class="block mb-2 text-gray-700 font-medium">Email</label>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   class="w-full border rounded-lg p-3 mb-4 focus:ring-indigo-500 focus:border-indigo-500">

            {{-- Password --}}
            <label class="block mb-2 text-gray-700 font-medium">Password</label>
            <input type="password"
                   name="password"
                   required
                   class="w-full border rounded-lg p-3 mb-4 focus:ring-indigo-500 focus:border-indigo-500">

            {{-- Login button --}}
            <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-500 transition">
                Log in
            </button>

            {{-- Register link --}}
            <div class="mt-4 text-right">
                <a href="{{ route('register') }}" class="text-indigo-600 hover:underline text-sm">
                    Don't have an account? Register
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>
