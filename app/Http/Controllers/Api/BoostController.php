<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class BoostController extends Controller
{
    // Catálogo mock (luego lo conectamos a DB)
    private array $catalog = [
        [
            "id" => 1,
            "title" => "Boosting",
            "description" => "Sube de rango con Boosting",
            "price" => 49.0
        ],
        [
            "id" => 2,
            "title" => "Coaching",
            "description" => "Sube de rango con coaching",
            "price" => 199.0
        ],
        [
            "id" => 3,
            "title" => "Duo Boost",
            "description" => "Sube de rango con un Booster",
            "price" => 99.0
        ],
    ];

    public function index(Request $request)
    {
        return response()->json($this->catalog, 200);
    }

    public function buy(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1|max:99',
        ]);

        $userId = $request->user()->id;
        $boostId = (int) $id;
        $qty = (int) $request->qty;

        // ✅ Suma qty si ya existe, si no crea
        Purchase::updateOrCreate(
            ['user_id' => $userId, 'boost_id' => $boostId],
            ['qty' => DB::raw("qty + {$qty}")]
        );

        // ✅ Leer el qty final
        $purchase = Purchase::where('user_id', $userId)
            ->where('boost_id', $boostId)
            ->first();

        return response()->json([
            "message" => "Compra exitosa",
            "boost_id" => $boostId,
            "user_id" => $userId,
            "qty_added" => $qty,
            "qty_total" => (int) ($purchase?->qty ?? $qty),
        ], 200);
    }

    public function myBoosts(Request $request)
    {
        $userId = $request->user()->id;

        $catalog = collect($this->catalog)->keyBy('id');

        $purchases = Purchase::where('user_id', $userId)->get();

        $result = $purchases->map(function ($p) use ($catalog) {
            $b = $catalog->get($p->boost_id);
            if (!$b) return null;

            return [
                "id" => $b["id"],
                "title" => $b["title"],
                "description" => $b["description"],
                "price" => $b["price"],
                "qty_total" => (int) $p->qty,
            ];
        })->filter()->values();

        return response()->json($result, 200);
    }
}