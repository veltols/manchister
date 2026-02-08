/**
 * AttachmentPreview - Real-time file preview for file inputs with Premium Modal support
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
        this.currentFile = null;
        this.currentBlobUrl = null;

        if (!this.input || !this.container) {
            console.warn('AttachmentPreview: Input or Container not found', options);
            return;
        }

        this.init();
        this.ensureModalStructure();
    }

    init() {
        this.input.addEventListener('change', (e) => this.handleFileSelect(e));
    }

    ensureModalStructure() {
        if (document.getElementById('premium-preview-modal')) return;

        const modalHtml = `
            <div id="premium-preview-modal" class="fixed inset-0 z-[9999] hidden items-center justify-center p-4 md:p-8">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm animate-fade-in modal-overlay"></div>
                <div class="relative w-full max-w-6xl h-full max-h-[90vh] bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col animate-scale-in">
                    <!-- Modal Header -->
                    <div class="px-8 py-4 border-b border-slate-100 flex items-center justify-between bg-white/80 backdrop-blur-md sticky top-0 z-10">
                        <div class="flex items-center gap-4">
                            <div id="modal-file-icon" class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <i class="fa-solid fa-file"></i>
                            </div>
                            <div>
                                <h3 id="modal-file-name" class="font-display font-bold text-slate-800 text-lg truncate max-w-[200px] md:max-w-md">File Preview</h3>
                                <p id="modal-file-info" class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">File Details</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                             <a id="modal-download-btn" href="#" download class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 hover:bg-brand hover:text-white transition-all flex items-center justify-center" title="Download Source">
                                <i class="fa-solid fa-download"></i>
                            </a>
                            <button id="close-premium-modal" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all flex items-center justify-center">
                                <i class="fa-solid fa-times text-lg"></i>
                            </button>
                        </div>
                    </div>
                    <!-- Modal Body -->
                    <div id="modal-preview-content" class="flex-1 overflow-y-auto bg-slate-50 p-4 md:p-8 flex items-center justify-center">
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                             <i class="fa-solid fa-spinner fa-spin text-4xl"></i>
                        </div>
                    </div>
                </div>
            </div>
            <style>
                .animate-scale-in { animation: scaleIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
                @keyframes scaleIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                #premium-preview-modal.active { display: flex; }
            </style>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const modal = document.getElementById('premium-preview-modal');
        modal.querySelector('.modal-overlay').addEventListener('click', () => this.closeModal());
        modal.querySelector('#close-premium-modal').addEventListener('click', () => this.closeModal());
    }

    handleFileSelect(e) {
        const file = e.target.files[0];
        if (!file) {
            this.clearPreview();
            return;
        }

        this.currentFile = file;
        const fileName = file.name;
        const fileSize = this.formatBytes(file.size);
        const extension = fileName.split('.').pop().toLowerCase();

        this.container.innerHTML = `
            <div class="mt-4 p-4 rounded-2xl bg-white border border-slate-100 shadow-sm animate-fade-in group hover:border-brand/30 transition-all">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div id="preview-thumbnail" class="w-12 h-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center overflow-hidden border border-slate-50 group-hover:bg-brand/5 group-hover:text-brand transition-colors">
                            <i class="fa-solid ${this.getIconForExtension(extension)} text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-700 truncate max-w-[200px]" title="${fileName}">${fileName}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider">${fileSize} • ${extension}</p>
                                <button type="button" id="open-full-preview" class="text-[10px] text-indigo-600 font-bold hover:underline">Quick Look</button>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="remove-preview-btn" class="w-8 h-8 rounded-lg hover:bg-red-50 hover:text-red-500 flex items-center justify-center text-slate-300 transition-all">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            </div>
        `;

        // Update inline thumbnail if possible
        const fileType = file.type;
        if (fileType.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (ev) => {
                const thumb = this.container.querySelector('#preview-thumbnail');
                thumb.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
            };
            reader.readAsDataURL(file);
        }

        this.container.querySelector('#remove-preview-btn').addEventListener('click', () => this.clearPreview());
        this.container.querySelector('#open-full-preview').addEventListener('click', () => this.openModal());
    }

    openModal() {
        if (!this.currentFile) return;

        const modal = document.getElementById('premium-preview-modal');
        const content = modal.querySelector('#modal-preview-content');
        const iconContainer = modal.querySelector('#modal-file-icon');
        const nameLabel = modal.querySelector('#modal-file-name');
        const infoLabel = modal.querySelector('#modal-file-info');
        const downloadBtn = modal.querySelector('#modal-download-btn');

        const file = this.currentFile;
        const extension = file.name.split('.').pop().toLowerCase();
        const fileType = file.type;

        // Reset state
        content.innerHTML = '<div class="flex flex-col items-center gap-4 text-slate-400"><i class="fa-solid fa-spinner fa-spin text-4xl text-brand"></i><p class="font-bold text-xs uppercase tracking-widest">Preparing Preview...</p></div>';
        iconContainer.innerHTML = `<i class="fa-solid ${this.getIconForExtension(extension)}"></i>`;
        nameLabel.innerText = file.name;
        infoLabel.innerText = `${this.formatBytes(file.size)} • ${fileType || 'binary'}`;

        // Prepare Download Link
        if (this.currentBlobUrl) URL.revokeObjectURL(this.currentBlobUrl);
        this.currentBlobUrl = URL.createObjectURL(file);
        downloadBtn.href = this.currentBlobUrl;
        downloadBtn.download = file.name;

        modal.classList.add('active');

        // Render Content
        if (fileType.startsWith('image/')) {
            const img = document.createElement('img');
            img.src = this.currentBlobUrl;
            img.className = 'max-w-full max-h-full rounded-2xl shadow-xl transition-all hover:scale-[1.01]';
            content.innerHTML = '';
            content.appendChild(img);
        } else if (fileType === 'application/pdf') {
            content.innerHTML = `<iframe src="${this.currentBlobUrl}#toolbar=1" class="w-full h-full rounded-[1.5rem] border-0 bg-white shadow-xl"></iframe>`;
        } else if (extension === 'docx') {
            const reader = new FileReader();
            reader.onload = (e) => {
                if (window.mammoth) {
                    window.mammoth.convertToHtml({ arrayBuffer: e.target.result })
                        .then(result => {
                            content.innerHTML = `
                                <div class="w-full max-w-4xl bg-white p-12 md:p-16 rounded-[2rem] shadow-xl overflow-auto h-full prose prose-sm md:prose-base max-w-none prose-slate">
                                    ${result.value || '<p class="text-slate-400 italic">No content found in document</p>'}
                                </div>`;
                        })
                        .catch(err => {
                            content.innerHTML = `<div class="text-center p-8"><i class="fa-solid fa-circle-exclamation text-red-500 text-4xl mb-4"></i><p class="font-bold text-slate-700">Display Error</p><p class="text-sm text-slate-400 mt-1">Failed to render DOCX content</p></div>`;
                        });
                } else {
                    content.innerHTML = `<div class="text-center p-8 text-slate-400"><i class="fa-solid fa-cloud-arrow-down text-4xl mb-4"></i><p>Word Viewer (Mammoth.js) is missing.</p></div>`;
                }
            };
            reader.readAsArrayBuffer(file);
        } else if (fileType.startsWith('text/') || ['sql', 'php', 'js', 'json', 'css'].includes(extension)) {
            const reader = new FileReader();
            reader.onload = (e) => {
                content.innerHTML = `
                    <div class="w-full max-w-5xl h-full bg-slate-900 rounded-[2rem] p-8 md:p-12 overflow-auto shadow-2xl relative group">
                        <div class="absolute top-4 right-4 flex gap-2">
                             <span class="text-[9px] font-bold text-slate-600 uppercase tracking-widest bg-slate-800 px-3 py-1 rounded-full border border-slate-700">Syntax View</span>
                        </div>
                        <pre class="text-slate-300 font-mono text-xs md:text-sm leading-relaxed whitespace-pre-wrap"><code>${this.escapeHTML(e.target.result)}</code></pre>
                    </div>`;
            };
            reader.readAsText(file);
        } else {
            content.innerHTML = `
                <div class="text-center p-12 bg-white rounded-[2.5rem] shadow-xl border border-slate-100 max-w-sm">
                    <div class="w-20 h-20 rounded-3xl bg-indigo-50 text-indigo-500 flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid ${this.getIconForExtension(extension)} text-4xl"></i>
                    </div>
                    <h3 class="font-display font-bold text-slate-800 text-xl">Preview Unavailable</h3>
                    <p class="text-sm text-slate-400 mt-2 leading-relaxed">Browsers cannot render <b>.${extension}</b> files natively. The file is secure and ready for upload.</p>
                </div>`;
        }
    }

    closeModal() {
        const modal = document.getElementById('premium-preview-modal');
        if (modal) modal.classList.remove('active');
    }

    clearPreview() {
        if (this.currentBlobUrl) {
            URL.revokeObjectURL(this.currentBlobUrl);
            this.currentBlobUrl = null;
        }
        this.currentFile = null;
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

    escapeHTML(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }
}

// Global initialization helper
window.initAttachmentPreview = function (options) {
    return new AttachmentPreview(options);
};
