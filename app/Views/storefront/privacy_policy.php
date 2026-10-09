<div class="min-h-screen bg-slate-50/60 pb-16">

    <!-- â”€â”€ Breadcrumb Section â”€â”€ -->
    <div class="bg-white border-b border-gray-200/80">
        <div class="w-full max-w-full mx-auto px-2 sm:px-6 lg:px-8 py-3.5">
            <nav class="flex items-center space-x-2 text-sm font-medium">
                <a href="<?= BASE_URL ?>/" class="inline-flex items-center gap-1.5 text-[#F25996] hover:text-[#d8407d] font-semibold transition-colors">
                    <svg class="w-4 h-4 shrink-0 text-[#F25996]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                    </svg>
                    <span>Home</span>
                </a>
                <svg class="w-4 h-4 text-[#F25996] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-[#F25996] font-bold">Privacy &amp; Policies</span>
            </nav>
        </div>
    </div>

    <!-- â”€â”€ Hero Banner Section â”€â”€ -->
    <div class="w-full max-w-full mx-auto px-2 sm:px-6 lg:px-8 pt-8">
        <div class="relative overflow-hidden rounded-3xl border border-pink-200/70 shadow-sm p-6 sm:p-10 lg:p-12"
             style="background: linear-gradient(135deg, #fdf2f7 0%, #fce7f3 55%, #fdf4f8 100%);">
            
            <!-- Decorative background glows -->
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-pink-300/30 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-64 h-64 bg-pink-200/40 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8 lg:gap-12">
                
                <!-- Left Content -->
                <div class="w-full lg:w-3/5 space-y-3">
                    <div class="inline-block">
                        <span class="text-xs sm:text-sm font-extrabold tracking-widest uppercase text-[#F25996] bg-pink-100/80 px-3 py-1 rounded-full border border-pink-200/60">
                            OUR POLICIES
                        </span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-[#0f2963] font-display tracking-tight leading-tight">
                        Privacy &amp; <span class="text-[#F25996]">Policies</span>
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg max-w-xl leading-relaxed pt-1">
                        Your trust is important to us. Learn how we collect, use, and protect your information at GRM B2B Wholesale.
                    </p>
                </div>

                <!-- Right Visual: Shield Lock + Script Text -->
                <div class="w-full lg:w-2/5 flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-6 sm:gap-8">
                    
                    <!-- Shield Graphic -->
                    <div class="relative flex-shrink-0">
                        <!-- Soft aura behind shield -->
                        <div class="absolute inset-0 bg-pink-400/25 rounded-full blur-xl transform scale-125 pointer-events-none"></div>
                        
                        <!-- Main Shield -->
                        <div class="relative w-28 h-32 sm:w-32 sm:h-36 bg-gradient-to-b from-[#F25996] via-[#e84d8a] to-[#d8407d] rounded-t-3xl rounded-b-[2.5rem] shadow-xl border-4 border-white flex items-center justify-center transform hover:scale-105 transition-transform duration-300">
                            <!-- Inner glow border -->
                            <div class="absolute inset-1.5 border border-white/30 rounded-t-2xl rounded-b-[2rem] pointer-events-none"></div>
                            
                            <!-- Lock Icon -->
                            <div class="w-11 h-13 sm:w-12 sm:h-14 bg-white rounded-xl shadow-md flex flex-col items-center justify-center relative p-1">
                                <!-- Lock Shackle -->
                                <div class="w-6 h-6 border-4 border-[#F25996] rounded-t-full -mt-6 bg-transparent"></div>
                                <!-- Keyhole -->
                                <div class="w-2.5 h-3 bg-[#F25996] rounded-full mt-1"></div>
                                <div class="w-1.5 h-2 bg-[#F25996] -mt-0.5"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Script Tagline & Accent -->
                    <div class="flex flex-col items-center sm:items-start space-y-1 relative">
                        <div class="text-center sm:text-left">
                            <span class="block text-2xl sm:text-3xl font-extrabold italic text-[#d8407d] tracking-tight" style="font-family: 'Outfit', 'Inter', cursive, sans-serif;">
                                Your Privacy
                            </span>
                            <span class="block text-2xl sm:text-3xl font-extrabold italic text-[#d8407d] tracking-tight" style="font-family: 'Outfit', 'Inter', cursive, sans-serif;">
                                Our Priority
                            </span>
                        </div>

                        <!-- Decorative Orange Swoosh -->
                        <div class="pt-1.5">
                            <svg class="w-28 h-3.5 text-[#F25996]" viewBox="0 0 100 12" fill="none">
                                <path d="M2 9C25 2 75 2 98 9" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- â”€â”€ Main Policy Content Section â”€â”€ -->
    <div class="w-full max-w-full mx-auto px-2 sm:px-6 lg:px-8 pt-8">
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 sm:p-10 lg:p-12">
            
            <!-- Card Header -->
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0f2963] tracking-tight font-display">
                    Privacy &amp; Policies
                </h2>
                <p class="text-slate-600 text-sm sm:text-base mt-2 leading-relaxed max-w-4xl">
                    At GRM B2B Wholesale, we are committed to protecting the privacy and security of our customers. This policy explains how we collect, use, and safeguard your information.
                </p>
            </div>

            <!-- List of 6 Policy Points -->
            <div class="divide-y divide-gray-100">

                <!-- Point 1: Information We Collect -->
                <div class="py-5 sm:py-6 flex items-start gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-2xl transition-colors">
                    <div class="w-12 h-12 rounded-full bg-pink-100/90 text-[#F25996] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            <path d="M18 10c1.66 0 3-1.34 3-3s-1.34-3-3-3c-.35 0-.68.06-1 .17 1.22.86 2 2.28 2 3.83s-.78 2.97-2 3.83c.32.11.65.17 1 .17zm1 4.5c1.78.89 3 2.37 3 3.5v2h-4v-2c0-1.36-.6-2.55-1.63-3.41.87-.06 1.78-.09 2.63-.09z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-[#0f2963]">
                            Information We Collect
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            We collect personal and business information such as your name, company details, contact information, shipping address, and order history when you register or place an order.
                        </p>
                    </div>
                </div>

                <!-- Point 2: How We Use Your Information -->
                <div class="py-5 sm:py-6 flex items-start gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-2xl transition-colors">
                    <div class="w-12 h-12 rounded-full bg-pink-100/90 text-[#F25996] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-[#0f2963]">
                            How We Use Your Information
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            Your information is used to process orders, provide customer support, improve our services, and keep you updated about products, offers, and important announcements.
                        </p>
                    </div>
                </div>

                <!-- Point 3: Data Security -->
                <div class="py-5 sm:py-6 flex items-start gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-2xl transition-colors">
                    <div class="w-12 h-12 rounded-full bg-pink-100/90 text-[#F25996] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-[#0f2963]">
                            Data Security
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            We take appropriate security measures to protect your information from unauthorized access, alteration, disclosure, or misuse.
                        </p>
                    </div>
                </div>

                <!-- Point 4: Sharing of Information -->
                <div class="py-5 sm:py-6 flex items-start gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-2xl transition-colors">
                    <div class="w-12 h-12 rounded-full bg-pink-100/90 text-[#F25996] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-[#0f2963]">
                            Sharing of Information
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            We do not sell, rent, or share your personal information with third parties, except when required by law or to complete your order (e.g., shipping partners).
                        </p>
                    </div>
                </div>

                <!-- Point 5: Cookies -->
                <div class="py-5 sm:py-6 flex items-start gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-2xl transition-colors">
                    <div class="w-12 h-12 rounded-full bg-pink-100/90 text-[#F25996] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-[#0f2963]">
                            Cookies
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            Our website may use cookies to enhance your browsing experience, remember your preferences, and analyze site usage.
                        </p>
                    </div>
                </div>

                <!-- Point 6: Policy Updates -->
                <div class="py-5 sm:py-6 flex items-start gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-2xl transition-colors">
                    <div class="w-12 h-12 rounded-full bg-pink-100/90 text-[#F25996] flex items-center justify-center flex-shrink-0 shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base sm:text-lg font-bold text-[#0f2963]">
                            Policy Updates
                        </h3>
                        <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                            We may update this Privacy &amp; Policies page from time to time. Any changes will be posted on this page with the updated date.
                        </p>
                    </div>
                </div>

            </div>

            <!-- Bottom Support Callout Box -->
            <div class="mt-8 rounded-2xl bg-gradient-to-r from-pink-50 via-pink-50 to-pink-100/70 border border-pink-200/80 p-5 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-3.5 w-full sm:w-auto">
                    <div class="w-10 h-10 rounded-full bg-[#F25996] text-white flex items-center justify-center flex-shrink-0 font-serif font-bold text-lg shadow-sm">
                        i
                    </div>
                    <p class="text-slate-700 font-medium text-sm sm:text-base leading-snug">
                        If you have any questions about our privacy practices, please feel free to contact us.
                    </p>
                </div>
                
                <a href="mailto:grmb2bsupport@gmail.com" 
                   class="inline-flex items-center justify-center gap-2 bg-[#F25996] hover:bg-[#d8407d] active:bg-[#c2346e] text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm whitespace-nowrap w-full sm:w-auto text-center transform hover:-translate-y-0.5">
                    <span>Contact Us</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

        </div>
    </div>

</div>

