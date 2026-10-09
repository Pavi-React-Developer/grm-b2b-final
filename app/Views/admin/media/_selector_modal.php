<!-- Media Selector Modal -->
<div id="media-selector-modal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden flex items-center justify-center p-4" style="z-index: 9999;">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden flex flex-col max-h-[90vh]">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 shrink-0">
            <div class="flex items-center space-x-4">
                <h3 class="font-semibold text-gray-900">Select Media</h3>
                <label id="media-modal-upload-lbl" class="cursor-pointer inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-brand-600 hover:bg-brand-700 transition-colors">
                    <svg class="-ml-0.5 mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    <span id="media-modal-upload-text">Upload New</span>
                    <input type="file" id="media-modal-upload" class="hidden" accept="image/*" onchange="handleModalUpload(this)">
                </label>
            </div>
            <button type="button" onclick="closeMediaSelector()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-6 overflow-y-auto grow">
            <div id="media-loading" class="text-center py-8 text-gray-500">
                <svg class="animate-spin h-8 w-8 mx-auto mb-4 text-brand-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Loading media...
            </div>
            <div id="media-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 hidden">
                <!-- Media items injected here via JS -->
            </div>
            <div id="media-empty" class="text-center py-8 text-gray-500 hidden">
                No media found. Please upload images in the Media Manager.
            </div>
        </div>
    </div>
</div>

<script>
let mediaSelectorCallback = null;
let mediaLoaded = false;

function openMediaSelector(callback) {
    mediaSelectorCallback = callback;
    document.getElementById('media-selector-modal').classList.remove('hidden');
    
    if (!mediaLoaded) {
        loadMedia();
    }
}

function closeMediaSelector() {
    document.getElementById('media-selector-modal').classList.add('hidden');
    mediaSelectorCallback = null;
}

function loadMedia() {
    document.getElementById('media-loading').classList.remove('hidden');
    document.getElementById('media-grid').classList.add('hidden');
    document.getElementById('media-empty').classList.add('hidden');
    
    fetch('<?= BASE_URL ?>/admin/media/ajax-get')
        .then(response => response.json())
        .then(data => {
            document.getElementById('media-loading').classList.add('hidden');
            if (data.success && data.data.length > 0) {
                const grid = document.getElementById('media-grid');
                grid.innerHTML = '';
                data.data.forEach(media => {
                    const item = document.createElement('div');
                    item.className = 'border border-gray-200 rounded-lg overflow-hidden bg-gray-50 cursor-pointer hover:border-brand-500 transition-colors group relative';
                    const rawUrl = media.cloudinary_url || '';
                    const fullUrl = (rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) ? rawUrl : ('<?= BASE_URL ?>/' + rawUrl.replace(/^\/+/, ''));
                    item.onclick = () => selectMedia(rawUrl, fullUrl);
                    item.innerHTML = `
                        <img src="${fullUrl}" alt="${media.original_filename}" class="w-full h-32 object-cover bg-white" onerror="this.onerror=null; this.src='data:image/svg+xml;utf8,<svg xmlns=\\\'http://www.w3.org/2000/svg\\\' width=\\\'100\\\' height=\\\'100\\\' fill=\\\'%23cbd5e1\\\' viewBox=\\\'0 0 24 24\\\'><path d=\\\'M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z\\\'/></svg>';">
                        <div class="p-2 truncate text-xs text-gray-600">${media.original_filename}</div>
                        <div class="absolute inset-0 bg-brand-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    `;
                    grid.appendChild(item);
                });
                grid.classList.remove('hidden');
                mediaLoaded = true;
            } else {
                document.getElementById('media-empty').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error loading media:', error);
            document.getElementById('media-loading').classList.add('hidden');
            document.getElementById('media-empty').innerText = 'Error loading media.';
            document.getElementById('media-empty').classList.remove('hidden');
        });
}

function selectMedia(rawUrl, fullUrl) {
    if (mediaSelectorCallback) {
        const finalFullUrl = fullUrl || ((rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) ? rawUrl : ('<?= BASE_URL ?>/' + rawUrl.replace(/^\/+/, '')));
        mediaSelectorCallback(rawUrl, finalFullUrl);
    }
    closeMediaSelector();
}

function handleModalUpload(input) {
    if (!input.files || input.files.length === 0) return;
    
    const file = input.files[0];
    const formData = new FormData();
    formData.append('image', file);
    
    const label = document.getElementById('media-modal-upload-lbl');
    const textSpan = document.getElementById('media-modal-upload-text');
    const originalText = textSpan.innerText;
    
    textSpan.innerText = 'Uploading...';
    label.classList.add('opacity-50', 'pointer-events-none');
    
    fetch('<?= BASE_URL ?>/admin/media/upload', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch (e) {
            console.error('Non-JSON response:', text);
            throw new Error('Server returned invalid response: ' + (text.substring(0, 100) || 'empty'));
        }
    })
    .then(data => {
        if (data.success) {
            const rawUrl = data.url || '';
            const fullUrl = (rawUrl.startsWith('http://') || rawUrl.startsWith('https://')) ? rawUrl : ('<?= BASE_URL ?>/' + rawUrl.replace(/^\/+/, ''));
            selectMedia(rawUrl, fullUrl);
            mediaLoaded = false; // Reload grid next time modal opens
        } else {
            alert('Upload failed: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error('Upload Error:', err);
        alert('Upload failed: ' + (err.message || 'Network error'));
    })
    .finally(() => {
        textSpan.innerText = originalText;
        label.classList.remove('opacity-50', 'pointer-events-none');
        input.value = ''; // clear input so same file can be uploaded again if needed
    });
}
</script>
