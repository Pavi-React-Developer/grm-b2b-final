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
                <span class="text-[#F25996] font-bold">Terms &amp; Conditions</span>
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
                        Terms & <span class="text-[#F25996]">Conditions</span>
                    </h1>

                    <p class="text-slate-600 text-base sm:text-lg max-w-xl leading-relaxed pt-1">
                        Please read our terms and conditions carefully before placing an order on GRM B2B Wholesale.
                    </p>
                </div>

                <!-- Right Visual: Document Shield + Trust Badges -->
                <div class="w-full lg:w-2/5 flex flex-col sm:flex-row items-center justify-center lg:justify-end gap-6 sm:gap-8">
                    
                    <!-- Document & Shield Graphic -->
                    <div class="relative flex-shrink-0">
                        <div class="w-28 h-36 sm:w-32 sm:h-40 bg-white rounded-2xl shadow-lg border-2 border-pink-200 flex flex-col p-3.5 justify-between relative transform -rotate-2 hover:rotate-0 transition-transform duration-300">
                            <!-- Document lines -->
                            <div class="space-y-2 pt-1">
                                <div class="h-2.5 bg-[#F25996] rounded-full w-3/4"></div>
                                <div class="h-1.5 bg-pink-200 rounded-full w-full"></div>
                                <div class="h-1.5 bg-pink-200 rounded-full w-5/6"></div>
                                <div class="h-1.5 bg-pink-200 rounded-full w-4/6"></div>
                                <div class="h-1.5 bg-pink-200 rounded-full w-full"></div>
                            </div>
                            <!-- Shield badge on bottom right of doc -->
                            <div class="absolute -bottom-3 -right-3 w-12 h-14 bg-gradient-to-br from-[#F25996] to-[#d8407d] rounded-b-xl rounded-t-sm shadow-md flex items-center justify-center border-2 border-white">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Trust Pillars -->
                    <div class="flex flex-col space-y-3.5 relative">
                        <!-- Fair Trade -->
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-[#F25996] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-[#0f2963] text-sm sm:text-base tracking-tight">Fair Trade</span>
                        </div>

                        <!-- Trusted Partnership -->
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-[#F25996] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-[#0f2963] text-sm sm:text-base tracking-tight">Trusted Partnership</span>
                        </div>

                        <!-- Long Term Growth -->
                        <div class="flex items-center gap-2.5">
                            <div class="w-6 h-6 rounded-full bg-[#F25996] text-white flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <span class="font-bold text-[#0f2963] text-sm sm:text-base tracking-tight">Long Term Growth</span>
                        </div>

                        <!-- Decorative Orange Swoosh -->
                        <div class="pt-1">
                            <svg class="w-24 h-3 text-[#F25996]" viewBox="0 0 100 12" fill="none">
                                <path d="M2 9C25 2 75 2 98 9" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- â”€â”€ Main Terms Card Section â”€â”€ -->
    <div class="w-full max-w-full mx-auto px-2 sm:px-6 lg:px-8 pt-8">
        <div class="bg-white rounded-3xl border border-gray-200/90 shadow-sm p-6 sm:p-10 lg:p-12">
            
            <!-- Card Header -->
            <div class="mb-8">
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#0f2963] tracking-tight font-display">
                    Invoice Terms & Conditions
                </h2>
                <p class="text-slate-500 text-sm sm:text-base mt-2">
                    By placing an order on GRM B2B Wholesale, you agree to the following terms and conditions:
                </p>
            </div>

            <!-- List of 8 Terms -->
            <div class="divide-y divide-gray-100">

                <!-- Item 1 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        1
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        Wholesale orders only.
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        2
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        Products are supplied as per the quantity ordered.
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        3
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        For products with assorted/random designs, pieces are selected randomly and may differ from the images shown on the website.
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        4
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        Images are for reference only; actual design, print, color, or pattern may vary.
                    </div>
                </div>

                <!-- Item 5 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        5
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        <strong class="font-extrabold text-gray-900">NO RETURN, NO EXCHANGE, NO CASH REFUND</strong> is accepted once the order is confirmed/delivered.
                    </div>
                </div>

                <!-- Item 6 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        6
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        Customers are requested to check the quantity and products at the time of delivery.
                    </div>
                </div>

                <!-- Item 7 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        7
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        Once the order is confirmed, cancellation is not allowed.
                    </div>
                </div>

                <!-- Item 8 -->
                <div class="py-4 sm:py-5 flex items-start sm:items-center gap-4 sm:gap-6 hover:bg-pink-50/40 px-3 sm:px-4 rounded-xl transition-colors">
                    <div class="w-9 h-9 rounded-full bg-pink-100/90 text-[#F25996] font-bold flex items-center justify-center flex-shrink-0 text-sm">
                        8
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-pink-50 text-[#F25996] flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017a2 2 0 01-1.414-.586l-4.243-4.243a2 2 0 010-2.828l1.414-1.414a2 2 0 012.828 0L11 13.172V4a2 2 0 012-2h1a2 2 0 012 2v6z" />
                        </svg>
                    </div>
                    <div class="text-slate-700 font-medium text-sm sm:text-base leading-relaxed">
                        By placing the order, the customer agrees to the above terms.
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
                        If you have any questions about our terms, please contact our support team before placing an order.
                    </p>
                </div>
                
                <a href="mailto:grmb2bsupport@gmail.com" 
                   class="inline-flex items-center justify-center gap-2 bg-[#F25996] hover:bg-[#d8407d] active:bg-[#c2346e] text-white font-bold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 text-sm whitespace-nowrap w-full sm:w-auto text-center transform hover:-translate-y-0.5">
                    <span>Contact Support</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

        </div>
    </div>

</div>


