<div class="bg-white p-4 shadow rounded-lg hover:shadow-md transition">
    <h2 class="text-xl font-semibold mb-2">{{ $title ?? 'Section' }}</h2>
    <p class="text-sm text-gray-600 mb-4">{{ $description ?? 'No description available' }}</p>
    
    @if(isset($route))
        @if(Route::has($route))
            <a href="{{ route($route) }}" class="text-indigo-600 font-medium hover:underline">Go to section →</a>
        @else
            <a href="#" class="text-indigo-600 font-medium hover:underline">Go to section →</a>
        @endif
    @else
        <a href="#" class="text-indigo-600 font-medium hover:underline">Go to section →</a>
    @endif
</div>