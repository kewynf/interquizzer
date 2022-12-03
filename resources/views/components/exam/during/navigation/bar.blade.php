@props(['current'])

<div class="w-full flex gap-4 snap-x overflow-x-hidden bg-white px-3 scroll-pl-12 ">
    @foreach ($exam->steps as $step)
        <div class="flex flex-col snap-start mt-6 @if ($step->id == $current)  @endif">
            <span class="text-lg text-gray-700">{{ $step->title }}</span>
            <span class="text-sm text-gray-500 truncate w-48">{{ $step->description }}</span>

            <div class="my-2"></div>

            <x-exam.during.navigation.status-indicator :type="$step->id == $current ? 'current' : ($step->completed ? 'completed' : 'pending')" />

            <div class="mt-6 @if ($step->id == $current) border-b border-orange-500 border-4 @endif"></div>
        </div>

        @if (!$loop->last)
            <div class="flex items-center">
                <i class="ph-caret-right-thin text-8xl text-gray-300"></i>
            </div>
        @endif

        @if ($loop->last)
            <div class="flex items-center">
                <a href="{{ route('exam.end', $exam->id) }}">
                    <i class="ph-check-bold text-8xl text-green-500"></i>
                </a>
            </div>
        @endif
    @endforeach
</div>
