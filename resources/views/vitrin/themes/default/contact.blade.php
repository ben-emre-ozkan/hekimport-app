@extends('vitrin.themes.default.layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Contact Us</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Contact Information -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Get in Touch</h2>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Address</h3>
                                <p class="text-gray-600">{{ $vitrin->address }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Phone</h3>
                                <p class="text-gray-600">{{ $vitrin->phone }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Email</h3>
                                <p class="text-gray-600">{{ $vitrin->email }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Business Hours</h3>
                                <p class="text-gray-600">{{ $vitrin->business_hours }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Form -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Send us a Message</h2>
                        
                        <form action="{{ route('vitrin.contact.submit', ['subdomain' => $vitrin->subdomain]) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" id="name" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                <input type="text" name="subject" id="subject" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                                <textarea name="message" id="message" rows="4" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            </div>
                            
                            <div>
                                <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 