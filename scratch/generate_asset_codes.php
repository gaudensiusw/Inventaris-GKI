<?php

use App\Models\Item;

echo "Generating asset codes for items 1-74...\n";

for ($id = 1; $id <= 74; $id++) {
    $code = 'INV-' . str_pad($id, 3, '0', STR_PAD_LEFT);
    Item::where('id', $id)->update([
        'kode_aset' => $code,
        'barcode' => $code
    ]);
}

echo "Done.\n";
