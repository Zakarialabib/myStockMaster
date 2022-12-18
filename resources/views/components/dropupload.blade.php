<div>
<div x-on:dragover="showDropzone" x-on:dragleave="hideDropzone" x-show="showDropzone" class="dropzone">
    <div class="dropzone-message" x-text="dropzoneMessage"></div>
    <div class="dropzone-files">
        <div class="dropzone-files-preview" x-for="(file, index) in files" :key="index">
            <div class="dropzone-files-preview-image" x-show="file.type === 'image'">
                <img :src="file.src" alt="">
            </div>
            <div class="dropzone-files-preview-progress" x-show="file.type !== 'image'">
                <div class="progress-bar" x-bind:style="{ width: (file.percentage || 0) + '%' }"></div>
            </div>
            <div class="dropzone-files-preview-remove" x-on:click="handleRemove(index)">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </div>
        </div>
    </div>
</div>

<div x-show="showPreview" class="preview-container">
    <div class="preview-images" x-for="(image, index) in images" :key="index">
        <img :src="image" alt="">
        <div class="preview-images-remove" x-on:click="handleRemove(index)">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </div>
    </div>
</div>
</div>

<script>
export default {
props: {
    file: {
        type: [String, Array],
        default: null
    }
},

data() {
    return {
        files: [],
        images: [],
        showDropzone: false,
        dropzoneMessage: 'Drop files here to upload',
        showPreview: false
    }
},

watch: {
    file() {
        if (this.file && Array.isArray(this.file)) {
            this.images = this.file;
            this.showPreview = true;
        } else {
            this.showPreview = false;
        }
    }
},

methods: {
    handleChange(e) {
        e.preventDefault();

        const files = e.target.files;
        if (!files.length) return;

        this.files = this.files.concat(Array.from(files).map(file => {
            return {
                type: file.type.split('/')[0],
                src: URL.createObjectURL(file),
                file,
                percentage: 0
            }
        }));
    },

    showDropzone(e) {
        e.preventDefault();
        this.showDropzone = true;
    },

    hideDropzone(e) {
        e.preventDefault();
        this.showDropzone = false;
    },

    handleRemove(index) {
        this.files.splice(index, 1);
    }
}
}
</script>

<style>
    .upload-container {
        width: 100%;
        height: auto;
        min-height: 200px;
        position: relative;
    }

    .dropzone {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #f0f0f0;
        color: #888;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        opacity: 0.75;
    }

    .dropzone-message {
        font-size: 1.2rem;
    }

    .dropzone-files {
        width: 100%;
        height: auto;
        min-height: 100px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 10px;
        margin-top: 10px;
    }

    .dropzone-files-preview {
        position: relative;
        width: 50px;
        height: 50px;
        margin: 5px;
        border-radius: 5px;
        background: #e0e0e0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .dropzone-files-preview-image {
        width: 100%;
        height: 100%;
        border-radius: 5px;
    }

    .dropzone-files-preview-progress {
        width: 50px;
        height: 50px;
        border-radius: 5px;
        background: #e0e0e0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .progress-bar {
        width: 0;
        height: 5px;
        background: #00b8d8;
        transition: width 0.2s ease;
    }

    .dropzone-files-preview-remove {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: #e0e0e0;
        border-radius: 50%;
        border: 1px solid #888;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .preview-container {
        width: 100%;
        height: auto;
        min-height: 200px;
        position: relative;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        padding: 10px;
    }

    .preview-images {
        position: relative;
        width: 50px;
        height: 50px;
        margin: 5px;
        border-radius: 5px;
    }

    .preview-images-remove {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 20px;
        height: 20px;
        background: #e0e0e0;
        border-radius: 50%;
        border: 1px solid #888;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>