<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;
use Stripe\Exception\CardException;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display the subscription plans.
     */
    public function plans()
    {
        $plans = Plan::active()->ordered()->get();
        $clinic = auth()->user()->currentClinic;

        return view('subscriptions.plans', compact('plans', 'clinic'));
    }

    /**
     * Show the checkout page for a specific plan.
     */
    public function checkout(Request $request, Plan $plan)
    {
        $clinic = auth()->user()->currentClinic;
        
        if (!$clinic) {
            return redirect()->route('clinics.create')
                ->with('error', 'Please create a clinic first.');
        }

        // If the clinic is already subscribed to this plan
        if ($clinic->subscribedToPlan($plan->stripe_price_id, 'default')) {
            return redirect()->route('billing.index')
                ->with('info', 'You are already subscribed to this plan.');
        }

        // Check if this is one of the first 15 users for special trial
        $specialTrial = $clinic->id <= 15;
        $trialDays = $specialTrial ? 14 : $plan->trial_days;

        // Check for lifetime discount eligibility
        $price = $clinic->id <= 15 ? ($plan->price * 0.5) : $plan->price;

        return view('subscriptions.checkout', compact('plan', 'clinic', 'trialDays', 'price'));
    }

    /**
     * Process the subscription.
     */
    public function subscribe(Request $request, Plan $plan)
    {
        $clinic = auth()->user()->currentClinic;

        $request->validate([
            'payment_method' => 'required',
            'coupon' => 'nullable|string',
        ]);

        try {
            // Add payment method
            $clinic->addPaymentMethod($request->payment_method);

            // Create the subscription
            $subscription = $clinic->newSubscription('default', $plan->stripe_price_id);

            // Apply trial if eligible
            if ($clinic->id <= 15) {
                $subscription->trialDays(14);
            } elseif ($plan->trial_days > 0) {
                $subscription->trialDays($plan->trial_days);
            }

            // Apply coupon if provided
            if ($request->coupon) {
                $subscription->withCoupon($request->coupon);
            }

            // Apply lifetime discount for first 15 users
            if ($clinic->id <= 15) {
                $subscription->withCoupon('LIFETIME50');
            }

            $subscription->create($request->payment_method);

            return redirect()->route('billing.index')
                ->with('success', 'Your subscription has been activated successfully!');

        } catch (CardException $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        } catch (IncompletePayment $e) {
            return redirect()->route('cashier.payment', [
                $e->payment->id, 'redirect' => route('billing.index')
            ]);
        }
    }

    /**
     * Show the billing portal.
     */
    public function billing()
    {
        $clinic = auth()->user()->currentClinic;
        
        if (!$clinic) {
            return redirect()->route('clinics.create')
                ->with('error', 'Please create a clinic first.');
        }

        $subscription = $clinic->subscription('default');
        $invoices = $clinic->invoices();
        $paymentMethods = $clinic->paymentMethods();

        return view('subscriptions.billing', compact('clinic', 'subscription', 'invoices', 'paymentMethods'));
    }

    /**
     * Update the subscription.
     */
    public function update(Request $request)
    {
        $clinic = auth()->user()->currentClinic;
        $plan = Plan::where('stripe_price_id', $request->plan)->firstOrFail();

        try {
            // If switching to a higher plan
            if ($plan->price > $clinic->subscription('default')->plan->price) {
                $clinic->subscription('default')->swapAndInvoice($plan->stripe_price_id);
            } else {
                $clinic->subscription('default')->swap($plan->stripe_price_id);
            }

            return redirect()->route('billing.index')
                ->with('success', 'Your subscription has been updated successfully!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Cancel the subscription.
     */
    public function cancel(Request $request)
    {
        $clinic = auth()->user()->currentClinic;

        if ($request->immediately) {
            $clinic->subscription('default')->cancelNow();
        } else {
            $clinic->subscription('default')->cancel();
        }

        return redirect()->route('billing.index')
            ->with('success', 'Your subscription has been cancelled.');
    }

    /**
     * Resume a cancelled subscription.
     */
    public function resume()
    {
        $clinic = auth()->user()->currentClinic;
        
        if ($clinic->subscription('default')->onGracePeriod()) {
            $clinic->subscription('default')->resume();

            return redirect()->route('billing.index')
                ->with('success', 'Your subscription has been resumed.');
        }

        return back()->with('error', 'Unable to resume subscription.');
    }

    /**
     * Download an invoice.
     */
    public function downloadInvoice(string $invoiceId)
    {
        return auth()->user()->currentClinic
            ->downloadInvoice($invoiceId, [
                'vendor' => 'Hekimport',
                'product' => 'Subscription',
            ]);
    }
}
