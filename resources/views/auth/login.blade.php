<x-guest-layout>
    <style>
        body {
            background: url('https://images.unsplash.com/photo-1532614338840-ab30cf10ed36?auto=format&fit=crop&w=1920&q=80')
                        no-repeat center center fixed;
            background-size: cover;
        }
        .glass-card {
            backdrop-filter: blur(18px);
            background: rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
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

        <h1 class="text-4xl font-extrabold text-white drop-shadow-lg mb-8">
            Welcome Back 👋
        </h1>

        <div class="w-full max-w-md glass-card p-8">

            <!-- Email error -->
            <x-input-error class="mb-2" :messages="$errors->get('email')" />

            <!-- Password error -->
            <x-input-error class="mb-2" :messages="$errors->get('password')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label class="block text-gray-900 font-semibold">Email</label>
                    <input id="email" class="block mt-1 w-full rounded-md border-gray-300"
                        type="email" name="email" required autofocus />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block text-gray-900 font-semibold">Password</label>
                    <input id="password" class="block mt-1 w-full rounded-md border-gray-300"
                        type="password" name="password" required />
                </div>

                <!-- Remember Me -->
                <div class="block mb-4">
                    <label for="remember_me" class="inline-flex items-center text-gray-900">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600"
                            name="remember">
                        <span class="ml-2">Remember me</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">

                    <a class="underline text-gray-900 hover:text-gray-700"
                       href="{{ route('password.request') }}">
                        Forgot password?
                    </a>

                    <button class="px-6 py-2 rounded-lg text-white font-semibold 
                                   bg-gradient-to-r from-indigo-500 to-purple-500
                                   hover:from-indigo-400 hover:to-purple-400
                                   transform hover:-translate-y-0.5 transition">
                        Log in
                    </button>
                </div>
            </form>
        </div>

        <p class="mt-6 text-white">
            Don’t have an account? 
            <a href="{{ route('register') }}" class="underline font-bold">Register here</a>
        </p>

    </div>
</x-guest-layout>
