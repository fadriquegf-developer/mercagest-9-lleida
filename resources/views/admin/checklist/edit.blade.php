@extends(backpack_view('blank'))

@php
$defaultBreadcrumbs = [
    __('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    __('backpack::crud.add') => false,
];

$breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;
@endphp

@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{{ $checklist->name }}</span>
            <small>
                @if ($entry->is_stall())
                    {{ $origin->titular }} - {{ __('backpack.stalls.single') }} {{ $origin->num }}.
                @elseif($entry->is_market())
                    {{ $origin->name }}.
                @endif
            </small>

            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="d-print-none font-sm"><i
                            class="la la-angle-double-{{ config('backpack.base.html_direction') == 'rtl' ? 'right' : 'left' }}"></i>
                        {{ __('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="{{ $crud->getCreateContentClass() }}">
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <form method="post" action="{{ url($crud->route . '/' . $entry->getKey()) }}"
                @if ($crud->hasUploadFields('create')) enctype="multipart/form-data" @endif>
                {!! csrf_field() !!}
                {!! method_field('PUT') !!}

                <ul class="list-group mb-4">
                    <input type="hidden" name="origin" value="{{ $origin->id }}">
                    @foreach ($groups as $group => $answers)
                        <li class="list-group-item rounded-0">
                            <div class="">
                                <h2 class="cursor-pointer d-block text-uppercase">{{ $group }}</h2>
                            </div>
                        </li>
                        @foreach ($answers as $answer)
                            @php
                                $question = $answer->checklist_question;
                            @endphp
                            <li class="list-group-item rounded-0">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="question_{{ $question->id }}"
                                        name="question_{{ $question->id }}" type="checkbox" @checked(old('question_' . $question->id, $answer->is_check))
                                        onchange="handleChange(event)">
                                    <label class="cursor-pointer d-block custom-control-label"
                                        for="question_{{ $question->id }}">{{ $question->text }}</label>
                                    <div class="extra-inputs" style="display: none;">
                                        <textarea class="form-control" name="text_{{ $question->id }}"
                                            placeholder="{{ __('backpack.checklists.text_placeholder') }}">{{ old('text_' . $question->id, $answer->comment) }}</textarea>
                                        <input type="file" class="mt-2" name="filepond_{{ $question->id }}" />
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endforeach
                    <div class="hidden" element="div" bp-field-wrapper="true" bp-field-name="id" bp-field-type="hidden">
                        <input type="hidden" name="id" value="{{ $entry->getKey() }}" class="form-control">
                    </div>
                </ul>

                @if ($entry->is_stall())
                    <div class="custom-control custom-checkbox mb-4">
                        <input class="custom-control-input" id="all_ok" name="all_ok" type="checkbox" value="true" @checked(old('all_ok', $entry->all_ok))>
                        <label class="cursor-pointer d-block custom-control-label" for="all_ok">
                            {{ __('backpack.checklists.all_ok') }}
                        </label>
                    </div>
                @endif

                <!-- This makes sure that all field assets are loaded. -->
                <div class="d-none" id="parentLoadedAssets">{{ json_encode(Assets::loaded()) }}</div>
                @include('crud::inc.form_save_buttons')
            </form>
        </div>
    </div>
@endsection

@if ($entry->is_stall())
    @include('admin.checklist.inc.script')

    @push('after_scripts')
        <script>
            $(function() {
                @foreach ($groups as $group => $answers)
                    @foreach ($answers as $answer)
                        @php
                            $files = collect();
                            $question = $answer->checklist_question;
                            if (old('filepond_' . $question->id)) {
                                $files->push([
                                    'source' => old('filepond_' . $question->id),
                                    'options' => ['type' => 'limbo'],
                                ]);
                            }
                            
                            if ($files->isEmpty() && $answer->img) {
                                $files->push([
                                    'source' => $answer->id,
                                    'options' => ['type' => 'local'],
                                ]);
                            }
                        @endphp
                        FilePond.create(document.querySelector("input[name=\"filepond_{{ $question->id }}\"]"), {
                            files: @json($files)
                        });
                    @endforeach
                @endforeach
            });
        </script>
    @endpush
@endif
