<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Delivery;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Message;
use App\Models\Payment;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ClientController extends Controller
{
    use AuthorizesRequests;
    public function dashboard()
    {
        $client = Client::find(Auth::guard('client')->id());
        $activeDeliveries = Delivery::where('client_id', $client->id)
            ->whereIn('status', ['pending', 'accepted', 'in_progress'])
            ->with('driver')
            ->latest()
            ->take(5)
            ->get();

        $recentDeliveries = Delivery::where('client_id', $client->id)
            ->where('status', 'completed')
            ->with('driver', 'review')
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'active' => Delivery::where('client_id', $client->id)
                ->whereIn('status', ['pending', 'accepted', 'in_progress'])
                ->count(),
            'completed' => Delivery::where('client_id', $client->id)
                ->where('status', 'completed')
                ->count(),
            'total_spent' => Delivery::where('client_id', $client->id)
                ->where('status', 'completed')
                ->sum('amount')
        ];

        return view('client.dashboard', compact('client', 'activeDeliveries', 'recentDeliveries', 'stats'));
    }

    public function deliveries()
    {
        $client = Auth::guard('client')->user();
        $deliveries = Delivery::where('client_id', $client->id)
            ->with('driver', 'payment')
            ->latest()
            ->paginate(10);

        return view('client.deliveries', compact('client', 'deliveries'));
    }

    public function showDelivery(Delivery $delivery)
    {
        $this->authorize('view', $delivery);
        
        $delivery->load('driver', 'payment', 'messages');
        return view('client.delivery-details', compact('delivery'));
    }

    public function createDelivery()
    {
        return view('client.new-delivery');
    }

    public function storeDelivery(Request $request)
    {
        $validated = $request->validate([
            'pickup_location' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'package_type' => 'required|in:small,medium,large,extra_large',
            'delivery_type' => 'required|in:standard,express,overnight',
            'delivery_date' => 'required|date|after_or_equal:today',
            'special_instructions' => 'nullable|string',
            'auto_assign' => 'boolean'
        ]);

        $client = Auth::guard('client')->user();

        $delivery = new Delivery();
        $delivery->client_id = $client->id;
        $delivery->pickup_location = $validated['pickup_location'];
        $delivery->destination = $validated['destination'];
        $delivery->package_type = $validated['package_type'];
        $delivery->delivery_type = $validated['delivery_type'];
        $delivery->delivery_date = $validated['delivery_date'];
        $delivery->special_instructions = $validated['special_instructions'];
        $delivery->status = 'pending';
        
        // Calculate amount based on package type and delivery type
        $delivery->amount = $this->calculateDeliveryAmount(
            $validated['package_type'],
            $validated['delivery_type']
        );

        $delivery->save();

        if ($request->auto_assign) {
            // Logic to auto-assign driver would go here
        }

        return redirect()->route('client.deliveries')->with('success', 'Delivery request created successfully!');
    }

    protected function calculateDeliveryAmount($packageType, $deliveryType)
    {
        // Base prices
        $basePrices = [
            'small' => 5.00,
            'medium' => 10.00,
            'large' => 15.00,
            'extra_large' => 25.00
        ];

        // Delivery type multipliers
        $multipliers = [
            'standard' => 1.0,
            'express' => 1.5,
            'overnight' => 2.0
        ];

        return $basePrices[$packageType] * $multipliers[$deliveryType];
    }

    public function messages()
    {
        $client = Auth::guard('client')->user();
        $messages = Message::whereHas('delivery', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->with('delivery')
            ->latest()
            ->paginate(10);

        return view('client.messages', compact('client', 'messages'));
    }

    public function sendMessage(Request $request, Delivery $delivery)
    {
        $this->authorize('view', $delivery);

        $validated = $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $client = Auth::guard('client')->user();

        $message = new Message();
        $message->delivery_id = $delivery->id;
        $message->sender_id = $client->id;
        $message->sender_type = 'client';
        $message->message = $validated['message'];
        $message->save();

        return back()->with('success', 'Message sent!');
    }

    public function payments()
    {
        $client = Auth::guard('client')->user();
        $payments = Payment::whereHas('delivery', function($query) use ($client) {
                $query->where('client_id', $client->id);
            })
            ->with('delivery')
            ->latest()
            ->paginate(10);

        return view('client.payments', compact('client', 'payments'));
    }

    public function settings()
    {
        $client = Auth::guard('client')->user();
        return view('client.settings', compact('client'));
    }

    public function updateSettings(Request $request)
    {
        $client = new Client();

        $validated = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,'.$client->id,
            'phone' => 'required|string|max:20',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $client->fname = $validated['fname'];
        $client->lname = $validated['lname'];
        $client->email = $validated['email'];
        $client->phone = $validated['phone'];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('client_images', 'public');
            $client->image = $path;
        }

        $client->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}