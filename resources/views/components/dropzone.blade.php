<div wire:ignore>
    <div class="dropzone" {{ $attributes }}></div>
</div>

@push('scripts')
    <script>
        Dropzone.options[_.camelCase("{{ $attributes['id'] }}")] = {
    url: "{{ $attributes['action'] }}",
    maxFilesize: {{ $attributes['max-file-size'] ?? 2 }},
    maxFiles: {{ $attributes['max-files'] ?? 'null' }},
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
@if($attributes['max-width'])
      max_width: {{ $attributes['max-width'] }},
@endif
@if($attributes['max-height'])
      max_height: {{ $attributes['max-height'] }},
@endif
      size: {{ $attributes['max-file-size'] ?? 2 }},
      model_id: {{ $attributes['model-id'] ?? 0 }},
      collection_name: "{{ $attributes['collection-name'] ?? 'default' }}"
    },
    success: function (file, response) {
@this.addMedia(response.media)
    },
    removedfile: function (file) {
        file.previewElement.remove()

        if (file.status === 'error') {
            return;
        }

        if (file.xhr) {
            var response = JSON.parse(file.xhr.response)
@this.removeMedia(response.media)
        } else {
@this.removeMedia(file)
        }
    },
    init: function () {
        document.addEventListener("livewire:load", () => {
            let files = @this.mediaCollections["{{ $attributes['collection-name'] ?? 'default' }}"]
            if (files !== undefined && files.length) {
                files.forEach(file => {
                    let fileClone = JSON.parse(JSON.stringify(file))
                    this.files.push(fileClone)
                    this.emit("addedfile", fileClone)

                    if (fileClone.preview_thumbnail !== undefined) {
                        this.emit("thumbnail", fileClone, fileClone.preview_thumbnail)
                    } else {
                        this.emit("thumbnail", fileClone, fileClone.url)
                    }

                    this.emit("complete", fileClone)
                    if (this.options.maxFiles !== null) {
                        this.options.maxFiles--
                    }
                })
            }
        });
    },
    error: function (file, response) {
        file.previewElement.classList.add('dz-error')
        let message = $.type(response) === 'string' ? response : response.errors.file
        return _.map(file.previewElement.querySelectorAll('[data-dz-errormessage]'), r => r.textContent = message)
    }
}
    </script>
@endpush