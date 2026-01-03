@extends('layouts.admin')

@section('title', 'Créer un article')

@section('content')
<div class="bg-white p-5">
    
    
    <!-- Header -->
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Créer un article</h1>
                <p class="text-gray-600 mt-1">Rédigez un nouvel article pour votre blog</p>
            </div>
            
            <a href="{{ route('admin.articles.index') }}" 
               class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour
            </a>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Publish Settings -->
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Publication</h3>
            
            <div class="space-y-4">
                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('status') border-red-500 @enderror">
                        <option value="draft" @if(old('status', 'draft') === 'draft') selected @endif>Brouillon</option>
                        <option value="published" @if(old('status') === 'published') selected @endif>Publié</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Published At -->
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                        Date de publication
                    </label>
                    <input type="datetime-local" 
                            id="published_at" 
                            name="published_at" 
                            value="{{ old('published_at') }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('published_at') border-red-500 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Laissez vide pour utiliser le titre de l'article</p>
                </div>

                <div>
                    <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description SEO
                    </label>
                    <textarea id="meta_description" 
                                name="meta_description" 
                                rows="2"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Description pour les moteurs de recherche">{{ old('meta_description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Laissez vide pour utiliser l'extrait</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de l'article <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           value="{{ old('title') }}"
                           required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                           placeholder="Entrez le titre de votre article">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                        Extrait (résumé)
                    </label>
                    <textarea id="excerpt" 
                              name="excerpt" 
                              rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('excerpt') border-red-500 @enderror"
                              placeholder="Un court résumé de votre article (optionnel)">{{ old('excerpt') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Laissez vide pour générer automatiquement depuis le contenu</p>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Content -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                        Contenu de l'article <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" 
                              name="content" 
                              rows="20"
                              required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                              placeholder="Rédigez le contenu de votre article...">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SEO Section -->
                <div class="bg-gray-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Optimisation SEO</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Titre SEO
                            </label>
                            <input type="text" 
                                   id="meta_title" 
                                   name="meta_title" 
                                   value="{{ old('meta_title') }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Titre pour les moteurs de recherche">
                            <p class="mt-1 text-sm text-gray-500">Laissez vide pour publier immédiatement</p>
                            @error('published_at')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Matière -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Matière</h3>
                    
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Matière <span class="text-red-500">*</span>
                        </label>
                        <select id="subject_id" 
                                name="subject_id" 
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('subject_id') border-red-500 @enderror">
                            <option value="">Sélectionnez une matière</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" 
                                        @if(old('subject_id') == $subject->id) selected @endif>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Featured Image -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Image à la une</h3>
                    
                    <div>
                        <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Image
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="featured_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Télécharger une image</span>
                                        <input id="featured_image" 
                                               name="featured_image" 
                                               type="file" 
                                               accept="image/*"
                                               class="sr-only">
                                    </label>
                                    <p class="pl-1">ou glisser-déposer</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP jusqu'à 2MB</p>
                            </div>
                        </div>
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Preview -->
                    <div id="image-preview" class="mt-4 hidden">
                        <img id="preview-img" src="" alt="Aperçu" class="w-full h-32 object-cover rounded-lg">
                        <button type="button" 
                                id="remove-image" 
                                class="mt-2 text-red-600 text-sm hover:text-red-800">
                            Supprimer l'image
                        </button>
                    </div>
                </div>

                <!-- Tags -->
                {{-- <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                    
                    <div>
                        <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                            Sélectionner des tags
                        </label>
                        <select id="tags" 
                                name="tags[]" 
                                multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @foreach(\App\Models\Tag::orderBy('name')->get() as $tag)
                                <option value="{{ $tag->id }}" 
                                        @if(in_array($tag->id, old('tags', []))) selected @endif>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-sm text-gray-500">Maintenez Ctrl (Cmd sur Mac) pour sélectionner plusieurs tags</p>
                        @error('tags')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> --}}

                <!-- Actions -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex flex-col space-y-3">
                        <button type="submit" 
                                name="action" 
                                value="save"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Enregistrer l'article
                        </button>
                        
                        <button type="submit" 
                                name="action" 
                                value="save_and_continue"
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Enregistrer et continuer l'édition
                        </button>
                        
                        <a href="{{ route('admin.articles.index') }}" 
                           class="w-full bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-center">
                            Annuler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Summernote editor
    $('#content').summernote({
        height: 400,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ],
        placeholder: 'Rédigez le contenu de votre article...',
        callbacks: {
            onImageUpload: function(files) {
                uploadImage(files[0]);
            }
        }
    });

    // Auto-generate slug from title
    $('#title').on('input', function() {
        const title = $(this).val();
        // You could add slug generation logic here if needed
    });

    // Image upload preview
    $('#featured_image').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-img').attr('src', e.target.result);
                $('#image-preview').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove image
    $('#remove-image').on('click', function() {
        $('#featured_image').val('');
        $('#image-preview').addClass('hidden');
        $('#preview-img').attr('src', '');
    });

    // Form submission handling
    $('form').on('submit', function(e) {
        const action = e.originalEvent.submitter.value;
        
        if (action === 'save_and_continue') {
            // Add a hidden input to know we want to redirect to edit
            $('<input>').attr({
                type: 'hidden',
                name: 'redirect_to_edit',
                value: '1'
            }).appendTo(this);
        }
    });

    // Auto-save draft functionality
    let autoSaveTimeout;
    $('#title, #content, #excerpt').on('input', function() {
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            autoSaveDraft();
        }, 30000); // Auto-save after 30 seconds of inactivity
    });
});

function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    $.ajax({
        url: ' route("admin.upload.image") ',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $('#content').summernote('insertImage', response.url);
        },
        error: function() {
            alert('Erreur lors du téléchargement de l\'image');
        }
    });
}

function autoSaveDraft() {
    const formData = new FormData($('form')[0]);
    formData.append('auto_save', '1');
    
    $.ajax({
        url: ' route("admin.articles.auto-save") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            // Show success indicator
            showAutoSaveIndicator('Brouillon sauvegardé automatiquement');
        },
        error: function() {
            // Show error indicator
            showAutoSaveIndicator('Erreur de sauvegarde automatique', 'error');
        }
    });
}

function showAutoSaveIndicator(message, type = 'success') {
    const indicator = $(`
        <div class="fixed top-4 right-4 z-50 px-4 py-2 rounded-lg text-white text-sm ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}">
            ${message}
        </div>
    `);
    
    $('body').append(indicator);
    
    setTimeout(function() {
        indicator.fadeOut(300, function() {
            $(this).remove();
        });
    }, 3000);
}
</script>
@endpush
@endsection
