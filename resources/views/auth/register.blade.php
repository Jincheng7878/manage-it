<x-guest-layout>

    <style>
        body {
            background: url('https://images.unsplash.com/photo-1529070538774-1843cb3265df?auto=format&fit=crop&w=1920&q=80')
                        no-repeat center center fixed;
            background-size: cover;
        }
        .glass-card {
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.45);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.25);
        }
        .fade-in {
            animation: fadeIn 1s ease forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>

    <div class="min-h-screen flex flex-col justify-center items-center px-6 fade-in">

        <h1 class="text-4xl font-extrabold text-white mb-8 drop-shadow-lg">
            Create Your Account ✨
        </h1>

        <div class="w-full max-w-md glass-card p-8">

            <!-- Validation Errors (Breeze Style) -->
            <x-input-error :messages="$errors->all()" class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block text-gray-900 font-semibold">Name</label>
                    <input id="name" type="text" name="name"
                           value="{{ old('name') }}" required autofocus
                           class="block mt-1 w-full rounded-md border-gray-300" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="block text-gray-900 font-semibold">Email</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email') }}" required
                           class="block mt-1 w-full rounded-md border-gray-300" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-900 font-semibold">Password</label>
                    <input id="password" type="password" name="password" required
                           class="block mt-1 w-full rounded-md border-gray-300" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label class="block text-gray-900 font-semibold">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="block mt-1 w-full rounded-md border-gray-300" />
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-between">

                    <a class="underline text-gray-900 hover:text-gray-700"
                       href="{{ route('login') }}">
                        Already registered?
                    </a>

                    <button class="px-6 py-2 rounded-lg text-white font-semibold
                                   bg-gradient-to-r from-indigo-500 to-purple-500
                                   hover:from-indigo-400 hover:to-purple-400
                                   transform hover:-translate-y-0.5 transition">
                        Register
                    </button>

                </div>
            </form>
        </div>

        <p class="mt-6 text-white">
            Back to <a href="{{ route('login') }}" class="underline font-bold">Login</a>
        </p>

    </div>

</x-guest-layout>
