<div class="w-full">
    <!-- Header Section -->
    <div class="mb-8 relative z-10">
        <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
            Dashboard <span class="mx-1 text-gray-300">›</span> <?= $isVendor ? 'Vendor Portal' : 'Administration' ?> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Support & Helpdesk</span>
        </div>
        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-5xl font-display font-extrabold text-gray-900 tracking-tight">
                    <?= $isVendor ? 'Support & Query Management' : 'Vendor Support Desk & Queries' ?>
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    <?= $isVendor ? 'Raise queries with priority levels (High, Medium, Low), communicate with platform administrators, and track ticket resolutions.' : 'Review, prioritize, and resolve support queries raised by vendors.' ?>
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <?php if ($isVendor): ?>
                    <button onclick="openNewTicketModal()" class="bg-[#F25996] hover:bg-[#e04481] text-white px-5 py-2.5 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Raise New Query
                    </button>
                <?php else: ?>
                    <button onclick="window.location.reload()" class="bg-white hover:bg-gray-50 text-gray-700 px-5 py-2.5 rounded-full font-bold text-xs border border-gray-200 shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Refresh
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Stats Overview Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center">
            <div class="p-3 bg-gray-100 text-gray-700 rounded-xl mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-gray-400">Total Queries</p>
                <h3 class="text-xl font-extrabold text-gray-900"><?= $counts['total'] ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center">
            <div class="p-3 bg-amber-50 text-amber-600 rounded-xl mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-amber-500">Open Tickets</p>
                <h3 class="text-xl font-extrabold text-amber-600"><?= $counts['open'] ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center">
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-indigo-500">In Progress</p>
                <h3 class="text-xl font-extrabold text-indigo-600"><?= $counts['in_progress'] ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center">
            <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-emerald-600">Resolved / Closed</p>
                <h3 class="text-xl font-extrabold text-emerald-600"><?= $counts['resolved'] + $counts['closed'] ?></h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center">
            <div class="p-3 bg-rose-50 text-rose-600 rounded-xl mr-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <p class="text-[10px] uppercase font-bold text-rose-500">High Priority Active</p>
                <h3 class="text-xl font-extrabold text-rose-600"><?= $counts['high_priority_active'] ?></h3>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6 flex flex-wrap items-center justify-between gap-4">
        <!-- Status Tabs -->
        <div class="flex items-center space-x-2 text-xs font-semibold overflow-x-auto">
            <?php
            $statuses = [
                'all'         => 'All Queries',
                'open'        => 'Open',
                'in_progress' => 'In Progress',
                'resolved'    => 'Resolved',
                'closed'      => 'Closed'
            ];
            foreach ($statuses as $k => $label):
                $active = ($statusFilter === $k);
            ?>
                <a href="?status=<?= $k ?><?= $priorityFilter !== 'all' ? '&priority=' . $priorityFilter : '' ?><?= !empty($vendorFilter) ? '&vendor_id=' . $vendorFilter : '' ?>" class="px-3 py-1.5 rounded-xl transition <?= $active ? 'bg-brand-600 text-white font-bold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                    <?= $label ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Priority & Search Filter -->
        <div class="flex items-center space-x-3 ml-auto text-xs">
            <select onchange="location = this.value;" class="px-3 py-1.5 border border-gray-200 rounded-xl bg-white text-gray-700 font-medium focus:ring-brand-500">
                <option value="?status=<?= $statusFilter ?><?= !empty($vendorFilter) ? '&vendor_id=' . $vendorFilter : '' ?>&priority=all" <?= $priorityFilter === 'all' ? 'selected' : '' ?>>All Priorities</option>
                <option value="?status=<?= $statusFilter ?><?= !empty($vendorFilter) ? '&vendor_id=' . $vendorFilter : '' ?>&priority=high" <?= $priorityFilter === 'high' ? 'selected' : '' ?>>🔴 High Priority</option>
                <option value="?status=<?= $statusFilter ?><?= !empty($vendorFilter) ? '&vendor_id=' . $vendorFilter : '' ?>&priority=medium" <?= $priorityFilter === 'medium' ? 'selected' : '' ?>>🟡 Medium Priority</option>
                <option value="?status=<?= $statusFilter ?><?= !empty($vendorFilter) ? '&vendor_id=' . $vendorFilter : '' ?>&priority=low" <?= $priorityFilter === 'low' ? 'selected' : '' ?>>🔵 Low Priority</option>
            </select>

            <?php if (!$isVendor && !empty($vendorsList)): ?>
                <select onchange="location = this.value;" class="px-3 py-1.5 border border-gray-200 rounded-xl bg-white text-gray-700 font-medium focus:ring-brand-500 max-w-[180px] truncate">
                    <option value="?status=<?= $statusFilter ?>&priority=<?= $priorityFilter ?>">All Vendors</option>
                    <?php foreach ($vendorsList as $v): ?>
                        <option value="?status=<?= $statusFilter ?>&priority=<?= $priorityFilter ?>&vendor_id=<?= $v['user_id'] ?>" <?= $vendorFilter == $v['user_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($v['store_name'] ?: $v['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-left">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5">Ticket Ref #</th>
                        <?php if (!$isVendor): ?>
                            <th class="px-6 py-3.5">Vendor / Store</th>
                        <?php endif; ?>
                        <th class="px-6 py-3.5">Subject & Category</th>
                        <th class="px-6 py-3.5">Priority</th>
                        <th class="px-6 py-3.5">Status</th>
                        <th class="px-6 py-3.5">Last Activity</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm font-medium">
                    <?php if (empty($tickets)): ?>
                        <tr>
                            <td colspan="<?= $isVendor ? 6 : 7 ?>" class="px-6 py-12 text-center text-gray-400">
                                <div class="w-12 h-12 rounded-full bg-gray-50 text-gray-300 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-600">No support queries found</p>
                                <p class="text-xs text-gray-400 mt-1"><?= $isVendor ? 'Have a question or need assistance? Click "Raise New Query" above.' : 'No vendor queries matching the selected filter criteria.' ?></p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($tickets as $tkt): ?>
                            <tr class="hover:bg-gray-50/80 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-mono font-bold text-brand-700 text-xs">
                                    <?= htmlspecialchars($tkt['ticket_number']) ?>
                                </td>

                                <?php if (!$isVendor): ?>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 text-xs"><?= htmlspecialchars($tkt['vendor_store_name'] ?: $tkt['vendor_name']) ?></div>
                                        <div class="text-[11px] text-gray-500"><?= htmlspecialchars($tkt['vendor_email']) ?></div>
                                    </td>
                                <?php endif; ?>

                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900 text-sm line-clamp-1"><?= htmlspecialchars($tkt['subject']) ?></div>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-full"><?= htmlspecialchars($tkt['category']) ?></span>
                                        <span class="text-[10px] text-gray-400"><?= (int)$tkt['message_count'] ?> messages</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($tkt['priority'] === 'high'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-rose-100 text-rose-700 border border-rose-200 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> High
                                        </span>
                                    <?php elseif ($tkt['priority'] === 'medium'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 border border-amber-200 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Medium
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-100 text-blue-700 border border-blue-200 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Low
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if ($tkt['status'] === 'open'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">Open</span>
                                    <?php elseif ($tkt['status'] === 'in_progress'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-indigo-50 text-indigo-700 border border-indigo-200">In Progress</span>
                                    <?php elseif ($tkt['status'] === 'resolved'): ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">Resolved</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-600 border border-gray-200">Closed</span>
                                    <?php endif; ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    <div><?= date('M d, Y', strtotime($tkt['updated_at'])) ?></div>
                                    <div class="text-[10px] text-gray-400"><?= date('h:i A', strtotime($tkt['updated_at'])) ?></div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-xs">
                                    <button onclick="openTicketThread(<?= $tkt['id'] ?>)" class="bg-gray-100 hover:bg-brand-600 hover:text-white text-gray-700 px-3.5 py-1.5 rounded-xl font-bold transition shadow-sm inline-flex items-center gap-1.5">
                                        View Thread &rarr;
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Raise New Query (Vendor) -->
<div id="newTicketModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-100 flex flex-col my-auto">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-[#F25996] to-[#e04481] text-white">
            <div>
                <h3 class="font-bold text-base">Raise Support Query</h3>
                <p class="text-xs text-white/80">Submit your inquiry or issue directly to the platform administrators.</p>
            </div>
            <button onclick="closeNewTicketModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="newTicketForm" onsubmit="handleCreateTicket(event)" enctype="multipart/form-data" class="p-6 space-y-4">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Subject / Summary *</label>
                <input type="text" id="tkt_subject" name="subject" required placeholder="Brief summary of your query or issue" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-semibold text-gray-900">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Category / Department *</label>
                    <select id="tkt_category" name="category" required class="w-full px-3.5 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-medium text-gray-900 bg-white">
                        <option value="Finance & Payouts">💳 Finance & Payouts</option>
                        <option value="Orders & Fulfillment">🛒 Orders & Fulfillment</option>
                        <option value="Products & Catalog">📦 Products & Catalog</option>
                        <option value="Account & GST/KYC">🛡️ Account & KYC</option>
                        <option value="Technical & Bug">⚙️ Technical / Bug</option>
                        <option value="General Inquiry" selected>💬 General Inquiry</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Priority Level *</label>
                    <select id="tkt_priority" name="priority" required class="w-full px-3.5 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm font-bold text-gray-900 bg-white">
                        <option value="low">🔵 Low — General Question</option>
                        <option value="medium" selected>🟡 Medium — Standard Inquiry</option>
                        <option value="high">🔴 High — Urgent Issue</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Query Details / Notes *</label>
                <textarea id="tkt_message" name="message" rows="4" required placeholder="Please describe your query or problem in detail so our support team can assist quickly..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm text-gray-900"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Attachment / Screenshot (Optional)</label>
                <input type="file" id="tkt_attachment" name="attachment" accept="image/*,.pdf,.doc,.docx" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            </div>

            <div class="pt-4 flex justify-end space-x-3 border-t border-gray-100">
                <button type="button" onclick="closeNewTicketModal()" class="px-4 py-2 border border-gray-200 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-50 transition">Cancel</button>
                <button type="submit" id="createTktBtn" class="px-5 py-2 bg-[#F25996] hover:bg-[#e04481] text-white rounded-xl text-xs font-bold transition shadow">Send Query to Admin &rarr;</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Interactive Ticket Thread & Discussion -->
<div id="ticketThreadModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden border border-gray-100 flex flex-col max-h-[90vh] my-auto">
        <!-- Thread Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-slate-900 text-white shrink-0">
            <div>
                <div class="flex items-center gap-2">
                    <span id="th_tkt_no" class="font-mono text-[#F25996] font-bold text-xs">#TKT-</span>
                    <span id="th_priority_badge"></span>
                    <span id="th_status_badge"></span>
                </div>
                <h3 id="th_subject" class="font-bold text-base text-white mt-1"></h3>
                <p id="th_vendor_info" class="text-xs text-slate-400"></p>
            </div>
            <button onclick="closeTicketThreadModal()" class="text-white/80 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Admin Control Toolbar (Only for Admin) -->
        <?php if (!$isVendor): ?>
            <div class="bg-slate-50 px-6 py-3 border-b border-gray-200 flex flex-wrap items-center justify-between gap-3 text-xs shrink-0">
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-600">Update Status:</span>
                    <select id="th_admin_status_select" class="px-2.5 py-1 border border-gray-300 rounded-lg bg-white font-bold text-gray-800">
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <span class="font-bold text-gray-600">Priority:</span>
                    <select id="th_admin_priority_select" class="px-2.5 py-1 border border-gray-300 rounded-lg bg-white font-bold text-gray-800">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <button onclick="saveAdminTicketStatus()" class="px-3 py-1 bg-[#F25996] hover:bg-[#e04481] text-white font-bold rounded-lg transition shadow-xs">
                    Save Changes
                </button>
            </div>
        <?php endif; ?>

        <!-- Messages Trail -->
        <div id="threadMessagesContainer" class="p-6 space-y-4 overflow-y-auto flex-1 min-h-[220px] max-h-[400px] bg-gray-50/50">
            <!-- Messages inserted via JS -->
        </div>

        <!-- Reply Box -->
        <form id="replyTicketForm" onsubmit="handleSendReply(event)" class="p-4 bg-white border-t border-gray-200 shrink-0">
            <input type="hidden" id="reply_ticket_id" value="">
            <div class="space-y-3">
                <textarea id="reply_message" rows="2" required placeholder="Type your reply / note here..." class="w-full px-3.5 py-2 border border-gray-200 rounded-xl focus:ring-brand-500 focus:border-brand-500 text-sm text-gray-900"></textarea>

                <div class="flex items-center justify-between">
                    <input type="file" id="reply_attachment" name="attachment" accept="image/*,.pdf,.doc,.docx" class="text-xs text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">

                    <button type="submit" id="sendReplyBtn" class="px-5 py-2 bg-[#F25996] hover:bg-[#e04481] text-white rounded-xl text-xs font-bold transition shadow flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Send Reply
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let currentActiveTicketId = null;

function openNewTicketModal() {
    document.getElementById('newTicketForm').reset();
    document.getElementById('newTicketModal').classList.remove('hidden');
}

function closeNewTicketModal() {
    document.getElementById('newTicketModal').classList.add('hidden');
}

function handleCreateTicket(e) {
    e.preventDefault();
    const btn = document.getElementById('createTktBtn');
    btn.disabled = true;
    btn.innerText = 'Submitting...';

    const formData = new FormData(document.getElementById('newTicketForm'));

    fetch('<?= BASE_URL ?>/admin/support/create', {
        method: 'POST',
        headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async (r) => {
        const text = await r.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            console.error("Non-JSON response:", text);
            throw new Error(text.replace(/<[^>]*>?/gm, '').trim().substring(0, 200) || 'Server returned an invalid response');
        }
    })
    .then(res => {
        btn.disabled = false;
        btn.innerText = 'Send Query to Admin →';
        if (res.success) {
            alert(res.message || 'Support ticket created successfully!');
            window.location.reload();
        } else {
            alert(res.message || 'Failed to create ticket.');
        }
    })
    .catch((err) => {
        btn.disabled = false;
        btn.innerText = 'Send Query to Admin →';
        alert(err.message ? ('Error: ' + err.message) : 'An error occurred while creating ticket.');
    });
}

function openTicketThread(ticketId) {
    currentActiveTicketId = ticketId;
    document.getElementById('reply_ticket_id').value = ticketId;
    document.getElementById('threadMessagesContainer').innerHTML = '<div class="text-center py-8 text-gray-400 text-xs">Loading conversation...</div>';
    document.getElementById('ticketThreadModal').classList.remove('hidden');

    fetch('<?= BASE_URL ?>/admin/support/view/' + ticketId, {
        headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(async (r) => {
        const text = await r.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error("Non-JSON thread response:", text);
            throw new Error(text.replace(/<[^>]*>?/gm, '').trim().substring(0, 200) || 'Invalid server response');
        }
    })
    .then(res => {
        if (!res.success) {
            alert(res.message || 'Ticket not found.');
            closeTicketThreadModal();
            return;
        }

        const tkt = res.ticket;
        document.getElementById('th_tkt_no').innerText = '#' + tkt.ticket_number;
        document.getElementById('th_subject').innerText = tkt.subject;
        document.getElementById('th_vendor_info').innerText = 'Category: ' + tkt.category + (tkt.vendor_store_name ? (' | Vendor: ' + tkt.vendor_store_name) : '');

        // Set Priority Badge
        let priBadge = '';
        if (tkt.priority === 'high') {
            priBadge = '<span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full uppercase">High Priority</span>';
        } else if (tkt.priority === 'medium') {
            priBadge = '<span class="bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Medium</span>';
        } else {
            priBadge = '<span class="bg-blue-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Low</span>';
        }
        document.getElementById('th_priority_badge').innerHTML = priBadge;

        // Set Status Badge
        let stBadge = `<span class="bg-slate-700 text-white text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">${tkt.status.replace('_', ' ')}</span>`;
        document.getElementById('th_status_badge').innerHTML = stBadge;

        // Admin selects if present
        const stSelect = document.getElementById('th_admin_status_select');
        if (stSelect) stSelect.value = tkt.status;
        const prSelect = document.getElementById('th_admin_priority_select');
        if (prSelect) prSelect.value = tkt.priority;

        // Render messages
        renderMessages(res.messages);
    })
    .catch((err) => {
        alert(err.message ? ('Error: ' + err.message) : 'Failed to load ticket thread.');
        closeTicketThreadModal();
    });
}

function renderMessages(messages) {
    const container = document.getElementById('threadMessagesContainer');
    if (!messages || messages.length === 0) {
        container.innerHTML = '<div class="text-center py-6 text-gray-400 text-xs">No messages yet.</div>';
        return;
    }

    let html = '';
    messages.forEach(m => {
        const isFromAdmin = (m.sender_role === 'admin' || m.sender_role === 'super_admin' || m.sender_role === 'staff');
        
        html += `
            <div class="flex flex-col ${isFromAdmin ? 'items-end' : 'items-start'}">
                <div class="flex items-center gap-1.5 mb-1 text-[11px] text-gray-400 font-medium">
                    <span class="font-bold ${isFromAdmin ? 'text-indigo-600' : 'text-gray-800'}">${m.sender_name} (${isFromAdmin ? 'Support Team' : 'Vendor'})</span>
                    <span>•</span>
                    <span>${new Date(m.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', month:'short', day:'numeric'})}</span>
                </div>
                <div class="max-w-[85%] rounded-2xl p-4 text-xs font-medium leading-relaxed ${isFromAdmin ? 'bg-indigo-600 text-white rounded-tr-none shadow-sm' : 'bg-white text-gray-800 border border-gray-200 rounded-tl-none shadow-xs'}">
                    <p class="whitespace-pre-wrap">${m.message}</p>
                    ${m.attachment ? (() => {
                        const attUrl = (m.attachment.startsWith('http://') || m.attachment.startsWith('https://')) ? m.attachment : ('<?= BASE_URL ?>/' + m.attachment);
                        const isImg = m.attachment.match(/\.(jpg|jpeg|png|webp|gif|svg)($|\?)/i) || m.attachment.includes('/image/upload/');
                        return `
                            <div class="mt-2.5 pt-2 border-t ${isFromAdmin ? 'border-indigo-500/50' : 'border-gray-100'}">
                                ${isImg ? `
                                    <a href="${attUrl}" target="_blank" class="block mb-2 group max-w-[200px]">
                                        <img src="${attUrl}" class="rounded-xl border ${isFromAdmin ? 'border-indigo-400' : 'border-gray-200'} max-h-32 object-cover shadow-xs group-hover:opacity-90 transition" alt="Attachment Preview">
                                    </a>
                                ` : ''}
                                <a href="${attUrl}" target="_blank" class="inline-flex items-center gap-1.5 font-bold ${isFromAdmin ? 'text-indigo-200 hover:text-white' : 'text-brand-600 hover:underline'} text-[11px]">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                    View / Download Attachment
                                </a>
                            </div>
                        `;
                    })() : ''}
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
    container.scrollTop = container.scrollHeight;
}

function closeTicketThreadModal() {
    document.getElementById('ticketThreadModal').classList.add('hidden');
    currentActiveTicketId = null;
}

function handleSendReply(e) {
    e.preventDefault();
    if (!currentActiveTicketId) return;

    const btn = document.getElementById('sendReplyBtn');
    btn.disabled = true;
    btn.innerText = 'Sending...';

    const formData = new FormData();
    formData.append('message', document.getElementById('reply_message').value);
    formData.append('ticket_id', currentActiveTicketId);
    const file = document.getElementById('reply_attachment').files[0];
    if (file) {
        formData.append('attachment', file);
    }

    fetch('<?= BASE_URL ?>/admin/support/reply/' + currentActiveTicketId, {
        method: 'POST',
        headers: { 
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async (r) => {
        const text = await r.text();
        try {
            return JSON.parse(text);
        } catch (err) {
            console.error("Non-JSON reply response:", text);
            throw new Error(text.replace(/<[^>]*>?/gm, '').trim().substring(0, 200) || 'Invalid server response');
        }
    })
    .then(res => {
        btn.disabled = false;
        btn.innerText = 'Send Reply';
        if (res.success) {
            document.getElementById('reply_message').value = '';
            document.getElementById('reply_attachment').value = '';
            // Reload thread
            openTicketThread(currentActiveTicketId);
        } else {
            alert(res.message || 'Failed to post reply.');
        }
    })
    .catch((err) => {
        btn.disabled = false;
        btn.innerText = 'Send Reply';
        alert(err.message ? ('Error: ' + err.message) : 'Failed to send reply.');
    });
}

function saveAdminTicketStatus() {
    if (!currentActiveTicketId) return;

    const status = document.getElementById('th_admin_status_select').value;
    const priority = document.getElementById('th_admin_priority_select').value;

    Promise.all([
        fetch('<?= BASE_URL ?>/admin/support/status/' + currentActiveTicketId, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status: status, ticket_id: currentActiveTicketId })
        }).then(r => r.json()),

        fetch('<?= BASE_URL ?>/admin/support/priority/' + currentActiveTicketId, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ priority: priority, ticket_id: currentActiveTicketId })
        }).then(r => r.json())
    ])
    .then(([resStatus, resPriority]) => {
        if (resStatus.success && resPriority.success) {
            alert('Ticket status and priority updated successfully.');
            window.location.reload();
        } else {
            alert(resStatus.message || resPriority.message || 'Failed to update ticket');
        }
    })
    .catch((err) => alert('Failed to update ticket changes: ' + (err.message || '')));
}
</script>
