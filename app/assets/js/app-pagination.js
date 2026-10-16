/**
 * app-pagination.js
 * Universal Client-Side Table Pagination Component for Bakul Sepatu Solo POS
 * 
 * Fitur:
 * - Paging instan tanpa reload halaman
 * - Mendukung filter teks / pencarian & dropdown kategori secara otomatis
 * - Dropdown pilihan jumlah entri per halaman (10, 25, 50, 100, Semua)
 * - Tampilan ringkasan: "Menampilkan 1 - 25 dari 100 data"
 * - Tombol navigasi: Pertama, Sebelumnya, Angka Halaman (dengan ellipsis jika banyak), Selanjutnya, Terakhir
 * - Integrasi mulus dengan checkbox (misal restok masal) & edit inline
 * - Desain responsif, modern, dan mendukung Dark Mode
 */

class TablePaginator {
    constructor(options) {
        this.tableId = options.tableId;
        this.rowSelector = options.rowSelector;
        this.containerId = options.containerId; // Tempat merender bar pagination
        this.filterInputId = options.filterInputId || null;
        this.categorySelectId = options.categorySelectId || null;
        this.customFilterFn = options.customFilterFn || null;
        this.defaultPerPage = options.defaultPerPage || 25;
        this.perPageOptions = options.perPageOptions || [10, 25, 50, 100, -1]; // -1 = Semua
        this.itemLabel = options.itemLabel || 'data';
        this.onPageRendered = options.onPageRendered || null;

        this.currentPage = 1;
        this.perPage = this.defaultPerPage;
        this.filteredRows = [];
        this.allRows = [];

        this.init();
    }

    init() {
        const table = document.getElementById(this.tableId);
        if (!table) return;

        this.allRows = Array.from(table.querySelectorAll(this.rowSelector));
        if (this.allRows.length === 0) return;

        // Container pagination di bawah tabel
        this.container = document.getElementById(this.containerId);
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = this.containerId;
            table.parentElement.insertAdjacentElement('afterend', this.container);
        }

        // Setup filter input listener jika ada
        if (this.filterInputId) {
            const input = document.getElementById(this.filterInputId);
            if (input) {
                input.addEventListener('input', () => {
                    this.currentPage = 1;
                    this.applyFilterAndRender();
                });
            }
        }

        // Setup category select listener jika ada
        if (this.categorySelectId) {
            const select = document.getElementById(this.categorySelectId);
            if (select) {
                select.addEventListener('change', () => {
                    this.currentPage = 1;
                    this.applyFilterAndRender();
                });
            }
        }

        this.applyFilterAndRender();
    }

    applyFilterAndRender() {
        const query = this.filterInputId ? (document.getElementById(this.filterInputId)?.value || '').toLowerCase().trim() : '';
        const selectedKat = this.categorySelectId ? (document.getElementById(this.categorySelectId)?.value || '').toLowerCase() : '';

        this.filteredRows = this.allRows.filter(row => {
            if (typeof this.customFilterFn === 'function') {
                return this.customFilterFn(row, query, selectedKat);
            }

            // Default filter search
            const searchTarget = (
                (row.getAttribute('data-search') || '') + ' ' +
                (row.getAttribute('data-name') || '') + ' ' +
                (row.getAttribute('data-id') || '') + ' ' +
                row.innerText
            ).toLowerCase();

            const rowKat = (row.getAttribute('data-kategori') || '').toLowerCase();

            const matchQuery = query === '' || searchTarget.includes(query);
            const matchKat = selectedKat === '' || rowKat === selectedKat;

            return matchQuery && matchKat;
        });

        const totalFiltered = this.filteredRows.length;
        const totalPages = this.perPage === -1 ? 1 : Math.ceil(totalFiltered / this.perPage) || 1;

        if (this.currentPage > totalPages) {
            this.currentPage = totalPages;
        }
        if (this.currentPage < 1) {
            this.currentPage = 1;
        }

        // Sembunyikan semua baris terlebih dahulu
        this.allRows.forEach(r => r.style.display = 'none');

        // Tampilkan hanya baris di halaman aktif
        const startIndex = this.perPage === -1 ? 0 : (this.currentPage - 1) * this.perPage;
        const endIndex = this.perPage === -1 ? totalFiltered : Math.min(startIndex + this.perPage, totalFiltered);

        for (let i = startIndex; i < endIndex; i++) {
            if (this.filteredRows[i]) {
                this.filteredRows[i].style.display = '';
            }
        }

        this.renderPaginationControls(totalFiltered, totalPages, startIndex, endIndex);

        if (typeof this.onPageRendered === 'function') {
            this.onPageRendered({
                currentPage: this.currentPage,
                totalPages: totalPages,
                totalFiltered: totalFiltered,
                startIndex: startIndex,
                endIndex: endIndex
            });
        }
    }

    renderPaginationControls(totalFiltered, totalPages, startIndex, endIndex) {
        if (!this.container) return;

        const startDisplay = totalFiltered === 0 ? 0 : startIndex + 1;
        const endDisplay = endIndex;

        let perPageOptionsHtml = '';
        this.perPageOptions.forEach(opt => {
            const label = opt === -1 ? 'Semua' : opt;
            const sel = (this.perPage === opt) ? 'selected' : '';
            perPageOptionsHtml += `<option value="${opt}" ${sel}>${label}</option>`;
        });

        // Generate nomor halaman dengan ellipsis
        let pageButtonsHtml = '';
        const maxButtons = 5;
        let startPage = Math.max(1, this.currentPage - 2);
        let endPage = Math.min(totalPages, startPage + maxButtons - 1);

        if (endPage - startPage < maxButtons - 1) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }

        // Tombol Pertama & Prev
        const prevDisabled = this.currentPage <= 1 ? 'disabled opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 active:scale-95';
        const nextDisabled = this.currentPage >= totalPages ? 'disabled opacity-40 cursor-not-allowed' : 'hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 active:scale-95';

        // Prev Button
        pageButtonsHtml += `
            <button type="button" class="pg-btn px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold flex items-center gap-1 transition-all ${prevDisabled}" data-page="${this.currentPage - 1}" ${this.currentPage <= 1 ? 'disabled' : ''} title="Halaman Sebelumnya">
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
            </button>
        `;

        if (startPage > 1) {
            pageButtonsHtml += `
                <button type="button" class="pg-btn w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold transition-all hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200" data-page="1">1</button>
            `;
            if (startPage > 2) {
                pageButtonsHtml += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            }
        }

        for (let p = startPage; p <= endPage; p++) {
            if (p === this.currentPage) {
                pageButtonsHtml += `
                    <button type="button" class="w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold text-xs shadow-sm flex items-center justify-center cursor-default">${p}</button>
                `;
            } else {
                pageButtonsHtml += `
                    <button type="button" class="pg-btn w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold transition-all hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 flex items-center justify-center" data-page="${p}">${p}</button>
                `;
            }
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                pageButtonsHtml += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            }
            pageButtonsHtml += `
                <button type="button" class="pg-btn w-8 h-8 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold transition-all hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200" data-page="${totalPages}">${totalPages}</button>
            `;
        }

        // Next Button
        pageButtonsHtml += `
            <button type="button" class="pg-btn px-2.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold flex items-center gap-1 transition-all ${nextDisabled}" data-page="${this.currentPage + 1}" ${this.currentPage >= totalPages ? 'disabled' : ''} title="Halaman Selanjutnya">
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
            </button>
        `;

        this.container.innerHTML = `
            <div class="px-4 py-3 bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-600 dark:text-slate-400 mt-3 transition-colors select-none">
                <!-- Info dan Pilihan Per Page -->
                <div class="flex items-center gap-3 flex-wrap justify-center sm:justify-start">
                    <div class="flex items-center gap-1.5">
                        <span class="text-slate-400 text-[11px]">Tampilkan:</span>
                        <select class="pg-per-page bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-semibold text-slate-800 dark:text-slate-200 focus:outline-none focus:border-emerald-500 cursor-pointer">
                            ${perPageOptionsHtml}
                        </select>
                        <span class="text-slate-400 text-[11px]">baris</span>
                    </div>
                    <span class="text-slate-300 dark:text-slate-700 hidden sm:inline">|</span>
                    <div class="text-[11px] font-medium text-slate-500 dark:text-slate-400">
                        Menampilkan <strong class="text-slate-800 dark:text-slate-200">${startDisplay} - ${endDisplay}</strong> dari <strong class="text-emerald-600 dark:text-emerald-400 font-bold">${totalFiltered}</strong> ${this.itemLabel}
                    </div>
                </div>

                <!-- Tombol Navigasi Halaman -->
                <div class="flex items-center gap-1 flex-wrap justify-center">
                    ${totalPages > 1 ? pageButtonsHtml : ''}
                </div>
            </div>
        `;

        // Event listener per page select
        const perPageSelect = this.container.querySelector('.pg-per-page');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', (e) => {
                this.perPage = parseInt(e.target.value, 10);
                this.currentPage = 1;
                this.applyFilterAndRender();
            });
        }

        // Event listener page buttons
        this.container.querySelectorAll('.pg-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const targetPage = parseInt(btn.getAttribute('data-page'), 10);
                if (targetPage && targetPage >= 1 && targetPage <= totalPages && targetPage !== this.currentPage) {
                    this.currentPage = targetPage;
                    this.applyFilterAndRender();
                    // Scroll smooth ke tabel
                    const tbl = document.getElementById(this.tableId);
                    if (tbl) {
                        tbl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }
                }
            });
        });
    }

    refresh() {
        const table = document.getElementById(this.tableId);
        if (table) {
            this.allRows = Array.from(table.querySelectorAll(this.rowSelector));
            this.applyFilterAndRender();
        }
    }
}

window.TablePaginator = TablePaginator;
