@props(['name', 'label' => '', 'value' => '', 'placeholder' => '', 'allowImages' => false])

@if ($label)
    <label class="block text-sm font-medium text-gray-800 mb-2">
        {{ $label }}
    </label>
@endif

<div id="toolbar-container" class="bg-[#f7f9fc] border border-b-0 rounded-t-md md:rounded-t-lg">
    <span class="ql-formats">
        <button class="ql-bold"></button>
        <button class="ql-italic"></button>
        <button class="ql-underline"></button>
        <button class="ql-list" value="ordered"></button>
        <button class="ql-list" value="bullet"></button>
        <button class="ql-link"></button>
        <button class="ql-clean"></button>
    </span>
</div>
<!-- Create the editor container -->
<div id="editor" style="font-family: Nunito !important"
    class="w-full h-[200px] bg-[#f7f9fc] border border-t-0 rounded-b-md md:rounded-b-lg">
</div>

<textarea name="{{ $name }}" hidden id="editorTextarea">{!! $value !!}</textarea>
@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/styles/github.min.css">

        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/highlight.js@11.9.0/lib/highlight.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

        <!-- Initialize Quill editor -->
        <script>
            const quill = new Quill('#editor', {
                modules: {
                    toolbar: '#toolbar-container',
                },
                placeholder: @json($placeholder),
                theme: 'snow',
            });

            quill.root.innerHTML = $("#editorTextarea").val();

            quill.on('text-change', function(delta, oldDelta, source) {
                $("#editorTextarea").val(quill.root.innerHTML);
            });
        </script>
    @endpush
@endonce
