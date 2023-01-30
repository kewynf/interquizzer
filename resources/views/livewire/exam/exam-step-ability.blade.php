<div class="px-8 py-4 flex flex-col gap-2 w-full bg-white text-gray-900 dark:bg-gray-800 dark:text-white">

    <span class="font-bold text-xl">{{ $ability->title }}</span>
    <span> {{ $ability->description }}</span>

    <div class="flex gap-8">
        <!--- CONTENT -->
        <div class="flex flex-col grow gap-2">
            <span>Content: {{ __('contentType.' . $ability->content_type) }}</span>

            @switch($ability->content_type)
                @case('text')
                    <span class="w-full px-2 py-2 bg-slate-800 text-white rounded-md">{{ $ability->content_description }}</span>
                @break

                @case('image')
                    <div class="w-full h-96 bg-contain bg-no-repeat bg-start"
                        style="background-image: url('{{ $ability->content_path }}')">
                    </div>
                @break

                @case('video')
                    <video src="{{ $ability->content_path }}" controls></video>
                @break

                @case('audio')
                    <audio src="{{ $ability->content_path }}" controls></audio>
                @break

                @case('file')
                    <a href="{{ $ability->content_path }}" target="_blank">Download</a>
                @break
            @endswitch

        </div>

        <!--- CONTROLS -->
        <div class="flex flex-col gap-4 justify-center">
            @if (!$ability->discord_message_id)
                <button wire:click="sendDiscordMessage" class="bg-green-600 text-white px-4 py-2 rounded">
                    SEND CONTENT
                </button>
            @else
                <button disabled class="bg-slate-500 text-white px-4 py-2 rounded"> MESSAGE SENT
                </button>
            @endif


            @if (!$ability->answer_start_at)
                <button wire:click="startTimer" class="bg-green-500 text-white px-4 py-2 rounded">
                    START TIMER
                </button>
            @elseif(!$ability->answer_end_at)
                <button wire:click="stopTimer" class="bg-red-500 text-white px-4 py-2 rounded">
                    END TIMER
                </button>
            @else
                <button disabled="disabled" class="bg-gray-500 text-white">
                    {{ $ability->answer_end_at->diffForHumans($ability->answer_start_at) }}
                </button>
                <button wire:click="resetTimer" class="bg-red-500 text-white px-4 py-2 rounded">
                    RESET TIMER
                </button>
            @endif

            <div class="flex gap-2 items-center">
                <label for="">Grade:</label>
                <input wire:model="grade" wire:blur.debounce.200ms="saveGrade" type="number" min='0'
                    max='10' class="text-slate-900 w-full">
            </div>

            <div>
                <label for="">Observation</label>
                <textarea wire:model.defer="comment" wire:blur.debounce.200ms="saveComment" name="" id=""
                    class="w-full h-full text-slate-900"></textarea>
            </div>

            <span class="font-mono text-sm mt-8">Last Update: {{ $ability->updated_at }}</span>

        </div>
    </div>
</div>
