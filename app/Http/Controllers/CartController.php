<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Product;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $user = Auth::user();



    if (!$user) {
        return redirect('/Login')->with('error', 'You need to login first.');
    }
        // dd($user->uuid);
        $product = DB::table('product')->where('ID_Produk', $request->product_id)->first();
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }


        // Get or create the cart
        // dd($user->id);
        $cart = Cart::firstOrCreate(
            ['userId' => $user->uuid],
            [
                'uuid' => (string) Str::uuid(),
                'tanggal' => now()->toDateString(),
                'waktu' => now()->toTimeString(),
                'total' => 0
            ]
        );

        $cartDetail = CartDetail::firstOrCreate(
            ['cartId' => $cart->uuid, 'productId' => $productId],
            [
                'uuid' => (string) Str::uuid(),
                'qty' => $quantity,
                'subTotal' => $product->Harga * $quantity
            ]
        );

        if (!$cartDetail->wasRecentlyCreated) {
            $cartDetail->qty += $quantity;
            $cartDetail->subTotal = $product->Harga * $cartDetail->qty;
            $cartDetail->save();
        }

        $cart->total = $cart->cartDetails()->sum('subTotal');
        $cart->save();

        return view('Cart')->with(['success' => true, 'cart' => $cart, 'message' => 'Product added to cart successfully!']);
    }


    public function showCart()
{
    // $cart = session()->get('cart', []);
    // $subtotal = array_reduce($cart, function ($carry, $item) {
    //     return $carry + ($item['price'] * $item['quantity']);
    // }, 0);
    // $total = $subtotal;
    $user = Auth::user();

    if (!$user) {
        return redirect('/login')->with('error', 'You need to login first.');
    }

    $cart = Cart::with('cartDetails.product')->where('userId', $user->uuid)->first();
    // dd($cart);
    // dd($cart);
    return view('Cart', compact('cart'));
}
public function updateQuantity(Request $request)
{
    $cartDetailId = $request->input('cartDetailId');
    $change = $request->input('change');

    $cartDetail = CartDetail::findOrFail($cartDetailId);
    $newQty = $cartDetail->qty + $change;

    if ($newQty < 1) {
        return response()->json(['success' => false, 'message' => 'Quantity cannot be less than 1']);
    }

    $cartDetail->qty = $newQty;
    $cartDetail->subTotal = $newQty * $cartDetail->product->Price;
    $cartDetail->save();

    // Update cart total
    $cartDetail->cart->total = $cartDetail->cart->cartDetails()->sum('subTotal');
    $cartDetail->cart->save();

    return response()->json(['success' => true]);
}
public function deleteItem(Request $request)
{
    // $cartDetailId = $request->input('cartDetailId');

    // // Hapus item keranjang berdasarkan id
    // $deleted = CartDetail::where('uuid', $cartDetailId)->delete();

    // if($deleted) {
    //     return response()->json(['success' => true, 'message' => 'Item has been deleted successfully.']);
    // } else {
    //     return response()->json(['success' => false, 'message' => 'Failed to delete item.']);
    // }

    $cartDetailId = $request->input('cartDetailId');

    // Temukan detail keranjang yang sesuai
    $cartDetail = CartDetail::where('uuid', $cartDetailId)->first();

    if (!$cartDetail) {
        return response()->json(['success' => false, 'message' => 'Item not found.']);
    }

    // Hapus item keranjang berdasarkan id
    $deleted = $cartDetail->delete();

    if($deleted) {
        // Update cart total
        $cartDetail->cart->total = $cartDetail->cart->cartDetails()->sum('subTotal');
        $cartDetail->cart->save();

        return response()->json(['success' => true, 'message' => 'Item has been deleted successfully.']);
    } else {
        return response()->json(['success' => false, 'message' => 'Failed to delete item.']);
    }
}
}
