@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Complete Your Subscription</h1>

                <!-- Plan Summary -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h2>
                            <p class="text-sm text-gray-600">{{ $plan->formatted_interval }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-2xl font-bold text-gray-900">{{ number_format($price, 2) }} TL</span>
                            <span class="text-sm text-gray-600">/{{ $plan->interval }}</span>
                        </div>
                    </div>
                    @if($trialDays > 0)
                        <div class="text-sm text-blue-600">
                            Includes {{ $trialDays }}-day free trial
                        </div>
                    @endif
                    @if($clinic->id <= 15)
                        <div class="text-sm text-green-600 mt-2">
                            50% lifetime discount applied as an early subscriber!
                        </div>
                    @endif
                </div>

                <!-- Payment Form -->
                <form action="{{ route('subscription.subscribe', $plan) }}" method="POST" id="payment-form">
                    @csrf

                    <!-- Coupon Code -->
                    <div class="mb-6">
                        <label for="coupon" class="block text-sm font-medium text-gray-700 mb-2">
                            Have a coupon code?
                        </label>
                        <div class="flex space-x-4">
                            <input type="text" name="coupon" id="coupon"
                                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <button type="button" id="apply-coupon"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                Apply
                            </button>
                        </div>
                    </div>

                    <!-- Card Element -->
                    <div class="mb-6">
                        <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                            Credit or debit card
                        </label>
                        <div id="card-element" class="p-3 border rounded-md bg-white">
                            <!-- Stripe Card Element will be inserted here -->
                        </div>
                        <div id="card-errors" role="alert" class="mt-2 text-sm text-red-600"></div>
                    </div>

                    <!-- Terms -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <input type="checkbox" name="terms" id="terms" required
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 block text-sm text-gray-600">
                                I agree to the <a href="#" class="text-blue-600 hover:text-blue-800">Terms of Service</a> and
                                <a href="#" class="text-blue-600 hover:text-blue-800">Privacy Policy</a>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submit-button"
                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50">
                        Start Subscription
                    </button>

                    <p class="mt-4 text-sm text-gray-600 text-center">
                        Your subscription will start after the {{ $trialDays }}-day trial.
                        You can cancel anytime.
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
const stripe = Stripe('{{ config('cashier.key') }}');
const elements = stripe.elements();

// Create card Element
const card = elements.create('card', {
    style: {
        base: {
            fontSize: '16px',
            color: '#32325d',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    }
});

// Mount the card Element
card.mount('#card-element');

// Handle validation errors
card.addEventListener('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

// Handle form submission
const form = document.getElementById('payment-form');
const submitButton = document.getElementById('submit-button');

form.addEventListener('submit', async function(event) {
    event.preventDefault();
    submitButton.disabled = true;

    const {paymentMethod, error} = await stripe.createPaymentMethod('card', card);

    if (error) {
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
        submitButton.disabled = false;
    } else {
        // Add payment method ID to form
        const hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'payment_method');
        hiddenInput.setAttribute('value', paymentMethod.id);
        form.appendChild(hiddenInput);

        // Submit form
        form.submit();
    }
});

// Handle coupon application
document.getElementById('apply-coupon').addEventListener('click', async function() {
    const couponInput = document.getElementById('coupon');
    const coupon = couponInput.value.trim();

    if (!coupon) return;

    try {
        const response = await fetch(`/api/coupons/validate/${coupon}`);
        const data = await response.json();

        if (data.valid) {
            // Update UI to show discount
            // This would need to be implemented based on your coupon system
        } else {
            alert('Invalid coupon code');
        }
    } catch (error) {
        console.error('Error validating coupon:', error);
        alert('Error validating coupon');
    }
});
</script>
@endpush
@endsection 