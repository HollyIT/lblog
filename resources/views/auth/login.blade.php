@extends('layouts.main')
@section('content')
    <div class="font-sans pt-24 pb-5">
        <div class="flex flex-col justify-center sm:w-96 sm:m-auto mx-5 mb-5 space-y-8">
            <h1 class="font-bold text-center text-4xl text-yellow-500">Log<span class="text-blue-500">In</span></h1>
            <form method="post" action="{{ url('/login') }}">
                @csrf
                <div class="flex flex-col bg-white p-10 rounded-lg shadow space-y-6">
                    <h1 class="font-bold text-xl text-center">Sign in to your account</h1>

                    <div class="flex flex-col space-y-1">
                        <input type="email" name="email" id="email" class="border-2 px-2 py-2 rounded w-full focus:outline-none focus:border-blue-400 focus:shadow" placeholder="Email" />
                        @error('email')
                        <span class="error text-red-600 text-sm">{{ $message }}</span>
                        @enderror

                    </div>


                    <div class="flex flex-col space-y-1">
                        <input type="password" name="password" id="password" class="border-2 rounded px-3 py-2 w-full focus:outline-none focus:border-blue-400 focus:shadow" placeholder="Password" />
                        @error('password')
                        <span class="error text-red-600 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="relative">
                        <input type="checkbox" name="remember" id="remember" checked class="inline-block align-middle" />
                        <label class="inline-block align-middle" for="remember">Remember me</label>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-between items-center">
                        <a href="#" class="inline-block text-blue-500 hover:text-blue-800 hover:underline">Forgot your password?</a>
                        <button type="submit" class="bg-blue-500 text-white font-bold px-5 py-2 rounded focus:outline-none shadow hover:bg-blue-700 transition-colors">Log In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
