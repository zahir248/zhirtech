<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; 

class AdminController extends Controller
{
    public function loginPage()
    {
        if (auth()->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Validate input
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Attempt to login
        if (Auth::attempt($credentials)) {
            // Authentication passed
            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Invalid credentials')->withInput();
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'services' => Service::all(),
            'orders' => Order::with('service')->latest()->get()
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('admin.dashboard')
                ->with('success', 'Service deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to delete service');
        }
    }

    public function update(Request $request, Service $service)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'note' => 'nullable|string|max:255',
            ]);

            $service->update($validated);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Service updated successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to update service');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'note' => 'nullable|string|max:255',
            ]);

            Service::create($validated);

            return redirect()->route('admin.dashboard')
                ->with('success', 'Service created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to create service: ' . $e->getMessage());
        }
    }

}
