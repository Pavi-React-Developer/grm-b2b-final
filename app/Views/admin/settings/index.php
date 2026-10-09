<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> Administration <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Settings</span>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-4xl font-display font-extrabold text-gray-900 tracking-tight">System & Platform Settings</h2>
                <p class="text-sm text-gray-500 mt-1">Manage global feature toggles, multi-vendor controls, complete data backups, and transactional restore.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="<?= BASE_URL ?>/admin/settings/backup/export" class="bg-[#F25996] hover:bg-[#e04481] text-white px-5 py-2.5 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Quick Export DB
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex border-b border-gray-200 mb-8 space-x-8">
        <button onclick="switchSettingsTab('general')" id="tab-btn-general" class="tab-btn py-3 text-sm font-bold border-b-2 <?= $activeTab === 'general' ? 'border-brand-600 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-700' ?> transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            General & Feature Flags
            <span id="vendor-status-pill" class="<?= $vendorEnabled ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' ?> text-xs px-2 py-0.5 rounded-full font-extrabold">
                <?= $vendorEnabled ? 'Vendor Module ON' : 'Vendor Module OFF' ?>
            </span>
        </button>

        <button onclick="switchSettingsTab('backup')" id="tab-btn-backup" class="tab-btn py-3 text-sm font-bold border-b-2 <?= $activeTab === 'backup' ? 'border-brand-600 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-700' ?> transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            Data Backup & Restore
            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-extrabold"><?= $totalTables ?> Tables</span>
        </button>

        <?php if ($vendorEnabled): ?>
        <button onclick="switchSettingsTab('bank_accounts')" id="tab-btn-bank_accounts" class="tab-btn py-3 text-sm font-medium border-b-2 <?= $activeTab === 'bank_accounts' ? 'border-brand-600 text-brand-700' : 'border-transparent text-gray-500 hover:text-gray-700' ?> transition flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
            Payout & Bank Settings
        </button>
        <?php endif; ?>
    </div>

    <!-- ==================== TAB 1: GENERAL & FEATURE FLAGS ==================== -->
    <div id="tab-content-general" class="settings-tab-pane <?= $activeTab === 'general' ? '' : 'hidden' ?> space-y-8">
        
        <!-- Feature Switch: Vendor Module Toggle Card -->
        <div class="bg-white rounded-3xl border border-gray-200/80 p-8 shadow-sm relative overflow-hidden">
            <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-pink-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8 relative z-10">
                <div class="flex items-start gap-5">
                    <div class="w-16 h-16 rounded-2xl <?= $vendorEnabled ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : 'bg-rose-50 text-rose-600 border border-rose-200' ?> flex items-center justify-center shrink-0 shadow-sm transition-all" id="vendor-icon-container">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>

                    <div>
                        <div class="flex items-center gap-3">
                            <h3 class="text-2xl font-bold text-gray-900">Multi-Vendor Marketplace Mode</h3>
                            <span id="vendor-live-badge" class="<?= $vendorEnabled ? 'bg-emerald-100 text-emerald-800 border-emerald-200' : 'bg-rose-100 text-rose-800 border-rose-200' ?> border text-xs font-black px-3 py-0.5 rounded-full uppercase tracking-wider">
                                <?= $vendorEnabled ? 'Active & Live' : 'Disabled (Single-Vendor Only)' ?>
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-2 leading-relaxed max-w-3xl">
                            Dynamically enable or disable third-party vendor operations across the entire B2B platform in real time. 
                            When switched <span class="font-bold text-rose-600">OFF</span>, all vendor onboarding, registration forms, storefront tabs, and vendor portal logins are seamlessly halted while safely preserving all vendor data and catalog history.
                        </p>

                        <!-- Operational Status Badges -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-5">
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-3 flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full <?= $vendorEnabled ? 'bg-emerald-500' : 'bg-gray-300' ?>" id="dot-reg"></span>
                                <span class="text-xs font-semibold text-gray-700">Vendor Registration (<span id="txt-reg"><?= $vendorEnabled ? 'Active' : 'Closed' ?></span>)</span>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-3 flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full <?= $vendorEnabled ? 'bg-emerald-500' : 'bg-gray-300' ?>" id="dot-login"></span>
                                <span class="text-xs font-semibold text-gray-700">Vendor Portal Login (<span id="txt-login"><?= $vendorEnabled ? 'Accessible' : 'Restricted' ?></span>)</span>
                            </div>
                            <div class="bg-gray-50 border border-gray-100 rounded-xl p-3 flex items-center gap-2.5">
                                <span class="w-2.5 h-2.5 rounded-full <?= $vendorEnabled ? 'bg-emerald-500' : 'bg-gray-300' ?>" id="dot-storefront"></span>
                                <span class="text-xs font-semibold text-gray-700">Storefront Vendor Tabs (<span id="txt-storefront"><?= $vendorEnabled ? 'Visible' : 'Hidden' ?></span>)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Switch Control -->
                <div class="flex flex-col items-center lg:items-end justify-center shrink-0 border-t lg:border-t-0 lg:border-l border-gray-100 pt-6 lg:pt-0 lg:pl-8">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Master Switch</span>
                    
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" id="vendorModuleToggle" class="sr-only peer" <?= $vendorEnabled ? 'checked' : '' ?> onchange="handleVendorToggleChange(this.checked)">
                        <div class="w-16 h-9 bg-gray-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-7 peer-checked:after:border-white after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-7 after:w-7 after:transition-all peer-checked:bg-emerald-600 shadow-inner"></div>
                    </label>

                    <span id="switch-status-label" class="text-xs font-black mt-2 <?= $vendorEnabled ? 'text-emerald-700' : 'text-gray-500' ?>">
                        <?= $vendorEnabled ? 'ENABLED' : 'DISABLED' ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Platform System Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black">
                    PHP
                </div>
                <div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Environment</span>
                    <h4 class="text-sm font-bold text-gray-900 mt-0.5">PHP <?= PHP_VERSION ?></h4>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-black">
                    DB
                </div>
                <div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Database Engine</span>
                    <h4 class="text-sm font-bold text-gray-900 mt-0.5">MariaDB / MySQL (Port 3307)</h4>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-black">
                    ⚡
                </div>
                <div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Upload Max Size</span>
                    <h4 class="text-sm font-bold text-gray-900 mt-0.5"><?= ini_get('upload_max_filesize') ?> (Post: <?= ini_get('post_max_size') ?>)</h4>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-black">
                    🔒
                </div>
                <div>
                    <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Session Handler</span>
                    <h4 class="text-sm font-bold text-gray-900 mt-0.5">Database Session Storage</h4>
                </div>
            </div>
        </div>

    </div>

    <!-- ==================== TAB 2: DATA BACKUP & RESTORE ==================== -->
    <div id="tab-content-backup" class="settings-tab-pane <?= $activeTab === 'backup' ? '' : 'hidden' ?> space-y-8">
        
        <!-- Summary Metric Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Database Tables</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1"><?= $totalTables ?></h3>
                    <p class="text-xs text-gray-500 mt-1">Full relational schema</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Database Records</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1"><?= number_format($totalRows) ?></h3>
                    <p class="text-xs text-gray-500 mt-1">Users, products, orders & logs</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Estimated Data Size</span>
                    <h3 class="text-3xl font-extrabold text-gray-900 mt-1"><?= $totalSizeMb ?> <span class="text-lg font-normal text-gray-500">MB</span></h3>
                    <p class="text-xs text-gray-500 mt-1">Indexes + Data footprint</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
            </div>
        </div>

        <!-- 2 Main Action Cards: EXPORT & RESTORE -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- CARD 1: EXPORT -->
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-950 rounded-3xl p-8 text-white shadow-lg flex flex-col justify-between relative overflow-hidden">
                <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-2xl pointer-events-none"></div>

                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-indigo-500/30 text-indigo-300 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider border border-indigo-400/30">
                            1-Click Full Export
                        </span>
                        <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path></svg>
                    </div>

                    <h3 class="text-2xl font-bold text-white mb-2">Export Complete Database</h3>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Export all Super Admin records, buyer accounts, vendors, complete product catalog, order modifications, inventory, fee rules, CMS layouts, and platform settings into a single structured JSON backup file.
                    </p>

                    <div class="mt-6 space-y-2 text-xs text-slate-400">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Includes all 38+ relational tables & foreign keys</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Includes encrypted user hashes, role RBAC matrix, and audit logs</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Safe UTF-8 Unicode serialization</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-700/60 flex items-center justify-between">
                    <span class="text-xs text-slate-400 font-mono">Format: .json</span>
                    <a href="<?= BASE_URL ?>/admin/settings/backup/export" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-wider transition shadow-md flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Backup File
                    </a>
                </div>
            </div>

            <!-- CARD 2: RESTORE -->
            <div class="bg-white rounded-3xl p-8 border border-gray-200/80 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="bg-rose-100 text-rose-800 text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-wider border border-rose-200">
                            Transactional Restore
                        </span>
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    </div>

                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Restore From Backup File</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Upload a previously exported <code class="bg-gray-100 px-1.5 py-0.5 rounded text-rose-600 font-bold">.json</code> backup file to completely restore all database tables. The import executes inside a strict transactional boundary with foreign-key validation.
                    </p>

                    <!-- File Dropzone Form -->
                    <form id="restoreBackupForm" action="<?= BASE_URL ?>/admin/settings/backup/import" method="POST" enctype="multipart/form-data" class="mt-6">
                        <div class="border-2 border-dashed border-gray-300 hover:border-brand-500 rounded-2xl p-6 text-center transition cursor-pointer bg-gray-50/50 hover:bg-pink-50/20" onclick="document.getElementById('backupFileInput').click()">
                            <input type="file" name="backup_file" id="backupFileInput" accept=".json" class="hidden" onchange="handleFileSelect(this)">
                            
                            <div class="w-12 h-12 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-3 text-gray-400" id="upload-icon-box">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            </div>

                            <p class="text-sm font-bold text-gray-800" id="upload-label-main">Click to select or drag & drop backup file</p>
                            <p class="text-xs text-gray-400 mt-1" id="upload-label-sub">Only valid GRM B2B JSON backups (.json) up to 50MB</p>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <span class="text-xs text-amber-700 bg-amber-50 px-2.5 py-1 rounded-lg font-medium">⚠️ Existing data will be overwritten</span>
                            <button type="button" onclick="confirmAndRestoreDatabase()" id="btnRestoreSubmit" class="bg-rose-600 hover:bg-rose-700 text-white px-6 py-3 rounded-2xl font-bold text-xs uppercase tracking-wider transition shadow-md flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Restore Database Now
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Table Statistics Breakdown -->
        <div class="bg-white rounded-3xl border border-gray-200/80 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Database Tables Inventory</h3>
                    <p class="text-xs text-gray-500">Live breakdown of individual table row counts and physical disk footprints.</p>
                </div>
                <div>
                    <input type="text" id="tableFilterInput" onkeyup="filterTableStats()" placeholder="Filter tables..." class="text-xs border border-gray-200 rounded-xl px-4 py-2 w-64 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="overflow-x-auto max-h-[450px]">
                <table class="w-full text-left border-collapse" id="tableStatsTable">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 text-[11px] uppercase tracking-wider font-extrabold border-b border-gray-100 sticky top-0 bg-gray-50 z-10">
                            <th class="py-3 px-6">#</th>
                            <th class="py-3 px-6">Table Name</th>
                            <th class="py-3 px-6">Category / Module</th>
                            <th class="py-3 px-6 text-right">Row Count</th>
                            <th class="py-3 px-6 text-right">Data Size</th>
                            <th class="py-3 px-6 text-right">Index Size</th>
                            <th class="py-3 px-6 text-center">Engine</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-xs text-gray-700 font-medium">
                        <?php 
                        $i = 1;
                        foreach ($tableStats as $stat): 
                            // Determine friendly category
                            $t = $stat['table_name'];
                            $cat = 'General';
                            if (strpos($t, 'user') !== false || strpos($t, 'buyer') !== false || strpos($t, 'role') !== false || strpos($t, 'permission') !== false || strpos($t, 'otp') !== false) {
                                $cat = 'Auth & Users';
                            } elseif (strpos($t, 'vendor') !== false) {
                                $cat = 'Vendor Module';
                            } elseif (strpos($t, 'product') !== false || strpos($t, 'categor') !== false || strpos($t, 'attribute') !== false || strpos($t, 'inventory') !== false) {
                                $cat = 'Catalog & Inventory';
                            } elseif (strpos($t, 'order') !== false || strpos($t, 'refund') !== false || strpos($t, 'cancel') !== false || strpos($t, 'fee') !== false) {
                                $cat = 'Orders & Commerce';
                            } elseif (strpos($t, 'cms') !== false || strpos($t, 'banner') !== false || strpos($t, 'footer') !== false || strpos($t, 'topbar') !== false || strpos($t, 'navbar') !== false) {
                                $cat = 'CMS & Layouts';
                            } elseif (strpos($t, 'finance') !== false || strpos($t, 'wallet') !== false || strpos($t, 'payment') !== false || strpos($t, 'withdrawal') !== false) {
                                $cat = 'Finance & Payouts';
                            } elseif (strpos($t, 'setting') !== false || strpos($t, 'audit') !== false || strpos($t, 'support') !== false || strpos($t, 'media') !== false) {
                                $cat = 'System & Media';
                            }
                        ?>
                            <tr class="hover:bg-pink-50/30 transition-colors">
                                <td class="py-3 px-6 text-gray-400 font-mono"><?= $i++ ?></td>
                                <td class="py-3 px-6 font-bold font-mono text-gray-900"><?= htmlspecialchars($stat['table_name']) ?></td>
                                <td class="py-3 px-6">
                                    <span class="bg-gray-100 text-gray-700 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                        <?= $cat ?>
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-right font-mono font-bold <?= $stat['rows'] > 0 ? 'text-gray-900' : 'text-gray-400' ?>">
                                    <?= number_format($stat['rows']) ?>
                                </td>
                                <td class="py-3 px-6 text-right font-mono text-gray-600">
                                    <?= number_format($stat['data_size_kb'], 1) ?> KB
                                </td>
                                <td class="py-3 px-6 text-right font-mono text-gray-400">
                                    <?= number_format($stat['index_size_kb'], 1) ?> KB
                                </td>
                                <td class="py-3 px-6 text-center">
                                    <span class="bg-emerald-50 text-emerald-700 font-mono text-[10px] font-bold px-2 py-0.5 rounded">
                                        <?= htmlspecialchars($stat['engine'] ?? 'InnoDB') ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <?php if ($vendorEnabled): ?>
    <!-- ==================== TAB 3: VENDOR BANK ACCOUNTS & PAYOUTS ==================== -->
    <div id="tab-content-bank_accounts" class="settings-tab-pane <?= $activeTab === 'bank_accounts' ? '' : 'hidden' ?> space-y-6">
        
        <!-- Top Action Bar -->
        <div class="flex items-center justify-between bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Payout Bank Accounts</h3>
                <p class="text-xs text-gray-500">Configure corporate settlement bank accounts for vendor disbursements and ledger tracking.</p>
            </div>
            <button onclick="openAddBankModal()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-5 py-2.5 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Bank Account
            </button>
        </div>

        <!-- Bank Accounts Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($bankAccounts)): ?>
                <div class="col-span-full bg-white rounded-2xl p-12 text-center border border-gray-100 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1">No Bank Accounts Configured</h3>
                    <p class="text-sm text-gray-500 max-w-md mx-auto mb-6">Add bank account details to enable automated payout workflows, wallet withdrawals, and settlement tracking.</p>
                    <button onclick="openAddBankModal()" class="bg-brand-600 hover:bg-brand-700 text-white px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider shadow">
                        Add First Bank Account
                    </button>
                </div>
            <?php else: ?>
                <?php foreach ($bankAccounts as $acc): ?>
                    <div class="bg-white rounded-2xl border <?= $acc['is_primary'] ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-md' : 'border-gray-200 hover:border-gray-300 shadow-sm' ?> p-6 relative flex flex-col justify-between transition-all">
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-9 h-9 rounded-lg <?= $acc['is_primary'] ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' ?> flex items-center justify-center font-black text-sm">
                                        🏦
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-gray-900 leading-tight"><?= htmlspecialchars($acc['bank_name']) ?></h4>
                                        <span class="text-[10px] uppercase font-semibold text-gray-400"><?= htmlspecialchars($acc['account_type']) ?> Account</span>
                                    </div>
                                </div>

                                <?php if ($acc['is_primary']): ?>
                                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-1 rounded-full uppercase tracking-wider flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Primary
                                    </span>
                                <?php else: ?>
                                    <button onclick="setPrimaryAccount(<?= $acc['id'] ?>)" class="text-xs text-brand-600 hover:text-brand-800 font-bold hover:underline">
                                        Set Active &rarr;
                                    </button>
                                <?php endif; ?>
                            </div>

                            <div class="bg-gray-50 rounded-xl p-4 space-y-2 mb-4">
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">Account No:</span>
                                    <span class="font-mono font-bold text-gray-900"><?= htmlspecialchars($acc['account_number']) ?></span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">Beneficiary:</span>
                                    <span class="font-bold text-gray-900"><?= htmlspecialchars($acc['account_holder_name']) ?></span>
                                </div>
                                <div class="flex justify-between text-xs">
                                    <span class="text-gray-500">IFSC Code:</span>
                                    <span class="font-mono font-bold text-gray-900 uppercase"><?= htmlspecialchars($acc['ifsc_code']) ?></span>
                                </div>
                                <?php if (!empty($acc['upi_id'])): ?>
                                    <div class="flex justify-between text-xs">
                                        <span class="text-gray-500">UPI VPA:</span>
                                        <span class="font-mono font-bold text-brand-600"><?= htmlspecialchars($acc['upi_id']) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                            <button onclick='editBankAccount(<?= json_encode($acc) ?>)' class="p-2 text-gray-400 hover:text-blue-600 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <button onclick="deleteBankAccount(<?= $acc['id'] ?>)" class="p-2 text-gray-400 hover:text-rose-600 rounded-lg hover:bg-gray-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
    <?php endif; ?>
</div>

<!-- Bank Account Modal (Add/Edit) -->
<div id="bankAccountModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-8 shadow-2xl border border-gray-100 transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-gray-900" id="modalBankTitle">Add New Bank Account</h3>
                <p class="text-xs text-gray-500">Enter validated bank details for payouts.</p>
            </div>
            <button onclick="closeBankModal()" class="w-8 h-8 rounded-full bg-gray-100 text-gray-400 hover:text-gray-600 flex items-center justify-center">
                ✕
            </button>
        </div>

        <form id="bankAccountForm" onsubmit="handleBankSubmit(event)" class="space-y-4">
            <input type="hidden" id="bank_id" name="id" value="">

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Bank Name *</label>
                <input type="text" id="bank_name" name="bank_name" required placeholder="e.g. HDFC Bank, ICICI Bank" class="w-full text-xs border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Account Holder Name *</label>
                <input type="text" id="account_holder_name" name="account_holder_name" required placeholder="Name as per bank passbook" class="w-full text-xs border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Account Number *</label>
                    <input type="text" id="account_number" name="account_number" required placeholder="9-18 digits" class="w-full text-xs font-mono border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">IFSC Code *</label>
                    <input type="text" id="ifsc_code" name="ifsc_code" required placeholder="e.g. HDFC0001234" class="w-full text-xs font-mono uppercase border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Branch Name</label>
                    <input type="text" id="branch_name" name="branch_name" placeholder="Branch location" class="w-full text-xs border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Account Type</label>
                    <select id="account_type" name="account_type" class="w-full text-xs border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        <option value="current">Current Account</option>
                        <option value="savings">Savings Account</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">UPI ID (Optional)</label>
                <input type="text" id="upi_id" name="upi_id" placeholder="username@upi" class="w-full text-xs border border-gray-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-brand-500 focus:outline-none">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" id="is_primary" name="is_primary" value="1" class="w-4 h-4 text-brand-600 rounded">
                <label for="is_primary" class="text-xs font-bold text-gray-700">Set as Primary Settlement Account</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="closeBankModal()" class="px-5 py-2.5 rounded-xl border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="bg-[#F25996] hover:bg-[#e04481] text-white px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow">
                    Save Account
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Tab Switching
function switchSettingsTab(tabName) {
    document.querySelectorAll('.settings-tab-pane').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-brand-600', 'text-brand-700');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    const targetPane = document.getElementById('tab-content-' + tabName);
    const targetBtn = document.getElementById('tab-btn-' + tabName);

    if (targetPane && targetBtn) {
        targetPane.classList.remove('hidden');
        targetBtn.classList.remove('border-transparent', 'text-gray-500');
        targetBtn.classList.add('border-brand-600', 'text-brand-700');
    }

    // Update URL query without page reload
    const url = new URL(window.location.href);
    url.searchParams.set('tab', tabName);
    window.history.replaceState({}, '', url);
}

// Vendor Module Toggle Handler with SweetAlert2
function handleVendorToggleChange(isChecked) {
    const actionText = isChecked ? 'ENABLE' : 'DISABLE';
    const warningText = isChecked 
        ? 'Enabling vendor module will reopen vendor registration, allow seller portal login, and restore vendor tabs across the storefront.'
        : 'Disabling vendor module will close vendor registration, block seller portal access, and hide vendor links from the storefront. Existing data will remain safe.';

    Swal.fire({
        title: `Are you sure you want to ${actionText} Vendor Module?`,
        text: warningText,
        icon: isChecked ? 'question' : 'warning',
        showCancelButton: true,
        confirmButtonColor: isChecked ? '#10B981' : '#E11D48',
        cancelButtonColor: '#6B7280',
        confirmButtonText: `Yes, ${actionText} Module`,
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Updating System Configuration...',
                text: 'Flushing cache and applying settings across all platform nodes...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('<?= BASE_URL ?>/admin/settings/toggle-vendor', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: isChecked ? 1 : 0 })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: data.message,
                        confirmButtonColor: '#F25996'
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    document.getElementById('vendorModuleToggle').checked = !isChecked;
                    Swal.fire({
                        icon: 'error',
                        title: 'Action Failed',
                        text: data.message || 'Could not update vendor module state.',
                        confirmButtonColor: '#F25996'
                    });
                }
            })
            .catch(err => {
                document.getElementById('vendorModuleToggle').checked = !isChecked;
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to communicate with server: ' + err.message,
                    confirmButtonColor: '#F25996'
                });
            });
        } else {
            // Revert toggle switch
            document.getElementById('vendorModuleToggle').checked = !isChecked;
        }
    });
}

// Backup File Select Feedback
function handleFileSelect(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('upload-label-main').textContent = file.name;
        document.getElementById('upload-label-sub').textContent = `Size: ${(file.size / 1024).toFixed(1)} KB — Ready to restore`;
        document.getElementById('upload-icon-box').className = 'w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full shadow-sm flex items-center justify-center mx-auto mb-3';
    }
}

// Confirm and Restore Database
function confirmAndRestoreDatabase() {
    const fileInput = document.getElementById('backupFileInput');
    if (!fileInput.files || !fileInput.files[0]) {
        Swal.fire({
            icon: 'warning',
            title: 'No File Selected',
            text: 'Please select a valid .json database backup file first.',
            confirmButtonColor: '#F25996'
        });
        return;
    }

    const fileName = fileInput.files[0].name;

    Swal.fire({
        title: '⚠️ CRITICAL: Restore Database?',
        html: `You are about to restore the database from <b>${fileName}</b>.<br><br><span style="color:#e11d48; font-weight:bold;">Warning: Existing records across all tables will be wiped and replaced with the backup snapshot.</span><br><br>Do you wish to proceed?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E11D48',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, Restore Now',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Restoring Database...',
                text: 'Executing transactional restore, verifying foreign keys, and flushing cache...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            document.getElementById('restoreBackupForm').submit();
        }
    });
}

// Filter Table Statistics
function filterTableStats() {
    const query = document.getElementById('tableFilterInput').value.toLowerCase();
    const rows = document.querySelectorAll('#tableStatsTable tbody tr');

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
}

// Bank Modal Operations
function openAddBankModal() {
    document.getElementById('bankAccountForm').reset();
    document.getElementById('bank_id').value = '';
    document.getElementById('modalBankTitle').textContent = 'Add New Bank Account';
    document.getElementById('bankAccountModal').classList.remove('hidden');
}

function closeBankModal() {
    document.getElementById('bankAccountModal').classList.add('hidden');
}

function editBankAccount(acc) {
    document.getElementById('bank_id').value = acc.id || '';
    document.getElementById('bank_name').value = acc.bank_name || '';
    document.getElementById('account_holder_name').value = acc.account_holder_name || '';
    document.getElementById('account_number').value = acc.account_number || '';
    document.getElementById('ifsc_code').value = acc.ifsc_code || '';
    document.getElementById('branch_name').value = acc.branch_name || '';
    document.getElementById('account_type').value = acc.account_type || 'current';
    document.getElementById('upi_id').value = acc.upi_id || '';
    document.getElementById('is_primary').checked = (acc.is_primary == 1);
    document.getElementById('modalBankTitle').textContent = 'Edit Bank Account';
    document.getElementById('bankAccountModal').classList.remove('hidden');
}

function handleBankSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    fetch('<?= BASE_URL ?>/admin/vendor/settings/bank-accounts/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            Swal.fire({
                icon: 'success',
                title: 'Saved!',
                text: res.message,
                confirmButtonColor: '#F25996'
            }).then(() => {
                window.location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: res.message,
                confirmButtonColor: '#F25996'
            });
        }
    })
    .catch(err => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: err.message,
            confirmButtonColor: '#F25996'
        });
    });
}

function setPrimaryAccount(id) {
    Swal.fire({
        title: 'Set as Primary Payout Destination?',
        text: 'All automated finance disbursals and wallet withdrawals will be directed to this bank.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10B981',
        confirmButtonText: 'Yes, Set Primary'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('<?= BASE_URL ?>/admin/vendor/settings/bank-accounts/set-primary', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: res.message,
                        confirmButtonColor: '#F25996'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        confirmButtonColor: '#F25996'
                    });
                }
            });
        }
    });
}

function deleteBankAccount(id) {
    Swal.fire({
        title: 'Delete Bank Account?',
        text: 'Are you sure you want to remove this bank account record?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E11D48',
        confirmButtonText: 'Yes, Delete'
    }).then(result => {
        if (result.isConfirmed) {
            fetch('<?= BASE_URL ?>/admin/vendor/settings/bank-accounts/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: res.message,
                        confirmButtonColor: '#F25996'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        confirmButtonColor: '#F25996'
                    });
                }
            });
        }
    });
}
</script>
