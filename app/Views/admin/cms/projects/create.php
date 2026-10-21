<?php
// Add Project Page
?>
<div class="mb-8 relative z-10">
    <div class="text-sm font-medium text-gray-400 mb-2 font-serif tracking-wide">
        Dashboard <span class="mx-1 text-gray-300">›</span> CMS Management <span class="mx-1 text-gray-300">›</span> <a href="<?= BASE_URL ?>/admin/cms/projects" class="hover:underline">Our Project</a> <span class="mx-1 text-gray-300">›</span> <span class="text-brand-700">Add Project</span>
    </div>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-3xl sm:text-4xl font-display font-extrabold text-gray-900 tracking-tight">Add New Project</h2>
            <p class="text-sm text-gray-500 mt-1">Fill in the project details and attach multiple images and videos. All changes are rendered dynamically on the storefront.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>/admin/cms/projects" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-full font-bold text-xs shadow-sm transition-all flex items-center gap-2 uppercase tracking-wider">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Projects
            </a>
        </div>
    </div>
</div>

<form action="<?= BASE_URL ?>/admin/cms/projects/store" method="POST" enctype="multipart/form-data" class="space-y-8" id="projectForm">
    <!-- Section 1: Basic Information -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-3">
            <span class="w-2.5 h-2.5 rounded-full bg-[#F25996]"></span>
            Project Details
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Project Title -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Project Title <span class="text-red-500">*</span>
                </label>
                <input type="text" name="title" required placeholder="e.g., Luxury Bridal Couture Autumn Showcase" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-2 focus:ring-pink-100 outline-none transition-all text-sm font-semibold text-gray-800">
            </div>

            <!-- Project Subtitle -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Sub Title
                </label>
                <input type="text" name="subtitle" placeholder="e.g., Handcrafted Haute Couture / Spring-Summer B2B Collection" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-2 focus:ring-pink-100 outline-none transition-all text-sm text-gray-700">
            </div>

            <!-- Project Description -->
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Project Description
                </label>
                <textarea name="description" rows="5" placeholder="Write a comprehensive description of the project, fabrics used, design philosophy, client work, or collection overview..."
                          class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-2 focus:ring-pink-100 outline-none transition-all text-sm text-gray-700"></textarea>
            </div>

            <!-- Sort Order -->
            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                    Display Sort Order
                </label>
                <input type="number" name="sort_order" value="0" min="0" 
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-2 focus:ring-pink-100 outline-none transition-all text-sm font-mono text-gray-800">
                <p class="text-[11px] text-gray-400 mt-1">Lower numbers appear first on the storefront page.</p>
            </div>

            <!-- Active Status -->
            <div class="flex items-center pt-6">
                <label class="relative flex items-center gap-3 cursor-pointer select-none">
                    <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 text-[#F25996] rounded border-gray-300 focus:ring-[#F25996] cursor-pointer">
                    <span class="text-sm font-bold text-gray-800">Active (Visible on Storefront `/project`)</span>
                </label>
            </div>
        </div>
    </div>

    <!-- Section 2: Multiple Project Images (Upload + Cloudinary) -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                Project Images (Multiple)
            </h3>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="openCloudinaryForImage()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-pink-50 text-[#F25996] hover:bg-pink-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Cloudinary Media Library
                </button>
                <button type="button" onclick="promptImageUrl()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    + Add Image URL
                </button>
            </div>
        </div>

        <!-- File Upload Box -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                Upload New Image Files (Select Multiple)
            </label>
            <div class="border-2 border-dashed border-gray-200 hover:border-[#F25996] rounded-2xl p-6 text-center transition-colors cursor-pointer bg-gray-50/50 hover:bg-pink-50/20" onclick="document.getElementById('imageFileInput').click();">
                <input type="file" id="imageFileInput" name="image_files[]" multiple accept="image/*" class="hidden" onchange="previewSelectedImageFiles(this)">
                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                <div class="text-sm font-semibold text-gray-700">Click to browse or drop images here</div>
                <div class="text-xs text-gray-400 mt-1">Supports PNG, JPG, WEBP, GIF, SVG (multiple files supported)</div>
            </div>
        </div>

        <!-- Image Gallery Preview Container -->
        <div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Selected Images (<span id="imageCount">0</span>)</div>
            <div id="imageGalleryGrid" class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-4 min-h-[100px] p-4 bg-gray-50/60 rounded-2xl border border-gray-100">
                <div id="imageEmptyNotice" class="col-span-full py-8 text-center text-xs text-gray-400">
                    No images added yet. Click Cloudinary Media Library, Add URL, or choose files above.
                </div>
            </div>
        </div>
    </div>

    <!-- Section 3: Multiple Project Videos (Upload + Cloudinary + Embeds) -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 border-b border-gray-100 pb-3">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                Project Videos (Multiple)
            </h3>
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="openCloudinaryForVideo()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    Cloudinary Video Picker
                </button>
                <button type="button" onclick="promptVideoUrl()" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path></svg>
                    + Add Video URL (YouTube / Direct MP4)
                </button>
            </div>
        </div>

        <!-- Video Upload Box -->
        <div class="mb-6">
            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">
                Upload New Video Files (Select Multiple)
            </label>
            <div class="border-2 border-dashed border-gray-200 hover:border-indigo-400 rounded-2xl p-6 text-center transition-colors cursor-pointer bg-gray-50/50 hover:bg-indigo-50/20" onclick="document.getElementById('videoFileInput').click();">
                <input type="file" id="videoFileInput" name="video_files[]" multiple accept="video/mp4,video/webm,video/ogg,video/quicktime" class="hidden" onchange="previewSelectedVideoFiles(this)">
                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <div class="text-sm font-semibold text-gray-700">Click to browse or drop videos here</div>
                <div class="text-xs text-gray-400 mt-1">Supports MP4, WebM, MOV files</div>
            </div>
        </div>

        <!-- Video List Container -->
        <div>
            <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Selected Videos (<span id="videoCount">0</span>)</div>
            <div id="videoGalleryGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 min-h-[100px] p-4 bg-gray-50/60 rounded-2xl border border-gray-100">
                <div id="videoEmptyNotice" class="col-span-full py-8 text-center text-xs text-gray-400">
                    No videos added yet. Click Cloudinary Video Picker, Add URL, or choose video files above.
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="flex items-center justify-end gap-3 pt-4">
        <a href="<?= BASE_URL ?>/admin/cms/projects" class="px-6 py-3 rounded-full font-bold text-xs uppercase tracking-wider text-gray-600 hover:bg-gray-100 transition-colors">
            Cancel
        </a>
        <button type="submit" class="bg-[#F25996] hover:bg-[#e04481] text-white px-8 py-3 rounded-full font-bold text-xs shadow-md hover:shadow-lg transition-all uppercase tracking-wider flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            Save Project
        </button>
    </div>
</form>

<!-- Custom URL Input Modal -->
<div id="urlInputModal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-xs hidden items-center justify-center p-4" onclick="if(event.target===this) closeUrlModal();">
    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-gray-100" onclick="event.stopPropagation();">
        <h4 id="urlModalTitle" class="text-base font-bold text-gray-900 mb-1">Add Media URL</h4>
        <p id="urlModalDesc" class="text-xs text-gray-500 mb-4">Enter URL</p>
        <input type="text" id="urlModalInput" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:border-[#F25996] focus:ring-2 focus:ring-pink-100 outline-none text-sm text-gray-800 mb-4 font-mono" onkeydown="if(event.key==='Enter'){event.preventDefault(); confirmUrlModal();}">
        <div class="flex items-center justify-end gap-2">
            <button type="button" onclick="closeUrlModal()" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-100 rounded-full uppercase tracking-wider">Cancel</button>
            <button type="button" onclick="confirmUrlModal()" class="px-6 py-2 text-xs font-bold text-white bg-[#F25996] hover:bg-[#e04481] rounded-full uppercase tracking-wider shadow-sm">Add</button>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/Views/admin/media/_selector_modal.php'; ?>

<script>
// Image Management Logic
let imageFilesDataTransfer = new DataTransfer();

function updateImageCount() {
    const count = document.querySelectorAll('.image-item-card').length;
    document.getElementById('imageCount').innerText = count;
    const emptyNotice = document.getElementById('imageEmptyNotice');
    if (emptyNotice) {
        emptyNotice.style.display = count === 0 ? 'block' : 'none';
    }
}

function addImageCard(url) {
    if (!url) return;
    const grid = document.getElementById('imageGalleryGrid');
    const card = document.createElement('div');
    card.className = 'image-item-card relative group rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-xs aspect-square';
    card.innerHTML = `
        <img src="${url}" class="w-full h-full object-cover">
        <input type="hidden" name="image_urls[]" value="${url}">
        <!-- Top Right Delete Button (Always Visible & Accessible) -->
        <button type="button" onclick="this.closest('.image-item-card').remove(); updateImageCount();" 
                class="absolute top-2 right-2 z-10 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-md transition-all hover:scale-110 flex items-center justify-center" title="Remove image">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
        </button>
        <!-- Center Hover Overlay -->
        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 pointer-events-none group-hover:pointer-events-auto">
            <button type="button" onclick="this.closest('.image-item-card').remove(); updateImageCount();" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-full shadow-md text-xs font-bold transition-transform hover:scale-105 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Remove
            </button>
        </div>
        <div class="absolute bottom-1 left-1 bg-black/60 text-white text-[9px] px-1.5 py-0.5 rounded font-mono truncate max-w-[85%]">
            URL / Cloudinary
        </div>
    `;
    grid.appendChild(card);
    updateImageCount();
}

function promptImageUrl() {
    openUrlModal('image');
}

function promptVideoUrl() {
    openUrlModal('video');
}

function previewSelectedImageFiles(input) {
    if (!input.files || !input.files.length) return;
    const grid = document.getElementById('imageGalleryGrid');
    
    Array.from(input.files).forEach(file => {
        const fileId = 'img_f_' + Math.random().toString(36).substring(2, 9) + '_' + Date.now();
        file._fileId = fileId;
        imageFilesDataTransfer.items.add(file);
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const card = document.createElement('div');
            card.className = 'image-item-card relative group rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-xs aspect-square';
            card.dataset.fileId = fileId;
            card.innerHTML = `
                <img src="${e.target.result}" class="w-full h-full object-cover">
                <!-- Top Right Delete Button (Always Visible) -->
                <button type="button" onclick="removeSelectedImageFile('${fileId}', this)" 
                        class="absolute top-2 right-2 z-10 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-md transition-all hover:scale-110 flex items-center justify-center" title="Remove image">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <!-- Center Hover Overlay -->
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2 pointer-events-none group-hover:pointer-events-auto">
                    <button type="button" onclick="removeSelectedImageFile('${fileId}', this)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-full shadow-md text-xs font-bold transition-transform hover:scale-105 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        Remove
                    </button>
                </div>
                <div class="absolute bottom-1 left-1 bg-emerald-600 text-white text-[9px] px-1.5 py-0.5 rounded font-bold truncate max-w-[85%]">
                    New File: ${file.name}
                </div>
            `;
            grid.appendChild(card);
            updateImageCount();
        };
        reader.readAsDataURL(file);
    });

    input.files = imageFilesDataTransfer.files;
}

function removeSelectedImageFile(fileId, btn) {
    const card = btn.closest('.image-item-card');
    if (card) {
        card.remove();
    }
    const newDT = new DataTransfer();
    for (let i = 0; i < imageFilesDataTransfer.files.length; i++) {
        const file = imageFilesDataTransfer.files[i];
        if (file._fileId !== fileId) {
            newDT.items.add(file);
        }
    }
    imageFilesDataTransfer = newDT;
    const input = document.getElementById('imageFileInput');
    if (input) {
        input.files = imageFilesDataTransfer.files;
    }
    updateImageCount();
}

// Video Management Logic
let videoFilesDataTransfer = new DataTransfer();

function updateVideoCount() {
    const count = document.querySelectorAll('.video-item-card').length;
    document.getElementById('videoCount').innerText = count;
    const emptyNotice = document.getElementById('videoEmptyNotice');
    if (emptyNotice) {
        emptyNotice.style.display = count === 0 ? 'block' : 'none';
    }
}

function addVideoCard(url, type = 'url') {
    if (!url) return;
    const grid = document.getElementById('videoGalleryGrid');
    const card = document.createElement('div');
    card.className = 'video-item-card relative rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-xs p-3 flex flex-col justify-between';
    
    let isEmbed = url.includes('youtube.com') || url.includes('youtu.be') || url.includes('vimeo.com');
    let previewHtml = '';
    
    if (isEmbed) {
        previewHtml = `<div class="aspect-video bg-gray-900 rounded-xl flex items-center justify-center text-white text-xs font-bold">
            <svg class="w-8 h-8 text-red-500 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
            Video Embed Link
        </div>`;
    } else {
        previewHtml = `<video src="${url}" class="w-full aspect-video rounded-xl bg-black object-cover" controls preload="metadata"></video>`;
    }

    card.innerHTML = `
        <div class="relative">
            ${previewHtml}
            <button type="button" onclick="this.closest('.video-item-card').remove(); updateVideoCount();" 
                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-md transition-all hover:scale-110 flex items-center justify-center z-10" title="Remove video">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
        <input type="hidden" name="video_urls[]" value="${url}">
        <div class="mt-2 flex items-center justify-between gap-2">
            <span class="text-[11px] font-mono text-gray-500 truncate" title="${url}">${url}</span>
            <button type="button" onclick="this.closest('.video-item-card').remove(); updateVideoCount();" class="text-red-500 hover:text-red-700 p-1 text-xs font-bold transition-colors">
                Remove
            </button>
        </div>
    `;
    grid.appendChild(card);
    updateVideoCount();
}

function openCloudinaryForImage() {
    if (typeof openMediaSelector === 'function') {
        openMediaSelector(url => {
            if (url) {
                addImageCard(url);
            }
        });
    } else {
        openUrlModal('image');
    }
}

function openCloudinaryForVideo() {
    if (typeof openMediaSelector === 'function') {
        openMediaSelector(url => {
            if (url) {
                addVideoCard(url);
            }
        });
    } else {
        openUrlModal('video');
    }
}

let activeUrlModalType = 'image';

function openUrlModal(type) {
    activeUrlModalType = type;
    const modal = document.getElementById('urlInputModal');
    const title = document.getElementById('urlModalTitle');
    const desc = document.getElementById('urlModalDesc');
    const input = document.getElementById('urlModalInput');
    
    if (type === 'image') {
        title.innerText = 'Add Image URL';
        desc.innerText = 'Enter Cloudinary or direct image link (e.g., https://.../image.webp)';
        input.placeholder = 'https://res.cloudinary.com/.../image.webp';
    } else {
        title.innerText = 'Add Video URL / Embed';
        desc.innerText = 'Enter YouTube, Vimeo, Cloudinary, or direct MP4 URL';
        input.placeholder = 'https://www.youtube.com/watch?v=... or https://.../video.mp4';
    }
    
    input.value = '';
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => input.focus(), 50);
}

function closeUrlModal() {
    const modal = document.getElementById('urlInputModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

function confirmUrlModal() {
    const input = document.getElementById('urlModalInput');
    const val = input.value.trim();
    if (val) {
        if (activeUrlModalType === 'image') {
            addImageCard(val);
        } else {
            addVideoCard(val);
        }
        closeUrlModal();
    }
}

function previewSelectedVideoFiles(input) {
    if (!input.files || !input.files.length) return;
    const grid = document.getElementById('videoGalleryGrid');

    Array.from(input.files).forEach(file => {
        const fileId = 'vid_f_' + Math.random().toString(36).substring(2, 9) + '_' + Date.now();
        file._fileId = fileId;
        videoFilesDataTransfer.items.add(file);

        const card = document.createElement('div');
        card.className = 'video-item-card relative rounded-2xl overflow-hidden border border-gray-200 bg-white shadow-xs p-3';
        card.dataset.fileId = fileId;
        card.innerHTML = `
            <div class="aspect-video bg-indigo-950 rounded-xl flex items-center justify-center text-white text-xs font-bold flex-col gap-1 p-2 relative">
                <button type="button" onclick="removeSelectedVideoFile('${fileId}', this)" 
                        class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white p-1.5 rounded-full shadow-md transition-all hover:scale-110 flex items-center justify-center z-10" title="Remove video">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
                <svg class="w-8 h-8 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                <span class="truncate max-w-full font-mono text-[11px]">${file.name}</span>
            </div>
            <div class="mt-2 flex items-center justify-between gap-2">
                <span class="text-[11px] font-bold text-indigo-600">
                    New Video: ${(file.size / (1024*1024)).toFixed(2)} MB
                </span>
                <button type="button" onclick="removeSelectedVideoFile('${fileId}', this)" class="text-red-500 hover:text-red-700 text-xs font-bold transition-colors">
                    Remove
                </button>
            </div>
        `;
        grid.appendChild(card);
        updateVideoCount();
    });

    input.files = videoFilesDataTransfer.files;
}

function removeSelectedVideoFile(fileId, btn) {
    const card = btn.closest('.video-item-card');
    if (card) {
        card.remove();
    }
    const newDT = new DataTransfer();
    for (let i = 0; i < videoFilesDataTransfer.files.length; i++) {
        const file = videoFilesDataTransfer.files[i];
        if (file._fileId !== fileId) {
            newDT.items.add(file);
        }
    }
    videoFilesDataTransfer = newDT;
    const input = document.getElementById('videoFileInput');
    if (input) {
        input.files = videoFilesDataTransfer.files;
    }
    updateVideoCount();
}
</script>
