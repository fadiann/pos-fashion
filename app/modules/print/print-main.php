<?php
$transaksi_id_val = $transaksi['transaksi_id'] ?? ($transaksi_id ?? '');
$tanggal_val = $transaksi['tanggal'] ?? date('Y-m-d');
$kasir_val = !empty($_SESSION['username']) ? $_SESSION['username'] : 'Kasir';
$pelanggan_nama_val = $transaksi['pelanggan_nama'] ?? 'Umum';
$jenis_pembayaran_val = (int)($transaksi['jenis_pembayaran'] ?? 1);
$total_sebelum_diskon = (float)($transaksi['total_sebelum_diskon'] ?? 0);
$promo_nominal = (float)($transaksi['promo_nominal'] ?? 0);
$cash = (float)($transaksi['cash'] ?? 0);
$total_item = 0;
$subtotal_items = 0;

$items_receipt = [];
if (is_array($viewlist)) {
    foreach ($viewlist as $item) {
        $jml = (int)($item['jumlah'] ?? 0);
        $sub = (float)($item['subtotal'] ?? 0);
        $total_item += $jml;
        $subtotal_items += $sub;
        $items_receipt[] = [
            'nama'     => $item['nama_barang'] ?? '',
            'size'     => $item['size_nama'] ?? '',
            'jumlah'   => $jml,
            'harga'    => (float)($item['harga_barang'] ?? 0),
            'subtotal' => $sub
        ];
    }
}
if ($total_sebelum_diskon <= 0 && $subtotal_items > 0) {
    $total_sebelum_diskon = $subtotal_items;
}
$sisa_bayar = $total_sebelum_diskon - $promo_nominal;
if ($cash <= 0) {
    $cash = $sisa_bayar;
}
$kembalian = $cash - $sisa_bayar;

$tipe_bayar_str = ($jenis_pembayaran_val == 1) ? "TUNAI" : "NON TUNAI";
// Load Dynamic Store Configuration
$configFile = __DIR__ . '/../../config/store_config.json';
$storeConfig = [
    'nama_toko'            => 'bakulsepatusolo',
    'tagline'              => 'Pakaian, Celana, Aksesoris & Aneka Produk',
    'alamat'               => 'Jl. Ronggowarsito Bejen Karanganyar, Solo',
    'telepon'              => '0812-3456-7890',
    'footer_struk'         => "Barang yang sudah dibeli tidak dapat ditukar/dikembalikan.\nTerima kasih telah berbelanja!",
    'lebar_kertas_default' => '58mm'
];
if (file_exists($configFile)) {
    $loaded = json_decode(file_get_contents($configFile), true);
    if (is_array($loaded)) {
        $storeConfig = array_merge($storeConfig, $loaded);
    }
}
$isDefault80 = ($storeConfig['lebar_kertas_default'] === '80mm');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Struk Transaksi - <?= $transaksi_id_val ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
        }

        /* THERMAL RECEIPT CONTAINER */
        .thermal-receipt {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            width: 58mm;
            min-height: 100mm;
            background: #ffffff;
            margin: 20px auto;
            padding: 4mm 3mm;
            color: #000000;
            font-size: 11px;
            line-height: 1.35;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
        }

        .thermal-receipt.receipt-80mm {
            width: 80mm;
            font-size: 12px;
        }

        /* PRINT STYLES */
        @media print {
            body {
                background: none !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .thermal-receipt {
                width: 100% !important;
                max-width: 58mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                padding: 1mm 1mm !important;
            }
            .thermal-receipt.receipt-80mm {
                max-width: 80mm !important;
            }
            @page {
                size: 58mm auto;
                margin: 0;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    <!-- TOP ACTION TOOLBAR (TABLET FRIENDLY - TIDAK IKUT DICETAK) -->
    <header class="no-print bg-slate-900 text-white p-3 md:p-4 shadow-lg sticky top-0 z-50">
        <div class="max-w-xl mx-auto flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <a href="index.php?main=transaksi&success" class="p-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition-colors" title="Kembali ke Kasir">
                    <i class="fa-solid fa-arrow-left text-base"></i>
                </a>
                <div>
                    <h3 class="text-sm font-bold leading-tight mb-0">Cetak Struk Kasir</h3>
                    <span class="text-[11px] text-emerald-400 font-medium"><?= $transaksi_id_val ?></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <!-- Size Switcher -->
                <button type="button" id="btn-toggle-width" onclick="togglePaperWidth()" class="px-3 py-2 rounded-xl bg-slate-800 text-slate-300 hover:text-white text-xs font-bold transition-colors">
                    <i class="fa-solid fa-scroll text-xs"></i> <span id="label-width">58mm</span>
                </button>

                <!-- Web Bluetooth Print Button (Instant ESC/POS) -->
                <button type="button" onclick="printThermalBluetooth()" id="btn-bt-print" class="px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition-all shadow-md shadow-blue-600/30 flex items-center gap-1.5 active:scale-95">
                    <i class="fa-brands fa-bluetooth-b text-sm"></i> Cetak Bluetooth
                </button>

                <!-- Browser Print Dialog Button -->
                <button type="button" onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-md shadow-emerald-600/30 flex items-center gap-1.5 active:scale-95">
                    <i class="fa-solid fa-print text-sm"></i> Print Standar
                </button>
            </div>
        </div>
    </header>

    <!-- STRUK THERMAL PREVIEW -->
    <main class="flex-1 flex items-center justify-center p-2">
        <div id="receipt-paper" class="thermal-receipt <?= $isDefault80 ? 'receipt-80mm' : '' ?>">
            
            <!-- HEADER TOKO -->
            <div class="text-center pb-2 border-b border-dashed border-black">
                <h2 class="text-sm font-extrabold tracking-wider uppercase mb-0.5"><?= htmlspecialchars(strtoupper($storeConfig['nama_toko'])) ?></h2>
                <?php if (!empty($storeConfig['tagline'])): ?>
                    <p class="text-[9.5px] leading-tight mb-0.5 text-slate-700"><?= htmlspecialchars($storeConfig['tagline']) ?></p>
                <?php endif; ?>
                <p class="text-[10px] leading-tight mb-0.5"><?= htmlspecialchars($storeConfig['alamat']) ?></p>
                <p class="text-[10px] leading-tight mb-0">Telp/WA: <?= htmlspecialchars($storeConfig['telepon']) ?></p>
            </div>

            <!-- INFO TRANSAKSI -->
            <div class="py-2 border-b border-dashed border-black text-[10px] space-y-0.5">
                <div class="flex justify-between">
                    <span>No. Transaksi</span>
                    <span class="font-bold"><?= $transaksi_id_val ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Tanggal</span>
                    <span><?= date('d/m/Y H:i', strtotime($tanggal_val)) ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Kasir</span>
                    <span><?= ucfirst($kasir_val) ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Pelanggan</span>
                    <span><?= ucfirst($pelanggan_nama_val) ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Pembayaran</span>
                    <span class="font-bold"><?= $tipe_bayar_str ?></span>
                </div>
            </div>

            <!-- LIST ITEM PESANAN -->
            <div class="py-2 border-b border-dashed border-black space-y-1.5">
                <?php if (!empty($items_receipt)): ?>
                    <?php foreach ($items_receipt as $item): ?>
                        <div>
                            <div class="font-bold text-[11px] leading-tight">
                                <?= strtoupper(substr($item['nama'], 0, 20)) ?>
                                <?php if (!empty($item['size'])): ?>
                                    [<?= $item['size'] ?>]
                                <?php endif; ?>
                            </div>
                            <div class="flex justify-between text-[10px] pl-1 text-slate-700">
                                <span><?= $item['jumlah'] ?> &times; <?= number_format($item['harga'], 0, ',', '.') ?></span>
                                <span class="font-bold text-black"><?= number_format($item['subtotal'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- SUMMARY TOTAL & PEMBAYARAN -->
            <div class="py-2 border-b border-dashed border-black text-[11px] space-y-1">
                <div class="flex justify-between">
                    <span>Total (<?= $total_item ?> pcs)</span>
                    <span class="font-bold">Rp <?= number_format($total_sebelum_diskon, 0, ',', '.') ?></span>
                </div>
                <?php if ($promo_nominal > 0): ?>
                    <div class="flex justify-between text-rose-700">
                        <span>Diskon / Promo</span>
                        <span>-Rp <?= number_format($promo_nominal, 0, ',', '.') ?></span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between text-xs font-black pt-1 border-t border-dotted border-black">
                    <span>TOTAL TAGIHAN</span>
                    <span>Rp <?= number_format($sisa_bayar, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between pt-0.5">
                    <span>Tunai / Bayar</span>
                    <span>Rp <?= number_format($cash, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Kembalian</span>
                    <span class="font-bold">Rp <?= number_format($kembalian, 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- FOOTER UCAPAN -->
            <div class="text-center pt-3 text-[10px] space-y-0.5">
                <p class="font-bold uppercase tracking-wider mb-0.5">TERIMA KASIH</p>
                <p class="leading-tight text-slate-600 whitespace-pre-line"><?= htmlspecialchars($storeConfig['footer_struk']) ?></p>
            </div>

        </div>
    </main>

    <!-- FOOTER NAV UNTUK KASIR TABLET -->
    <footer class="no-print p-4 bg-white border-t border-slate-200 text-center">
        <a href="index.php?main=transaksi&success" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md transition-all active:scale-95">
            <i class="fa-solid fa-circle-check"></i> Selesai & Kembali ke Kasir
        </a>
    </footer>

    <!-- JAVASCRIPT: WEB BLUETOOTH THERMAL PRINTER (ESC/POS DIRECT PRINT) -->
    <script>
        // Data Struk untuk ESC/POS
        const receiptData = {
            store: <?= json_encode(strtoupper($storeConfig['nama_toko'])) ?>,
            tagline: <?= json_encode($storeConfig['tagline']) ?>,
            address: <?= json_encode($storeConfig['alamat']) ?>,
            phone: <?= json_encode($storeConfig['telepon']) ?>,
            footer: <?= json_encode($storeConfig['footer_struk']) ?>,
            trxId: "<?= $transaksi_id_val ?>",
            date: "<?= date('d/m/Y H:i', strtotime($tanggal_val)) ?>",
            kasir: "<?= ucfirst($kasir_val) ?>",
            customer: "<?= ucfirst($pelanggan_nama_val) ?>",
            paymentType: "<?= $tipe_bayar_str ?>",
            items: <?= json_encode($items_receipt) ?>,
            totalItem: <?= (int)$total_item ?>,
            subtotal: <?= (float)$total_sebelum_diskon ?>,
            discount: <?= (float)$promo_nominal ?>,
            finalTotal: <?= (float)$sisa_bayar ?>,
            cash: <?= (float)$cash ?>,
            change: <?= (float)$kembalian ?>
        };

        // Switch Width Paper 58mm / 80mm
        function togglePaperWidth() {
            const paper = document.getElementById('receipt-paper');
            const label = document.getElementById('label-width');
            if (paper.classList.contains('receipt-80mm')) {
                paper.classList.remove('receipt-80mm');
                label.innerText = '58mm';
            } else {
                paper.classList.add('receipt-80mm');
                label.innerText = '80mm';
            }
        }

        // Generate ESC/POS Byte Buffer
        function buildEscPosData(data, is80mm = false) {
            const lineWidth = is80mm ? 48 : 32;
            const encoder = new TextEncoder();
            const commands = [];

            function appendBytes(...bytes) {
                commands.push(new Uint8Array(bytes));
            }
            function appendText(str) {
                commands.push(encoder.encode(str));
            }
            function printLine(left, right) {
                let space = lineWidth - left.length - right.length;
                if (space < 1) space = 1;
                return left + ' '.repeat(space) + right + '\n';
            }
            function separator() {
                return '-'.repeat(lineWidth) + '\n';
            }

            // Init printer & clear
            appendBytes(0x1B, 0x40);

            // Center Align: Header Toko
            appendBytes(0x1B, 0x61, 0x01); // align center
            appendBytes(0x1B, 0x45, 0x01); // bold on
            appendText(data.store + "\n");
            appendBytes(0x1B, 0x45, 0x00); // bold off
            if (data.tagline) {
                appendText(data.tagline + "\n");
            }
            appendText(data.address + "\n");
            appendText("Telp: " + data.phone + "\n");
            appendText(separator());

            // Left Align: Transaksi Info
            appendBytes(0x1B, 0x61, 0x00); // align left
            appendText(printLine("No. Trx : " + data.trxId, ""));
            appendText(printLine("Tgl     : " + data.date, ""));
            appendText(printLine("Kasir   : " + data.kasir, ""));
            appendText(printLine("Cust    : " + data.customer, ""));
            appendText(printLine("Metode  : " + data.paymentType, ""));
            appendText(separator());

            // Item Pesanan
            data.items.forEach(it => {
                let nama = it.nama;
                if (it.size) nama += " [" + it.size + "]";
                appendText(nama + "\n");
                let qtyPrice = it.jumlah + " x " + Number(it.harga).toLocaleString('id-ID');
                let subtotal = Number(it.subtotal).toLocaleString('id-ID');
                appendText(printLine("  " + qtyPrice, subtotal));
            });
            appendText(separator());

            // Total & Pembayaran
            appendText(printLine("Total (" + data.totalItem + " pcs)", "Rp " + Number(data.subtotal).toLocaleString('id-ID')));
            if (data.discount > 0) {
                appendText(printLine("Diskon", "-Rp " + Number(data.discount).toLocaleString('id-ID')));
            }
            appendBytes(0x1B, 0x45, 0x01); // bold on
            appendText(printLine("TOTAL TAGIHAN", "Rp " + Number(data.finalTotal).toLocaleString('id-ID')));
            appendBytes(0x1B, 0x45, 0x00); // bold off
            appendText(printLine("Tunai / Bayar", "Rp " + Number(data.cash).toLocaleString('id-ID')));
            appendText(printLine("Kembalian", "Rp " + Number(data.change).toLocaleString('id-ID')));
            appendText(separator());

            // Footer
            appendBytes(0x1B, 0x61, 0x01); // center
            appendBytes(0x1B, 0x45, 0x01);
            appendText("TERIMA KASIH\n");
            appendBytes(0x1B, 0x45, 0x00);
            if (data.footer) {
                appendText(data.footer + "\n\n\n\n");
            } else {
                appendText("@bakulsepatusolo\n\n\n\n");
            }

            // Cut paper (GS V 66 0)
            appendBytes(0x1D, 0x56, 0x42, 0x00);

            // Merge Uint8Array chunks
            let totalLength = commands.reduce((acc, c) => acc + c.length, 0);
            let merged = new Uint8Array(totalLength);
            let offset = 0;
            for (let c of commands) {
                merged.set(c, offset);
                offset += c.length;
            }
            return merged;
        }

        // Web Bluetooth Print Function
        async function printThermalBluetooth() {
            if (!navigator.bluetooth) {
                alert("Browser ini belum mendukung Web Bluetooth API. Silakan gunakan Google Chrome di Tablet Android atau PC yang memiliki koneksi Bluetooth, atau gunakan tombol 'Print Standar'.");
                window.print();
                return;
            }

            const btn = document.getElementById('btn-bt-print');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menghubungkan...';
            btn.disabled = true;

            let device = null;
            try {
                // Daftar UUID service printer thermal BLE yang umum digunakan
                const thermalPrinterServices = [
                    '000018f0-0000-1000-8000-00805f9b34fb', // Standard Printer Service (0x18F0)
                    'e7810a71-73ae-499d-8c15-faa9aef0c3f2', // Pos-58 / Goojprt / MPT / PT-210
                    '49535343-fe7d-4ae5-8fa9-9fafd205e455', // ISSC transparent UART
                    '6e400001-b5a3-f393-e0a9-e50e24dcca9e', // Nordic UART Service
                    '0000ae00-0000-1000-8000-00805f9b34fb', // Custom thermal printer AE00
                    '0000ae30-0000-1000-8000-00805f9b34fb', // Custom thermal printer AE30
                    '0000ff00-0000-1000-8000-00805f9b34fb', // Custom thermal printer FF00
                    '0000fee7-0000-1000-8000-00805f9b34fb', // IoT thermal printer FEE7
                    '000018f1-0000-1000-8000-00805f9b34fb',
                    '0000fff0-0000-1000-8000-00805f9b34fb'  // FFF0 Custom Service
                ];

                // Request Bluetooth Device (Gunakan acceptAllDevices tanpa filters agar tidak terjadi error bentrok)
                device = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: thermalPrinterServices
                });

                if (!device.gatt) {
                    throw new Error("Perangkat tidak mendukung koneksi Bluetooth GATT.");
                }

                btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyambungkan Printer...';
                const server = await device.gatt.connect();

                let writeChar = null;

                // 1. Coba iterasi semua primary services yang diizinkan untuk mencari karakteristik writable
                try {
                    const services = await server.getPrimaryServices();
                    for (const s of services) {
                        try {
                            const characteristics = await s.getCharacteristics();
                            for (const c of characteristics) {
                                if (c.properties.write || c.properties.writeWithoutResponse) {
                                    writeChar = c;
                                    break;
                                }
                            }
                            if (writeChar) break;
                        } catch (charErr) {
                            // Abaikan dan coba service berikutnya
                        }
                    }
                } catch (servErr) {
                    console.warn("getPrimaryServices multi-scan dilewati:", servErr);
                }

                // 2. Jika belum ditemukan, lakukan pengecekan langsung ke UUID service printer umum satu per satu
                if (!writeChar) {
                    for (const uuid of thermalPrinterServices) {
                        try {
                            const s = await server.getPrimaryService(uuid);
                            const characteristics = await s.getCharacteristics();
                            for (const c of characteristics) {
                                if (c.properties.write || c.properties.writeWithoutResponse) {
                                    writeChar = c;
                                    break;
                                }
                            }
                            if (writeChar) break;
                        } catch (e) {
                            // Service ini tidak ada di printer tersebut, lanjutkan
                        }
                    }
                }

                if (!writeChar) {
                    throw new Error("Karakteristik cetak tidak ditemukan pada printer ini. Pastikan printer Bluetooth mendukung mode BLE (Bluetooth Low Energy).");
                }

                btn.innerHTML = '<i class="fa-solid fa-print fa-bounce"></i> Mencetak Struk...';

                // Generate bytes ESC/POS
                const is80 = document.getElementById('receipt-paper').classList.contains('receipt-80mm');
                const rawData = buildEscPosData(receiptData, is80);

                // Kirim byte data per chunk dengan jeda kecil agar buffer printer tidak overflow
                const chunkSize = 100;
                for (let i = 0; i < rawData.length; i += chunkSize) {
                    const chunk = rawData.slice(i, i + chunkSize);
                    if (writeChar.writeValueWithoutResponse) {
                        await writeChar.writeValueWithoutResponse(chunk);
                    } else {
                        await writeChar.writeValue(chunk);
                    }
                    // Jeda 20ms untuk mencegah buffer overrun pada printer BLE
                    await new Promise(r => setTimeout(r, 20));
                }

                alert("Struk berhasil dikirim ke Printer Thermal Bluetooth!");
            } catch (error) {
                console.error("Bluetooth print error:", error);
                // Abaikan jika kasir membatalkan dialog pemilihan Bluetooth
                if (error.name !== 'NotFoundError' && !error.message.includes('User cancelled')) {
                    alert("Koneksi Bluetooth gagal (" + error.message + "). Gunakan tombol 'Print Standar' sebagai alternatif.");
                }
            } finally {
                // Disconnect setelah selesai agar koneksi tidak menggantung
                setTimeout(() => {
                    try {
                        if (device && device.gatt && device.gatt.connected) {
                            device.gatt.disconnect();
                        }
                    } catch (e) {}
                }, 1000);

                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        }
    </script>
</body>
</html>