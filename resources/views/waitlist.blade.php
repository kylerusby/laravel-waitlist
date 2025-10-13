<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Join Our Waitlist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen">
    
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-40 w-80 h-80 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 sm:py-32">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-sm font-medium mb-8">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ config('waitlist.badge_text') }}
                </div>

                <!-- Headline -->
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ config('waitlist.headline') }}
                </h1>

                <!-- Subheadline -->
                <p class="text-xl sm:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto leading-relaxed">
                    {{ config('waitlist.subheadline') }}
                </p>

                <!-- Waitlist Form -->
                <div class="max-w-xl mx-auto">
                    <form action="{{ route('waitlist.store') }}" method="POST" class="relative">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="flex-1">
                                <input 
                                    type="email" 
                                    name="email" 
                                    placeholder="Enter your email address" 
                                    required
                                    class="w-full px-6 py-4 text-lg rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all duration-200 shadow-sm @error('email') border-red-500 @enderror"
                                    value="{{ old('email') }}"
                                >
                                @error('email')
                                    <p class="text-red-500 text-sm mt-2 text-left">{{ $message }}</p>
                                @enderror
                            </div>
                            <button 
                                type="submit"
                                class="px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold text-lg rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-4 focus:ring-indigo-200 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                {{ config('waitlist.button_text') }}
                            </button>
                        </div>
                    </form>

                    @if(session('success'))
                        <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <p class="text-green-700 font-medium">{{ session('success') ?? config('waitlist.success_message') }}</p>
                        </div>
                    @endif

                    <!-- Social Proof -->
                    <div class="mt-8 flex items-center justify-center gap-2 text-gray-500">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 border-2 border-white"></div>
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-pink-400 to-pink-600 border-2 border-white"></div>
                        </div>
                        <p class="text-sm">
                            <span class="font-semibold text-gray-900">{{ number_format(config('waitlist.member_count')) }}+</span> people already joined
                        </p>
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="mt-24 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Early Access</h3>
                        <p class="text-gray-600">Get exclusive early access before the public launch</p>
                    </div>

                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Special Perks</h3>
                        <p class="text-gray-600">Enjoy special benefits reserved for early members</p>
                    </div>

                    <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                        <div class="w-12 h-12 bg-pink-100 rounded-xl flex items-center justify-center mb-4 mx-auto">
                            <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Stay Updated</h3>
                        <p class="text-gray-600">Be the first to know about updates and new features</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes blob {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            33% {
                transform: translate(30px, -50px) scale(1.1);
            }
            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>

</body>
</html>

