<div class="min-h-[70vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="mt-2 text-center text-3xl font-extrabold text-gray-900 font-display">Forgot Password</h2>
            <p class="mt-2 text-center text-sm text-gray-500">
                Enter your registered email address and we'll send you a 6-digit OTP to reset your password.
            </p>
        </div>
        


        <form class="mt-8 space-y-6" action="<?= BASE_URL ?>/forgot-password" method="POST">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="appearance-none relative block w-full px-4 py-3 border border-gray-200 placeholder-gray-400 text-gray-900 rounded-xl focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-transparent transition-all sm:text-sm" 
                    placeholder="Enter your registered email">
            </div>

            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-3.5 px-4 border border-transparent text-sm font-bold rounded-xl text-white transition-all shadow-sm hover:opacity-90" style="background: #f25996;">
                    Send OTP
                </button>
            </div>
            
            <div class="text-center">
                <a href="<?= BASE_URL ?>/login" class="font-semibold text-sm text-[#f25996] hover:text-[#d94883] transition-colors">
                    Back to Login
                </a>
            </div>
        </form>
    </div>
</div>


