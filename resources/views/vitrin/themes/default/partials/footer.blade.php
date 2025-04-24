<footer class="bg-white border-t border-gray-200">
    <div class="container mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">About Us</h3>
                <p class="text-gray-600">{{ $vitrin->description }}</p>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('vitrin.home', ['subdomain' => $vitrin->subdomain]) }}" class="text-gray-600 hover:text-gray-900">
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vitrin.about', ['subdomain' => $vitrin->subdomain]) }}" class="text-gray-600 hover:text-gray-900">
                            About
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vitrin.blog', ['subdomain' => $vitrin->subdomain]) }}" class="text-gray-600 hover:text-gray-900">
                            Blog
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('vitrin.contact', ['subdomain' => $vitrin->subdomain]) }}" class="text-gray-600 hover:text-gray-900">
                            Contact
                        </a>
                    </li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Info</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>{{ $vitrin->address }}</li>
                    <li>Phone: {{ $vitrin->phone }}</li>
                    <li>Email: {{ $vitrin->email }}</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-200">
            <p class="text-center text-gray-600">
                &copy; {{ date('Y') }} {{ $vitrin->name }}. All rights reserved.
            </p>
        </div>
    </div>
</footer> 