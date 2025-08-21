<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\PurchaseAttribute;
use App\Upload\Upload;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function create($request)
    {
        DB::transaction(function () use ($request) {

            if($request->has('main_image')) $request->merge(['image' =>  Upload::uploadImage($request->main_image, 'purchases' , $request->name)]);

            if(!$request->has('_method')) {
                $purchase = Purchase::create($request->only('supplier_id', 'date', 'reference_number', 'image', 'status', 'subtotal', 'vat', 'total', 'notes'));
            } else {
                $purchase = Purchase::find($request->id);
                $purchase->update($request->only('supplier_id', 'date', 'reference_number', 'image', 'status', 'subtotal', 'vat', 'total', 'notes'));
                $purchase->items()->delete();
                $purchase->stocks()->delete();
            }


            for ($i=0; $i < count($request->product_ids) ; $i++) {

                if($request->has('_method'))
                    $productId = $request->product_ids[$i];

                $request->discount_sorts[$i] == 0 || $request->discount_sorts[$i] == 'percentage' ? $specialChar = "%"  :  $specialChar = "ج.م" ;

                $discount_amount = str_replace($specialChar , '' , $request->discount_amounts[$i]);

                $purchase_item = $purchase->items()->create([
                'product_id' => $request->product_ids[$i],
                'unit_cost' => $request->unit_prices[$i],
                'qty' => $request->qtys[$i],
                'discount_sort' => ($request->discount_sorts[$i] == '-1' ? null : $request->discount_sorts[$i] == 0 || $request->discount_sorts[$i] == 'percentage') ? 'percentage' : 'amount',
                'discount' => $discount_amount,
                'sub_total' => $request->prices_after_discount[$i],
                'vat' => $request->vats_to_pay[$i],
                'total' => $request->total_prices[$i]
                ]);

                if(!$request->has('_method')) {
                    $attributeValues = $request->attribute_ids[$i] ?? [];
                    $attributeParents = $request->attribute_parents[$i] ?? [];
                }else{
                    $attributeValues = $request->attribute_ids[$productId] ?? $request->attribute_ids[$i] ?? [];
                    $attributeParents = $request->attribute_parents[$productId] ?? $request->attribute_parents[$i] ?? [];
                }

                foreach ($attributeValues as $key => $attributeValueId) {
                    $purchase_item->attributes()->create([
                        'attribute_id' => $attributeParents[$key], // parent ID
                        'attribute_value_id' => $attributeValueId, // value ID
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                if($purchase->status == 'pending') {
                    if ($purchase->stocks()->exists()) {
                        $purchase->stocks()->delete();
                        $purchase->stocks()->attributes()->delete();
                    }
                } else {
                    $stock = $purchase->stocks()->create([
                        'product_id' => $request->product_ids[$i],
                        'qty' => $request->qtys[$i],
                    ]);

                    foreach ($attributeValues as $key => $attributeValueId) {

                        $stock->attributes()->create([
                            'attribute_id' => $attributeParents[$key], // parent ID
                            'attribute_value_id' => $attributeValueId, // value ID
                        ]);

                    }
                }

            }

        });
    }
}
