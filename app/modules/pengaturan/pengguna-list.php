<?php ob_start(); ?>

<div class="space-y-4 pb-12">
    <!-- ALERT NOTIFICATIONS -->
    <?php if (isset($_GET['success'])): ?>
        <div class="p-3 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                <span>Data pengguna sistem berhasil disimpan!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'password_mismatch'): ?>
        <div class="p-3 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                <span>Konfirmasi password tidak cocok! Silakan ulangi kembali.</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] == 'cannot_delete'): ?>
        <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation text-amber-500"></i>
                <span>Akun Superadmin utama atau akun yang sedang aktif login tidak boleh dihapus!</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    <?php endif; ?>

    <!-- TOP NAVIGATION TABS -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-2 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between flex-wrap gap-2">
        <div class="flex items-center gap-1.5">
            <a href="?main=pengguna" class="px-3.5 py-2 rounded-lg text-xs font-bold bg-purple-50 dark:bg-purple-950/70 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800 flex items-center gap-2 no-underline shadow-sm">
                <i class="fa-solid fa-users text-xs"></i>
                <span>Daftar Akun Pengguna</span>
            </a>
            <a href="?main=role_akses" class="px-3.5 py-2 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center gap-2 no-underline transition-colors">
                <i class="fa-solid fa-user-shield text-xs text-slate-400"></i>
                <span>Matriks Hak Akses Role</span>
            </a>
        </div>
    </div>

    <!-- TOP HEADER -->
    <div class="bg-white dark:bg-slate-900 rounded-xl p-3 md:p-4 border border-slate-200/80 dark:border-slate-800 shadow-sm flex flex-wrap items-center justify-between gap-3 transition-colors">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-base border border-amber-200 dark:border-amber-800">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <h2 class="text-base font-bold text-slate-900 dark:text-white mb-0 leading-tight">Kelola Pengguna Sistem</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-0">Atur hak akses akun kasir dan administrator sistem POS</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="openModalAddUser()" type="button" class="px-3.5 py-2 rounded-lg text-xs font-bold bg-amber-500 hover:bg-amber-600 active:scale-95 text-slate-950 shadow-sm flex items-center gap-1.5 transition-all">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Tambah Pengguna</span>
            </button>
        </div>
    </div>

    <!-- TABLE PENGGUNA -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200/80 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300" id="table-pengguna">
                <thead class="bg-slate-50 dark:bg-slate-800/80 text-slate-500 dark:text-slate-400 uppercase font-bold text-[11px] border-b border-slate-200/80 dark:border-slate-800 tracking-wider">
                    <tr>
                        <th class="py-3 px-3 md:px-4 text-center w-16">No.</th>
                        <th class="py-3 px-3 md:px-4">Username Akun</th>
                        <th class="py-3 px-3 md:px-4 text-center w-36">Hak Akses / Role</th>
                        <th class="py-3 px-3 md:px-4 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    <?php 
                    $no = 1;
                    $currentUserId = $_SESSION['userId'] ?? 0;
                    foreach ($data as $row): 
                        $isSuperadmin = ($row['user_id'] == '1');
                        $isSelf = ($currentUserId == $row['user_id']);
                    ?>
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors user-row">
                            <td class="py-3 px-3 md:px-4 text-center font-mono font-bold text-slate-400">
                                <?= $no++ ?>.
                            </td>
                            <td class="py-3 px-3 md:px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-700 dark:text-slate-300">
                                        <?= strtoupper(substr($row['username'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 dark:text-white text-xs">
                                            <?= htmlspecialchars($row['username']) ?>
                                            <?php if ($isSelf): ?>
                                                <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-700 dark:bg-blue-950/80 dark:text-blue-300">(Anda)</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3 md:px-4 text-center">
                                <?php if ($row['level'] == 'admin'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-purple-100 text-purple-800 dark:bg-purple-950/80 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                                        Administrator
                                    </span>
                                <?php elseif ($row['level'] == 'owner'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-950/80 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                        <i class="fa-solid fa-crown text-[10px]"></i>
                                        Owner Toko
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                        <i class="fa-solid fa-cash-register text-[10px]"></i>
                                        Kasir POS
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-3 md:px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <!-- Edit Button -->
                                    <button type="button" 
                                        onclick="openModalEditUser('<?= $row['user_id'] ?>', '<?= htmlspecialchars(addslashes($row['username'])) ?>', '<?= $row['level'] ?>')"
                                        class="w-7 h-7 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900 border border-amber-200 dark:border-amber-800 flex items-center justify-center transition-colors text-xs" 
                                        title="Ubah Pengguna">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <!-- Delete Button (Disabled for superadmin or self) -->
                                    <?php if (!$isSuperadmin && !$isSelf): ?>
                                        <form action="?main=pengguna&action=postdelete" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun pengguna ini?');" class="inline-block m-0">
                                            <input type="hidden" value="postdelete" name="action">
                                            <input type="hidden" value="<?= $row['user_id'] ?>" name="user_id">
                                            <button type="submit" class="w-7 h-7 rounded-lg bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900 border border-rose-200 dark:border-rose-800 flex items-center justify-center transition-colors text-xs" title="Hapus Pengguna">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-300 dark:text-slate-600 flex items-center justify-center text-xs cursor-not-allowed" title="Akun utama tidak dapat dihapus">
                                            <i class="fa-solid fa-lock"></i>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PAGINATION CONTAINER -->
    <div id="penggunaPaginationContainer"></div>
</div>

<!-- MODAL TAMBAH USER -->
<div id="modalAddUser" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold border border-amber-200 dark:border-amber-800">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0">Tambah Pengguna Baru</h3>
            </div>
            <button type="button" onclick="closeModalAddUser()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="?main=pengguna&action=postadd" method="post" class="p-5 space-y-3.5">
            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Username <span class="text-rose-500">*</span></label>
                <input type="text" name="username" placeholder="Contoh: kasir1" autocomplete="off" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
                <span class="text-[10px] text-slate-400">Gunakan huruf kecil tanpa spasi</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Password <span class="text-rose-500">*</span></label>
                <input type="password" name="password" placeholder="Masukkan password" autocomplete="new-password" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                <input type="password" name="cpassword" placeholder="Ketik ulang password" autocomplete="new-password" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Role / Hak Akses</label>
                <select name="level" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                    <option value="kasir">Kasir (Transaksi & POS Kasir)</option>
                    <option value="owner">Owner (Akses Penuh Master Data & Laporan Keuangan)</option>
                    <option value="admin">Administrator (Hak Akses Penuh Termasuk Pengaturan Toko, Pengguna & Migrasi)</option>
                </select>
            </div>

            <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModalAddUser()" class="px-3.5 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" name="simpan" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT USER -->
<div id="modalEditUser" class="fixed inset-0 z-50 bg-slate-950/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full border border-slate-200 dark:border-slate-800 shadow-2xl overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        <div class="p-4 border-b border-slate-200/80 dark:border-slate-800 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm font-bold border border-amber-200 dark:border-amber-800">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-0">Ubah Data Pengguna</h3>
            </div>
            <button type="button" onclick="closeModalEditUser()" class="w-7 h-7 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="?main=pengguna&action=postedit" method="post" class="p-5 space-y-3.5">
            <input type="hidden" id="editUserId" name="user_id">

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Username <span class="text-rose-500">*</span></label>
                <input type="text" id="editUsername" name="username" placeholder="Username" autocomplete="off" required class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Password Baru (Opsional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
                <span class="text-[10px] text-slate-400">Kosongkan jika tidak ingin mengganti password</span>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="cpassword" placeholder="Ketik ulang password baru" autocomplete="new-password" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-1">Role / Hak Akses</label>
                <select id="editLevel" name="level" class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-xs font-semibold text-slate-900 dark:text-white focus:outline-none focus:border-amber-500">
                    <option value="kasir">Kasir (Transaksi & POS Kasir)</option>
                    <option value="owner">Owner (Akses Penuh Master Data & Laporan Keuangan)</option>
                    <option value="admin">Administrator (Hak Akses Penuh Termasuk Pengaturan Toko, Pengguna & Migrasi)</option>
                </select>
            </div>

            <div class="pt-3 border-t border-slate-200/80 dark:border-slate-800 flex items-center justify-end gap-2">
                <button type="button" onclick="closeModalEditUser()" class="px-3.5 py-2 rounded-lg text-xs font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    Batal
                </button>
                <button type="submit" name="simpan" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs shadow-sm flex items-center gap-1.5 transition-all">
                    <i class="fa-solid fa-floppy-disk"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModalAddUser() {
    const modal = document.getElementById('modalAddUser');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModalAddUser() {
    const modal = document.getElementById('modalAddUser');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openModalEditUser(userId, username, level) {
    document.getElementById('editUserId').value = userId;
    document.getElementById('editUsername').value = username;
    document.getElementById('editLevel').value = level;
    
    const modal = document.getElementById('modalEditUser');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModalEditUser() {
    const modal = document.getElementById('modalEditUser');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close on backdrop click
window.addEventListener('click', (e) => {
    const modalAdd = document.getElementById('modalAddUser');
    const modalEdit = document.getElementById('modalEditUser');
    if (e.target === modalAdd) closeModalAddUser();
    if (e.target === modalEdit) closeModalEditUser();
});

document.addEventListener('DOMContentLoaded', () => {
    window.penggunaPaginator = new TablePaginator({
        tableId: 'table-pengguna',
        rowSelector: '.user-row',
        containerId: 'penggunaPaginationContainer',
        defaultPerPage: 10,
        perPageOptions: [10, 25, 50, -1],
        itemLabel: 'pengguna'
    });
});
</script>

<?php $content = ob_get_clean(); ?>