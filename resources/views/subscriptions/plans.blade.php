@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Choose Your Plan</h1>
            <p class="text-lg text-gray-600">Select the plan that best fits your clinic's needs</p>
            @if($clinic->id <= 15)
                <div class="mt-4 bg-blue-50 text-blue-800 px-4 py-2 rounded-lg inline-block">
                    🎉 Special Offer: 50% lifetime discount + 14-day trial as one of our first 15 users!
                </div>
            @endif
        </div>

        <!-- Toggle Monthly/Yearly -->
        <div class="flex justify-center mb-8">
            <div class="bg-gray-100 rounded-lg p-1">
                <button type="button" class="px-4 py-2 rounded-md text-sm font-medium billing-toggle active" data-interval="month">
                    Monthly
                </button>
                <button type="button" class="px-4 py-2 rounded-md text-sm font-medium billing-toggle" data-interval="year">
                    Yearly <span class="text-green-600">(Save 20%)</span>
                </button>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($plans->where('interval', 'month') as $plan)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden plan-card" data-interval="month">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $plan->name }}</h2>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-bold text-gray-900">{{ $clinic->id <= 15 ? number_format($plan->price * 0.5, 2) : $plan->price }}</span>
                            <span class="text-gray-600 ml-1">TL/month</span>
                        </div>
                        <ul class="space-y-4 mb-6">
                            @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('subscription.checkout', $plan) }}" class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Get Started
                        </a>
                    </div>
                </div>
            @endforeach

            @foreach($plans->where('interval', 'year') as $plan)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden plan-card hidden" data-interval="year">
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $plan->name }}</h2>
                        <div class="flex items-baseline mb-6">
                            <span class="text-4xl font-bold text-gray-900">{{ $clinic->id <= 15 ? number_format($plan->price * 0.5 / 12, 2) : number_format($plan->price / 12, 2) }}</span>
                            <span class="text-gray-600 ml-1">TL/month</span>
                        </div>
                        <div class="text-sm text-green-600 mb-6">
                            Billed annually at {{ $clinic->id <= 15 ? number_format($plan->price * 0.5, 2) : $plan->price }} TL
                        </div>
                        <ul class="space-y-4 mb-6">
                            @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <svg class="h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-gray-600">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('subscription.checkout', $plan) }}" class="block w-full bg-blue-600 text-white text-center px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            Get Started
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- FAQ Section -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan later?</h3>
                    <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a trial period?</h3>
                    <p class="text-gray-600">Yes, all plans come with a {{ $clinic->id <= 15 ? '14' : '7' }}-day free trial.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I cancel my subscription?</h3>
                    <p class="text-gray-600">Yes, you can cancel your subscription at any time. You'll continue to have access until the end of your billing period.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                    <p class="text-gray-600">We accept all major credit cards through our secure payment processor, Stripe.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.billing-toggle');
    const planCards = document.querySelectorAll('.plan-card');

    toggleButtons.forEach(button => {
        button.addEventListener('click', () => {
            const interval = button.dataset.interval;

            // Update toggle buttons
            toggleButtons.forEach(btn => btn.classList.remove('active', 'bg-white', 'shadow'));
            button.classList.add('active', 'bg-white', 'shadow');

            // Show/hide plans
            planCards.forEach(card => {
                if (card.dataset.interval === interval) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});
</script>

<style>
.billing-toggle.active {
    @apply bg-white shadow;
}
</style>
@endpush
@endsection 