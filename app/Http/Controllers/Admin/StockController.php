<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Services\StockService;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stock_service;
    public function __construct(StockService $stock_service)
    {
        $this->stock_service = $stock_service;
        $this->middleware(['permission:view_stock'])->only(['index', 'show']);
    }
    public function index()
    {
        $stocks = Stock::with('product', 'attributes.attribute', 'attributes.attributeValue')->get();

        $uniqueStocks = $this->stock_service->group($stocks);

        return view('admin.stocks.index', ['stocks' => $uniqueStocks]);
    }

    public function show($id)
    {
        $selectedStock = Stock::with('attributes')->find($id);

        $stocks = $this->stock_service->getSingle($selectedStock);

        return view('admin.stocks.show', ['stocks' => $stocks]);
    }

    public function updatePrice(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:stocks,id',
            'price' => 'required|numeric|min:0',
        ]);

        $stock = Stock::findOrFail($request->id);
        $stock->selling_price = $request->price;
        $stock->save();

        activity()->log('قام '.auth()->user()->name.'بتعديل سعر المخزون' . $stock->product->name);

        return response()->json([
            'status' => true,
            'message' => trans('stock.price_updated'),
            'price' => $stock->selling_price,
        ]);
    }
}
