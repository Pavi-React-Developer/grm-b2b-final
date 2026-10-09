// GRM B2B Main JS
document.addEventListener('DOMContentLoaded', () => {
    // Add logic for dynamic interactions here
    // e.g., Modal toggling, Form validation, Image previews
});

// GLOBAL CLIENT-SIDE IMAGE COMPRESSION (10/10 Upload Speed Optimization)
// Intercepts ANY file input selection. Only processes actual image files.
document.addEventListener('change', async function(e) {
    if (e.target && e.target.matches('input[type="file"]')) {
        const input = e.target;
        if (!input.files || input.files.length === 0) return;

        // Skip if browser-image-compression is not loaded yet
        if (typeof imageCompression === 'undefined') {
            console.warn('browser-image-compression not loaded yet');
            return;
        }

        // Show loading state on the input's label or parent element if possible
        const originalParentStyle = input.parentElement ? input.parentElement.style.opacity : null;
        if (input.parentElement) {
            input.parentElement.style.opacity = '0.5';
            input.parentElement.style.pointerEvents = 'none';
        }

        try {
            const dataTransfer = new DataTransfer();
            const options = {
                maxSizeMB: 0.5, // 500KB max per image
                maxWidthOrHeight: 1920,
                useWebWorker: true
            };

            for (let i = 0; i < input.files.length; i++) {
                let file = input.files[i];
                // Only compress images (skip PDFs or other files if accepted by mistake)
                if (file.type.startsWith('image/')) {
                    console.log(`Compressing ${file.name}: Original size ${Math.round(file.size / 1024)} KB`);
                    
                    try {
                        let compressedFile = await imageCompression(file, options);
                        console.log(`Compressed ${file.name}: New size ${Math.round(compressedFile.size / 1024)} KB`);
                        
                        // Overwrite file name to retain original name
                        let finalFile = new File([compressedFile], file.name, {
                            type: compressedFile.type,
                            lastModified: Date.now()
                        });
                        dataTransfer.items.add(finalFile);
                    } catch (error) {
                        console.error('Compression error:', error);
                        // Fallback to original file if compression fails
                        dataTransfer.items.add(file);
                    }
                } else {
                    dataTransfer.items.add(file);
                }
            }

            // Replace the input's files with the compressed files
            input.files = dataTransfer.files;
            console.log('Successfully swapped input files with compressed versions.');

        } catch (error) {
            console.error('Global compression failed:', error);
        } finally {
            // Restore visual state
            if (input.parentElement) {
                input.parentElement.style.opacity = originalParentStyle || '1';
                input.parentElement.style.pointerEvents = 'auto';
            }
        }
    }
});

window.toggleWishlist = async function(e, productId, btnElement) {
    if (e) e.preventDefault();
    try {
        const response = await fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ product_id: productId })
        });
        
        const data = await response.json();
        
        if (!data.success && data.message && data.message.includes('login')) {
            window.location.href = '/login';
            return;
        }
        
        if (data.success) {
            // Update icon visually
            const svg = btnElement ? btnElement.querySelector('svg') : null;
            if (svg) {
                if (data.action === 'added') {
                    svg.setAttribute('fill', 'currentColor');
                    svg.classList.add('text-red-500');
                    svg.classList.remove('text-white');
                } else {
                    svg.setAttribute('fill', 'none');
                    svg.classList.remove('text-red-500');
                    svg.classList.add('text-white');
                }
            }
            
            // Refresh wishlist count
            if (typeof updateWishlistBadge === 'function') {
                updateWishlistBadge();
            } else {
                const badge = document.getElementById('wishlist-badge');
                if (badge) {
                    let current = parseInt(badge.textContent || '0');
                    if (data.action === 'added') current++;
                    else current = Math.max(0, current - 1);
                    badge.textContent = current;
                    if (current > 0) badge.classList.remove('hidden');
                    else badge.classList.add('hidden');
                }
            }
        }
    } catch (error) {
        console.error('Error toggling wishlist:', error);
    }
};