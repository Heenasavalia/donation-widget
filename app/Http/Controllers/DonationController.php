<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class DonationController extends Controller
{
    public function showForm()
    {
        return view('donation.form');
    }

    public function handleDonation(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ]);

        $donation = Donation::create([
            'name' => $request->name,
            'email' => $request->email,
            'amount' => $request->amount,
            'status' => 'pending',
        ]);

        // dd(config('services.stripe.secret'));
        // Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiKey(config('services.stripe.secret'));


        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Donation from ' . $request->name,
                    ],
                    'unit_amount' => $request->amount * 100, // amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('donation.success', ['id' => $donation->id]),
            'cancel_url' => route('donation.cancel', ['id' => $donation->id]),
        ], [
            'stripe_account' => env('STRIPE_CONNECT_ACCOUNT_ID'),
        ]);

        $donation->stripe_session_id = $session->id ?? null;
        $donation->save();
        return redirect($session->url);
    }


    public function success($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => 'success']);

        return redirect()->route('donation.form', ['success' => 1]);
    }

    public function cancel($id)
    {
        $donation = Donation::findOrFail($id);
        $donation->update(['status' => 'canceled']);

        return redirect()->route('donation.form', ['canceled' => 1]);
    }
}
