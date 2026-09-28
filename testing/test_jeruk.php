<?php
require __DIR__ . '/../src/vendor/autoload.php';
$app = require_once __DIR__ . '/../src/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

foreach (App\Models\Menu::with('bahans')->get() as $m) {
    if (stripos($m->nama_menu, 'Jeruk') !== false || stripos($m->nama_menu, 'Nutrisari') !== false || stripos($m->nama_menu, 'Teh') !== false) {
        echo $m->nama_menu . ' (ID: ' . $m->id_menu . ") -> Bahans: \n";
        foreach ($m->bahans as $b) {
            echo "   * " . $b->nama_bahan . " (ID: " . $b->id_bahan . ", Stok: " . $b->stok . ", Butuh: " . ($b->pivot->jumlah_dibutuhkan ?? 1) . ")\n";
        }
        if ($m->bahans->isEmpty()) {
            echo "   * [TIDAK ADA BAHAN TERHUBUNG!]\n";
        }
    }
}
