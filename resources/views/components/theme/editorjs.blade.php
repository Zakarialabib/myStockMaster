<div wire:ignore>
    <div id="editorjs"></div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.23.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.23.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.23.0"></script>
    <script>
        var editor = new EditorJS({
            holder: 'editorjs',
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: true,
                },
                image: {
                    class: ImageTool,
                    config: {
                        endpoints: {
                            byFile: '/api/image-upload',
                            byUrl: '/api/image-upload-by-url',
                        },
                    },
                },
                block: {
                    class: BlockTool,
                },
            },
            data: {
                blocks: [],
            },
            onChange: function() {
                Livewire.emit('editorChanged', this);
            },
        });

        Livewire.on('setContent', (content) => {
            editor.blocks.render(content);
        });
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.23.0@/dist/editor.css">
@endpush

@push('styles')
    <style>
        .ce-header__toolbar {
            display: none !important;
        }
    </style>
@endpush
