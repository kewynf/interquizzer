<x-app-layout>

    <div class="py-12 text-gray-800 dark:text-gray-200 leading-tight">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 space-y-6">
            <x-slot name="header">
                <h2 class="font-semibold text-xl ">
                    {{ __('Iniciar Novo Exame') }}
                </h2>
            </x-slot>


            <form action="{{ route('exam.generate') }}" method="POST" class="w-full flex flex-col gap-8">
                @csrf
                <div class="rounded w-full bg-gray-200 dark:bg-gray-800 px-4 py-4 gap-4 flex items-center">

                    <label for="exam_template_id" class="text-xl font-bold">Selecione o Exame:</label>

                    <select name="exam_template_id" id="exam_template_id" class="grow dark:bg-gray-800 ">
                        <option value="">Selecione o Exame</option>
                        @foreach ($exam_templates as $exam_template)
                            <option value="{{ $exam_template->id }}">{{ $exam_template->title }}</option>
                        @endforeach
                    </select>

                </div>

                <div class="rounded w-full bg-gray-200 dark:bg-gray-800 px-4 py-4 gap-4 flex items-center">

                    <label for="candidate_id" class="text-xl font-bold">Selecione o Candidato:</label>

                    <select name="candidate_id" id="candidate_id" class="grow dark:bg-gray-800 ">
                        <option value="">Selecione o Candidato</option>
                        @foreach ($candidates as $candidate)
                            <option value="{{ $candidate->id }}">{{ $candidate->name }}</option>
                        @endforeach
                    </select>

                </div>

                <button
                    class="w-full px-4 py-4 border border-green-500 text-green-500 bg-opacity-0 hover:bg-opacity-100 hover:bg-green-500 hover:text-white rounded"
                    type="submit">INICIAR</button>
            </form>

        </div>
    </div>

</x-app-layout>
