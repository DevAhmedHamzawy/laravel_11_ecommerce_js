<?php

namespace App\Services;

use App\Models\Stock;

class StockService
{
    public function group($stocks)
    {
        $groupedStocks = $stocks->groupBy(function ($stock) {
            $attributeIds = $stock->attributes->pluck('attribute_value_id')->sort()->join('-');
            return $stock->product_id . '-' . $attributeIds;
        });

        $uniqueStocks = $groupedStocks->map(function ($group) {
            $first = $group->first(); // ناخد أول واحد بس نعرضه كمثال

            return [
                'id' => $first->id,
                'product_name' => $first->product->name ?? 'N/A',
                'product_price' => $first->selling_price ?? $first->product->selling_price ?? 'N/A',
                'product_id' => $first->product_id,
                'attributes' => $first->attributes->map(function ($attr) {
                    return $attr->attribute->name . ': ' . $attr->attributeValue->name;
                })->join(', '),
                'qty' => $group->sum('qty'), // هنا بنحسب إجمالي الكمية
                'created_at' => $group->first()->created_at
            ];
        })->values();

        return $uniqueStocks;

    }

    public function getSingle($selectedStock)
    {
        // لو مش موجود
        if (!$selectedStock) {
            abort(404, 'Stock not found');
        }

        // نحضر البيانات الأساسية
        $product_id = $selectedStock->product_id;

        // نجيب المواصفات بتاعة الستوك كـ array مرتبة
        $attributeValueIds = $selectedStock->attributes->pluck('attribute_value_id')->sort()->values()->toArray();

        // دلوقتي نجيب كل التسجيلات اللي نفس المنتج ونفس المواصفات
        $matchingStocks = Stock::with('attributes', 'product')
            ->where('product_id', $product_id)
            ->get()
            ->filter(function ($stock) use ($attributeValueIds) {
                $currentAttrs = $stock->attributes->pluck('attribute_value_id')->sort()->values()->toArray();
                return $currentAttrs === $attributeValueIds;
            });

        return $matchingStocks;
    }
}
