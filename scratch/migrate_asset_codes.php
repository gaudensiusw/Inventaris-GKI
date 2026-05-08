<?php

use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

$categories = Category::all();

DB::beginTransaction();

try {
    foreach ($categories as $category) {
        $items = Item::where('category_id', $category->id)->orderBy('id')->get();
        $sequence = 1;
        
        echo "Updating Category: {$category->name} (ID: {$category->id})\n";
        
        foreach ($items as $item) {
            // Format: CategoryID + 4-digit sequence (e.g. 10001)
            $newCode = $category->id . str_pad($sequence, 4, '0', STR_PAD_LEFT);
            
            $item->update([
                'kode_aset' => $newCode,
                'barcode' => $newCode
            ]);
            
            $sequence++;
        }
    }
    
    DB::commit();
    echo "Successfully updated all asset codes!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error: " . $e->getMessage() . "\n";
}
