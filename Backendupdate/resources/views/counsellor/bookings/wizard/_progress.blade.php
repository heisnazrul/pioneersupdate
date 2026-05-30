<div class="mb-6">
    <div class="flex items-center justify-between text-xs text-gray-500 mb-2">
        <span>Step {{ $step }} of {{ $totalSteps }}</span>
        <span>{{ $stepLabels[$step - 1] ?? '' }}</span>
    </div>
    <div class="h-2 w-full rounded-full bg-gray-200 overflow-hidden">
        <div class="h-full bg-blue-600 transition-all" style="width: {{ round(($step / max(1, $totalSteps)) * 100) }}%"></div>
    </div>
    <div class="mt-3 flex flex-wrap gap-2">
        @foreach($stepLabels as $index => $label)
            <span class="rounded-full px-2.5 py-1 text-xs {{ ($index + 1) === $step ? 'bg-blue-100 text-blue-700 font-semibold' : (($index + 1) < $step ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500') }}">
                {{ $index + 1 }}. {{ $label }}
            </span>
        @endforeach
    </div>
</div>
