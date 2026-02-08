/**
 * AttachmentPreview - Real-time file preview for file inputs
 */
class AttachmentPreview {
    /**
     * @param {Object} options 
     * @param {string} options.inputSelector - The selector for the file input
     * @param {string} options.containerSelector - The selector for the preview container
     * @param {Function} [options.onRemove] - Callback when preview is cleared
     */
    constructor(options) {
        this.input = document.querySelector(options.inputSelector);
        this.container = document.querySelector(options.containerSelector);
        this.onRemove = options.onRemove || null;

        if (!this.input || !this.container) {
            console.warn('AttachmentPreview: Input or Container not found', options);
            return;
        }

        this.init();
    }

    init() {
        this.input.addEventListener('change', (e) => this.handleFileSelect(e));
    }

    handleFileSelect(e) {
        const file = e.target.files[0];

        if (!file) {
            this.clearPreview();
            return;
        }

        const reader = new FileReader();
        const fileName = file.name;
        const fileSize = this.formatBytes(file.size);
        const fileType = file.type;
        const extension = fileName.split('.').pop().toLowerCase();

        this.container.innerHTML = `
            <div class="mt-4 p-4 rounded-2xl bg-slate-50 border border-slate-100 animate-fade-in">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div id="preview-thumbnail" class="w-12 h-12 rounded-xl bg-white border border-slate-100 flex items-center justify-center overflow-hidden text-slate-400">
                            <i class="fa-solid ${this.getIconForExtension(extension)} text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 truncate max-w-[200px]" title="${fileName}">${fileName}</p>
                            <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">${fileSize} • ${extension}</p>
                        </div>
                    </div>
                    <button type="button" id="remove-preview-btn" class="w-8 h-8 rounded-lg hover:bg-red-50 hover:text-red-500 flex items-center justify-center text-slate-400 transition-all">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            </div>
        `;

        if (fileType.startsWith('image/')) {
            reader.onload = (event) => {
                const thumbnail = this.container.querySelector('#preview-thumbnail');
                thumbnail.innerHTML = `<img src="${event.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
        } else if (fileType === 'application/pdf') {
            const blobUrl = URL.createObjectURL(file);
            const thumbnail = this.container.querySelector('#preview-thumbnail');
            thumbnail.classList.remove('w-12', 'h-12');
            thumbnail.classList.add('w-full', 'h-96', 'mt-4'); // Increased height for better reading
            thumbnail.innerHTML = `
                <iframe src="${blobUrl}#toolbar=0" class="w-full h-full rounded-xl border-0 bg-slate-100"></iframe>
            `;

            this.addFullViewLink(blobUrl);
            this.currentBlobUrl = blobUrl;
        } else if (extension === 'docx') {
            const reader = new FileReader();
            reader.onload = (event) => {
                const arrayBuffer = event.target.result;
                const thumbnail = this.container.querySelector('#preview-thumbnail');
                thumbnail.classList.remove('w-12', 'h-12');
                thumbnail.classList.add('w-full', 'h-96', 'mt-4', 'bg-white', 'p-8', 'overflow-auto', 'border', 'border-slate-200', 'prose', 'prose-sm', 'max-w-none');

                if (window.mammoth) {
                    window.mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                        .then((result) => {
                            thumbnail.innerHTML = result.value;
                        })
                        .catch((err) => {
                            console.error(err);
                            thumbnail.innerHTML = `<div class="flex flex-col items-center justify-center h-full text-slate-400"><i class="fa-solid fa-circle-exclamation text-2xl mb-2"></i><p>Error rendering DOCX</p></div>`;
                        });
                } else {
                    thumbnail.innerHTML = `
                        <div class="flex flex-col items-center justify-center h-full text-indigo-400 gap-2">
                            <i class="fa-solid fa-file-word text-4xl"></i>
                            <p class="text-[10px] font-bold uppercase">DOCX Viewer Loading...</p>
                            <p class="text-[9px] text-slate-400">If the preview doesn't appear, the file is still ready for upload.</p>
                        </div>
                    `;
                    console.warn('AttachmentPreview: mammoth.js not loaded. DOCX preview disabled.');
                }
            };
            reader.readAsArrayBuffer(file);
        } else if (fileType.startsWith('text/') || extension === 'sql' || extension === 'php' || extension === 'js' || extension === 'json') {
            reader.onload = (event) => {
                const thumbnail = this.container.querySelector('#preview-thumbnail');
                thumbnail.classList.remove('w-12', 'h-12');
                thumbnail.classList.add('w-full', 'h-64', 'mt-4', 'bg-slate-900', 'text-slate-300', 'p-4', 'overflow-auto', 'font-mono', 'text-xs', 'whitespace-pre-wrap');
                thumbnail.innerText = event.target.result;
            };
            reader.readAsText(file);
        } else {
            // For Office files and others, since browsers can't render them natively, 
            // we show a descriptive "Ready to Upload" card with a large icon.
            const thumbnail = this.container.querySelector('#preview-thumbnail');
            thumbnail.classList.remove('w-12', 'h-12');
            thumbnail.classList.add('w-full', 'h-32', 'mt-4', 'bg-indigo-50', 'text-indigo-400');
            thumbnail.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full gap-2">
                    <i class="fa-solid ${this.getIconForExtension(extension)} text-4xl"></i>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400">Preview not available for this type</p>
                </div>
            `;
        }

        this.container.querySelector('#remove-preview-btn').addEventListener('click', () => {
            this.clearPreview();
        });
    }

    addFullViewLink(url) {
        const infoDiv = this.container.querySelector('div > div:last-child');
        const viewBtn = document.createElement('a');
        viewBtn.href = url;
        viewBtn.target = '_blank';
        viewBtn.className = 'text-[10px] text-indigo-600 font-bold hover:underline mt-1 block';
        viewBtn.innerText = 'Open in Full View';
        infoDiv.appendChild(viewBtn);
    }

    clearPreview() {
        if (this.currentBlobUrl) {
            URL.revokeObjectURL(this.currentBlobUrl);
            this.currentBlobUrl = null;
        }
        this.input.value = '';
        this.container.innerHTML = '';
        if (this.onRemove) this.onRemove();
    }

    getIconForExtension(ext) {
        const icons = {
            'pdf': 'fa-file-pdf',
            'doc': 'fa-file-word',
            'docx': 'fa-file-word',
            'xls': 'fa-file-excel',
            'xlsx': 'fa-file-excel',
            'jpg': 'fa-file-image',
            'jpeg': 'fa-file-image',
            'png': 'fa-file-image',
            'gif': 'fa-file-image',
            'zip': 'fa-file-archive',
            'rar': 'fa-file-archive',
            'txt': 'fa-file-lines'
        };
        return icons[ext] || 'fa-file';
    }

    formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
}

// Global initialization helper
window.initAttachmentPreview = function (options) {
    return new AttachmentPreview(options);
};
