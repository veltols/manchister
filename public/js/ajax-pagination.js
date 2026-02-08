/**
 * Generic AJAX Pagination Component
 * Beautiful, modern pagination with smooth transitions
 */

class AjaxPagination {
    constructor(config) {
        this.endpoint = config.endpoint;
        this.containerSelector = config.containerSelector;
        this.paginationSelector = config.paginationSelector;
        this.renderCallback = config.renderCallback;
        this.perPage = config.perPage || 15;
        this.currentPage = 1;
        this.isLoading = false;
        
        this.init();
    }
    
    init() {
        // Load initial page if URL has page parameter
        const urlParams = new URLSearchParams(window.location.search);
        const page = parseInt(urlParams.get('page')) || 1;
        if (page > 1) {
            this.loadPage(page);
        }
        
        // Handle browser back/forward
        window.addEventListener('popstate', (e) => {
            if (e.state && e.state.page) {
                this.loadPage(e.state.page, false);
            }
        });
    }
    
    async loadPage(page, updateHistory = true) {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.currentPage = page;
        
        // Show loading state
        this.showLoading();
        
        try {
            const response = await fetch(`${this.endpoint}?page=${page}&per_page=${this.perPage}`);
            const result = await response.json();
            
            if (result.success) {
                // Update content
                this.renderCallback(result.data);
                
                // Update pagination
                this.renderPagination(result.pagination);
                
                // Update URL
                if (updateHistory) {
                    const url = new URL(window.location);
                    url.searchParams.set('page', page);
                    window.history.pushState({ page }, '', url);
                }
                
                // Scroll to top smoothly
                document.querySelector(this.containerSelector).scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'start' 
                });
            } else {
                this.showError('Failed to load data');
            }
        } catch (error) {
            console.error('Pagination error:', error);
            this.showError('An error occurred while loading data');
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }
    
    renderPagination(pagination) {
        const container = document.querySelector(this.paginationSelector);
        if (!container) return;
        
        const { current_page, last_page, from, to, total } = pagination;
        
        if (last_page <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = `
            <div class="flex items-center justify-between px-6 py-4 bg-white border-t border-slate-100">
                <!-- Info -->
                <div class="text-sm text-slate-600">
                    Showing <span class="font-semibold text-slate-900">${from || 0}</span> to 
                    <span class="font-semibold text-slate-900">${to || 0}</span> of 
                    <span class="font-semibold text-slate-900">${total}</span> results
                </div>
                
                <!-- Pagination Controls -->
                <div class="flex items-center gap-2">
        `;
        
        // Previous button
        html += `
            <button onclick="window.ajaxPagination.loadPage(${current_page - 1})" 
                    ${current_page === 1 ? 'disabled' : ''}
                    class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-105 active:scale-95">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        `;
        
        // Page numbers
        const pages = this.getPageNumbers(current_page, last_page);
        pages.forEach(page => {
            if (page === '...') {
                html += `<span class="px-3 py-2 text-slate-400">...</span>`;
            } else {
                const isActive = page === current_page;
                html += `
                    <button onclick="window.ajaxPagination.loadPage(${page})"
                            class="min-w-[40px] px-4 py-2 rounded-lg font-medium transition-all hover:scale-105 active:scale-95 ${
                                isActive 
                                    ? 'bg-gradient-to-r from-brand to-cyan-600 text-white shadow-lg shadow-brand/30' 
                                    : 'border border-slate-200 text-slate-600 hover:bg-slate-50'
                            }">
                        ${page}
                    </button>
                `;
            }
        });
        
        // Next button
        html += `
            <button onclick="window.ajaxPagination.loadPage(${current_page + 1})" 
                    ${current_page === last_page ? 'disabled' : ''}
                    class="px-4 py-2 rounded-lg border border-slate-200 text-slate-600 font-medium hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all hover:scale-105 active:scale-95">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        `;
        
        html += `
                </div>
            </div>
        `;
        
        container.innerHTML = html;
    }
    
    getPageNumbers(current, last) {
        const pages = [];
        const delta = 2; // Number of pages to show on each side of current
        
        for (let i = 1; i <= last; i++) {
            if (
                i === 1 || 
                i === last || 
                (i >= current - delta && i <= current + delta)
            ) {
                pages.push(i);
            } else if (pages[pages.length - 1] !== '...') {
                pages.push('...');
            }
        }
        
        return pages;
    }
    
    showLoading() {
        const container = document.querySelector(this.containerSelector);
        if (container) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        }
        
        const pagination = document.querySelector(this.paginationSelector);
        if (pagination) {
            pagination.style.opacity = '0.5';
            pagination.style.pointerEvents = 'none';
        }
    }
    
    hideLoading() {
        const container = document.querySelector(this.containerSelector);
        if (container) {
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';
        }
        
        const pagination = document.querySelector(this.paginationSelector);
        if (pagination) {
            pagination.style.opacity = '1';
            pagination.style.pointerEvents = 'auto';
        }
    }
    
    showError(message) {
        const container = document.querySelector(this.containerSelector);
        if (container) {
            const errorHtml = `
                <div class="p-8 text-center">
                    <i class="fa-solid fa-exclamation-triangle text-4xl text-red-500 mb-4"></i>
                    <p class="text-slate-600">${message}</p>
                    <button onclick="location.reload()" class="mt-4 px-6 py-2 bg-brand text-white rounded-lg hover:brightness-110 transition-all">
                        Reload Page
                    </button>
                </div>
            `;
            container.innerHTML = errorHtml;
        }
    }
}

// Make it globally accessible
window.AjaxPagination = AjaxPagination;
