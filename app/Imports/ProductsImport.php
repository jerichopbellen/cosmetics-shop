<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Shade;
use App\Models\ProductImage;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class ProductsImport implements OnEachRow, WithHeadingRow
{
    public function onRow(Row $row)
    {
        $data = $row->toArray();
        $placeholder = 'placeholders/product.png';

        $brandName    = $data['brand'] ?? 'Unknown';
        $categoryName = $data['category'] ?? 'Uncategorized';
        $productName  = $data['name'] ?? 'Unnamed Product';

        $brand = Brand::firstOrCreate(['name' => trim($brandName)]);
        $category = Category::firstOrCreate(['name' => trim($categoryName)]);

        $product = Product::firstOrCreate(
            [
                'brand_id'    => $brand->id,
                'category_id' => $category->id,
                'name'        => trim($productName),
            ],
            [
                'description' => $data['description'] ?? 'No description',
                'finish'      => $data['finish'] ?? 'Matte',
            ]
        );

        Shade::create([
            'product_id' => $product->id,
            'shade_name' => $data['shade_name'] ?? 'Standard',
            'hex_code'   => $data['hex_code'] ?? '#FFFFFF',
            'price'      => $data['price'] ?? 0,
            'stock'      => $data['stock'] ?? 0,
            'image_path' => $placeholder,
        ]);

        ProductImage::firstOrCreate([
            'product_id' => $product->id,
            'image_path' => $placeholder,
        ]);
    }
}