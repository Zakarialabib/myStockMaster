<div>
    <div class="min-h-screen bg-gradient-to-br from-orange-50 via-white to-orange-100 flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="max-w-lg w-full">
            <div class="bg-white rounded-2xl shadow-elevation-5 overflow-hidden border border-orange-100 transform transition-all duration-300 hover:shadow-elevation-4">
                <!-- Header with gradient -->
                <div class="bg-gradient-to-r from-orange-500 via-orange-600 to-amber-500 p-8 text-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-4 ring-8 ring-white/10">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2 font-display">{{__('Account Pending Approval')}}</h1>
                        <p class="text-orange-100 text-sm">{{9}}</p>
                    </div>
                </div>

                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="w-24 h-24 bg-gradient-to-br from-orange-100 to-amber-100 rounded-full flex items-center justify-center mx-auto mb-4 ring-4 ring-orange-200/50">
                            <svg class="w-12 h-12 text-orange-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-semibold text-orange-900 mb-3 font-display">{{__('Welcome to')}} {{ config('app.name') }}!</h2>
                        <p class="text-orange-600 text-base leading-relaxed max-w-sm mx-auto">
                            {{__('Your setup is complete and we're now reviewing your account. You'll receive an email notification once your account is approved.')}}
                        </p>
                        
                        @auth
                            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm text-green-800">
                                        <strong>{{(__'Good news')}}!</strong> {{__('You\'re already logged in.')}} 
                                        <a href="{{ route('dashboard') }}" class="text-green-600 hover:text-green-700 font-medium underline decoration-green-600/30 hover:decoration-green-600 transition-all">Go to dashboard</a> or 
                                        <a href="{{ route('logout') }}" class="text-green-600 hover:text-green-700 font-medium underline decoration-green-600/30 hover:decoration-green-600 transition-all">sign out</a>.
                                    </p>
                                </div>
                            </div>
                        @endauth
                        
                        @guest
                            <div class="mt-6 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    <p class="text-sm text-orange-800">
                                        {{__('Already have an account?')}}  
                                        <a href="{{ url('/login') }}" class="text-orange-600 hover:text-orange-700 font-medium underline decoration-orange-600/30 hover:decoration-orange-600 transition-all">Sign in here</a>.
                                    </p>
                                </div>
                            </div>
                        @endguest
                    </div>

                    <div class="bg-gradient-to-br from-orange-50 to-orange-50/30 rounded-xl p-6 mb-8 border border-orange-100">
                        <h3 class="font-semibold text-orange-900 mb-3 flex items-center">
                            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            What's next?
                        </h3>
                        <ul class="text-sm text-orange-600 space-y-2">
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-orange-400 rounded-full mr-3 mt-2 shrink-0"></span>
                                Our team will review your setup within 24 hours
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-orange-400 rounded-full mr-3 mt-2 shrink-0"></span>
                                You'll receive login credentials via email
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-orange-400 rounded-full mr-3 mt-2 shrink-0"></span>
                                Start managing your restaurant immediately after approval
                            </li>
                        </ul>
                    </div>

                    <div class="text-center">
                        <p class="text-sm text-orange-600">
                            Need help? Contact us at 
                            <a href="mailto:support@restopos.com" class="text-orange-600 hover:text-orange-700 font-medium underline decoration-orange-600/30 hover:decoration-orange-600 transition-all">
                                support@restopos.com
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
