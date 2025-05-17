<?php

namespace App\Http\Controllers\client;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class ClientController extends Controller
{
    public function index()
    {
        $services = Service::all();

        $iconMap = [
            1  => 'fas fa-globe',           // Website
            2  => 'fas fa-code',            // Web App
            3  => 'fas fa-mobile-alt',      // Mobile App
            4  => 'fas fa-shopping-cart',   // E-commerce
            5  => 'fas fa-plug',            // API
            6  => 'fas fa-cloud',           // Cloud service
            7  => 'fas fa-server',          // Server hosting
            8  => 'fas fa-shield-alt',      // Security
            9  => 'fas fa-database',        // Database
            10 => 'fas fa-chart-line',      // Analytics
            11 => 'fas fa-users',           // User management
            12 => 'fas fa-envelope-open-text', // Email service
            13 => 'fas fa-cogs',            // Custom solutions
            14 => 'fas fa-robot',           // Automation / AI
            15 => 'fas fa-desktop',         // Desktop app
            16 => 'fas fa-sitemap',         // Architecture / planning
            17 => 'fas fa-comments',        // Communication
            18 => 'fas fa-money-bill-wave', // Payment integration
            19 => 'fas fa-wifi',            // IoT / Networking
            20 => 'fas fa-tools',           // Maintenance / support
        ];

        foreach ($services as $service) {
            $service->icon = $iconMap[$service->id] ?? 'fas fa-cogs'; // fallback icon
        }

        return view('client.index', compact('services'));
    }

    public function processPayment(Request $request)
    {
        // Validate form data
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service' => 'required|exists:services,id',
        ]);

        // Get service details
        $service = Service::findOrFail($validated['service']);
        
        // Generate unique reference number
        $reference_no = 'ZHIR-' . strtoupper(Str::random(8));
        
        // Create order record with pending status
        $order = Order::create([
            'customer_name' => $validated['customer_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'service_id' => $service->id,
            'amount' => 1.00,
            'status' => 'pending',
            'reference_no' => $reference_no,
        ]);

        // ToyyibPay API credentials
        $userSecretKey = config('services.toyyibpay.secret_key');
        $categoryCode = config('services.toyyibpay.category_code');
        
        // Prepare ToyyibPay payment parameters
        $paymentData = [
            'userSecretKey' => $userSecretKey,
            'categoryCode' => $categoryCode,
            'billName' => 'ZhirTech - ' . $service->name,
            'billDescription' => 'Payment for ' . $service->name . ' service',
            'billPriceSetting' => 1,
            'billPayorInfo' => 1,
            'billAmount' => 100, // 100 cents = RM 1.00
            'billReturnUrl' => route('payment.callback'),
            'billCallbackUrl' => route('payment.callback'),
            'billExternalReferenceNo' => $reference_no,
            'billTo' => $validated['customer_name'],
            'billEmail' => $validated['email'],
            'billPhone' => $validated['phone'],
            'billSplitPayment' => 0,
            'billPaymentChannel' => 0, // All payment channels
            'billDisplayMerchant' => 1,
            'billContentEmail' => 'Thank you for your order with ZhirTech!',
            'billChargeToCustomer' => 1, // Customer bears the transaction fee
        ];

        // Make API request to ToyyibPay
        $response = Http::asForm()->withOptions(['verify' => false])
               ->post('https://toyyibpay.com/index.php/api/createBill', $paymentData);
        
        if ($response->successful()) {
            $result = $response->json();
            if (isset($result[0]['BillCode'])) {
                // Redirect to ToyyibPay payment page
                return redirect('https://toyyibpay.com/' . $result[0]['BillCode']);
            }
        }
        
        // If ToyyibPay API call fails
        return back()->with('error', 'Payment initialization failed. Please try again later.');
    }

    public function paymentCallback(Request $request)
    {
        // Get payment status and reference number from ToyyibPay callback
        $status = $request->status_id;
        $reference_no = $request->order_id;
        
        // Find the order by reference number
        $order = Order::where('reference_no', $reference_no)->first();
        
        if (!$order) {
            return redirect()->route('client.index')->with([
                'show_modal' => true,
                'modal_type' => 'error',
                'modal_title' => 'Order Not Found',
                'modal_message' => 'We could not find your order. Please contact support.'
            ]);
        }
        
        // Update order status based on ToyyibPay status
        switch ($status) {
            case '1': // Payment Successful
                $order->update(['status' => 'paid']);
                return redirect()->route('client.index')->with([
                    'show_modal' => true,
                    'modal_type' => 'success',
                    'modal_title' => 'Payment Successful',
                    'modal_message' => 'Thank you for your payment! We will process your order shortly.'
                ]);
                
            case '2': // Pending Payment
                $order->update(['status' => 'pending']);
                return redirect()->route('client.index')->with([
                    'show_modal' => true,
                    'modal_type' => 'info',
                    'modal_title' => 'Payment Pending',
                    'modal_message' => 'Your payment is being processed. We will update you once it is confirmed.'
                ]);
                
            case '3': // Payment Failed
                $order->update(['status' => 'failed']);
                return redirect()->route('client.index')->with([
                    'show_modal' => true,
                    'modal_type' => 'error',
                    'modal_title' => 'Payment Failed',
                    'modal_message' => 'Your payment was unsuccessful. Please try again or contact support.'
                ]);
                
            default:
                return redirect()->route('client.index')->with([
                    'show_modal' => true,
                    'modal_type' => 'error',
                    'modal_title' => 'Unknown Status',
                    'modal_message' => 'Payment status unknown. Please contact support for assistance.'
                ]);
        }
    }
}
