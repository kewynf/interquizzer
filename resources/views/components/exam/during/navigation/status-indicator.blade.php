@props([
    'type' => 'pending',
])

@switch($type)
    @case('pending')
        <span class="w-fit px-2 py-[0.25rem] text-sm text-white bg-gray-300">Pending</span>
    @break

    @case('current')
        <span class="w-fit px-2 py-[0.25rem] text-sm text-white bg-orange-500">Current</span>
    @break

    @case('completed')
        <span class="w-fit px-2 py-[0.25rem] text-sm text-white bg-green-500">Completed</span>
    @break
@endswitch
