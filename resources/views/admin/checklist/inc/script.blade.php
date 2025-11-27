@push('after_styles')
    <link href="{{ asset('packages/filepond/dist/filepond.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('packages/filepond/dist/filepond-plugin-image-preview.min.css') }}" rel="stylesheet" />
@endpush

@push('after_scripts')
    <script>
        $(document).ready(function() {
            $("input[name^='question_']").each(function() {
                $(this).trigger('change');
            });
        });

        function handleChange(e) {
            const target = $(e.target);
            const extres = target.parent().find('.extra-inputs');
            const allOk = $("#all_ok");

            if (target.is(':checked')) {
                extres.show();
            } else {
                extres.hide();
                allOk.prop("disabled", true)
            }

            // enable or disable all ok input
            var result = $("input[name^='question_']:checkbox:checked").length > 0
            allOk.prop("disabled", result);
            if (result) {
                allOk.prop("checked", false);
            }
        }
    </script>

    {{-- FilePond --}}
    <!-- include FilePond plugins -->
    <script src="{{ asset('packages/filepond/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('packages/filepond/dist/filepond-plugin-image-preview.min.js') }}"></script>

    <!-- include FilePond library -->
    <script src="{{ asset('packages/filepond/dist/filepond.min.js') }}"></script>

    <script>
        // First register any plugins
        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        FilePond.registerPlugin(FilePondPluginImagePreview);

        // Turn input element into a pond
        FilePond.setOptions({
            chunkUploads: true,
            maxFileSize: '2MB',
            server: {
                url: "{{ backpack_url('filepond') }}",
                headers: {
                    'X-CSRF-TOKEN': "{{ @csrf_token() }}",
                },
                restore: '/restore/',
                load: '/load/',
            },
            credits: false,
            labelIdle: '{!! __('filepond.labelIdle') !!}',
            labelInvalidField: '{{ __('filepond.labelInvalidField') }}',
            labelFileWaitingForSize: '{{ __('filepond.labelFileWaitingForSize') }}',
            labelFileSizeNotAvailable: '{{ __('filepond.labelFileSizeNotAvailable') }}',
            labelFileLoading: '{{ __('filepond.labelFileLoading') }}',
            labelFileLoadError: '{{ __('filepond.labelFileLoadError') }}',
            labelFileProcessing: '{{ __('filepond.labelFileProcessing') }}',
            labelFileProcessingComplete: '{{ __('filepond.labelFileProcessingComplete') }}',
            labelFileProcessingAborted: '{{ __('filepond.labelFileProcessingAborted') }}',
            labelFileProcessingError: '{{ __('filepond.labelFileProcessingError') }}',
            labelFileProcessingRevertError: '{{ __('filepond.labelFileProcessingRevertError') }}',
            labelFileRemoveError: '{{ __('filepond.labelFileRemoveError') }}',
            labelTapToCancel: '{{ __('filepond.labelTapToCancel') }}',
            labelTapToRetry: '{{ __('filepond.labelTapToRetry') }}',
            labelTapToUndo: '{{ __('filepond.labelTapToUndo') }}',
            labelButtonRemoveItem: '{{ __('filepond.labelButtonRemoveItem') }}',
            labelButtonAbortItemLoad: '{{ __('filepond.labelButtonAbortItemLoad') }}',
            labelButtonRetryItemLoad: '{{ __('filepond.labelButtonRetryItemLoad') }}',
            labelButtonAbortItemProcessing: '{{ __('filepond.labelButtonAbortItemProcessing') }}',
            labelButtonUndoItemProcessing: '{{ __('filepond.labelButtonUndoItemProcessing') }}',
            labelButtonRetryItemProcessing: '{{ __('filepond.labelButtonRetryItemProcessing') }}',
            labelButtonProcessItem: '{{ __('filepond.labelButtonProcessItem') }}',
            labelMaxFileSizeExceeded: '{{ __('filepond.labelMaxFileSizeExceeded') }}',
            labelMaxFileSize: '{{ __('filepond.labelMaxFileSize') }}',
            labelMaxTotalFileSizeExceeded: '{{ __('filepond.labelMaxTotalFileSizeExceeded') }}',
            labelMaxTotalFileSize: '{{ __('filepond.labelMaxTotalFileSize') }}',
            labelFileTypeNotAllowed: '{{ __('filepond.labelFileTypeNotAllowed') }}',
            fileValidateTypeLabelExpectedTypes: '{{ __('filepond.fileValidateTypeLabelExpectedTypes') }}',
            imageValidateSizeLabelFormatError: '{{ __('filepond.imageValidateSizeLabelFormatError') }}',
            imageValidateSizeLabelImageSizeTooSmall: '{{ __('filepond.imageValidateSizeLabelImageSizeTooSmall') }}',
            imageValidateSizeLabelImageSizeTooBig: '{{ __('filepond.imageValidateSizeLabelImageSizeTooBig') }}',
            imageValidateSizeLabelExpectedMinSize: '{{ __('filepond.imageValidateSizeLabelExpectedMinSize') }}',
            imageValidateSizeLabelExpectedMaxSize: '{{ __('filepond.imageValidateSizeLabelExpectedMaxSize') }}',
            imageValidateSizeLabelImageResolutionTooLow: '{{ __('filepond.imageValidateSizeLabelImageResolutionTooLow') }}',
            imageValidateSizeLabelImageResolutionTooHigh: '{{ __('filepond.imageValidateSizeLabelImageResolutionTooHigh') }}',
            imageValidateSizeLabelExpectedMinResolution: '{{ __('filepond.imageValidateSizeLabelExpectedMinResolution') }}',
            imageValidateSizeLabelExpectedMaxResolution: '{{ __('filepond.imageValidateSizeLabelExpectedMaxResolution') }}',
        });
    </script>
@endpush
