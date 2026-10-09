<div class="min-h-[calc(100vh-80px)] h-auto relative flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[#fdfdfd]">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <div class="w-16 h-16 bg-[#fdf2f7] border border-[#fbaed2] rounded-full flex items-center justify-center mx-auto text-[#F25996] mb-3 shadow-xs">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 font-display">Verify Vendor Registration</h2>
        <p class="mt-2 text-sm text-gray-600">
            We sent a 6-digit OTP code to your registered email address. Please enter it below to verify your account.
        </p>
    </div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white p-8 shadow-xl rounded-2xl border border-gray-100">
            <form action="<?= BASE_URL ?>/vendor-register/verify" method="POST">
                <div class="mb-6">
                    <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2 text-center">Enter 6-Digit OTP</label>
                    <input id="otp" name="otp" type="text" maxlength="6" pattern="[0-9]{6}" placeholder="123456" required class="w-full text-center tracking-[0.5em] text-2xl font-bold px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#F25996] focus:border-[#F25996] outline-none bg-gray-50">
                </div>

                <button type="submit" class="w-full py-3.5 text-white font-bold text-sm rounded-xl transition-all shadow-md hover:opacity-95" style="background-color: #F25996;">
                    Verify & Submit Application
                </button>
            </form>

            <div class="mt-6 text-center">
                <button type="button" onclick="resendVendorOtp()" class="text-xs font-semibold text-[#F25996] hover:text-[#d94883] underline cursor-pointer bg-transparent border-0">
                    Didn't receive code? Resend OTP
                </button>
                <div id="resend-msg" class="text-xs mt-2 text-gray-500"></div>
            </div>
        </div>
    </div>
</div>

<script>
function resendVendorOtp() {
    const msg = document.getElementById('resend-msg');
    msg.innerText = 'Resending OTP...';
    msg.className = 'text-xs mt-2 text-[#F25996] font-semibold';
    fetch('<?= BASE_URL ?>/vendor-register/resend-otp', { method: 'POST' })
        .then(r => r.json())
        .then(data => {
            msg.innerText = data.message;
            if (data.success) {
                msg.className = 'text-xs mt-2 text-emerald-600 font-bold';
            } else {
                msg.className = 'text-xs mt-2 text-rose-600 font-semibold';
            }
        })
        .catch(() => {
            msg.innerText = 'Error connecting to server to resend OTP.';
            msg.className = 'text-xs mt-2 text-rose-600 font-semibold';
        });
}
</script>
