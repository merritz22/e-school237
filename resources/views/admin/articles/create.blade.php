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
                    <!-- Barre supérieure : label + import -->
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Contenu de l'article <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="file" id="html-file-import" accept=".html" class="hidden">
                            <button type="button" onclick="document.getElementById('html-file-import').click()"
                                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Importer .html
                            </button>
                            <span id="import-filename" class="text-xs text-gray-400 italic"></span>
                        </div>
                    </div>

                    <!-- Onglets -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        
                        <!-- Tab Headers -->
                        <div class="flex border-b border-gray-300 bg-gray-50">
                            <button type="button" id="tab-visual"
                                    onclick="switchTab('visual')"
                                    class="tab-btn active-tab px-4 py-2.5 text-sm font-medium flex items-center gap-1.5 border-b-2 border-blue-500 text-blue-600">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Visuel
                            </button>
                            <button type="button" id="tab-html"
                                    onclick="switchTab('html')"
                                    class="tab-btn px-4 py-2.5 text-sm font-medium flex items-center gap-1.5 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                </svg>
                                HTML source
                            </button>
                            <button type="button" id="tab-preview"
                                    onclick="switchTab('preview')"
                                    class="tab-btn px-4 py-2.5 text-sm font-medium flex items-center gap-1.5 border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Aperçu
                            </button>

                            <!-- Indicateur de synchro -->
                            <div class="ml-auto flex items-center pr-3">
                                <span id="sync-indicator" class="text-xs text-gray-400 italic hidden">
                                    ⟳ synchronisation...
                                </span>
                            </div>
                        </div>

                        <!-- Onglet 1 : Éditeur visuel Summernote -->
                        <div id="panel-visual" class="tab-panel">
                            <textarea name="content" id="content" rows="20" required
                                    class="@error('content') border-red-500 @enderror hidden"></textarea>
                        </div>

                        <!-- Onglet 2 : Éditeur HTML CodeMirror -->
                        <div id="panel-html" class="tab-panel hidden">
                            <div class="flex items-center justify-between px-3 py-2 bg-gray-900 border-b border-gray-700">
                                <span class="text-xs text-gray-400 font-mono">HTML / CSS</span>
                                <div class="flex gap-2">
                                    <button type="button" onclick="formatHtml()"
                                            class="text-xs text-gray-400 hover:text-white px-2 py-1 rounded bg-gray-700 hover:bg-gray-600 transition">
                                        ✨ Formater
                                    </button>
                                    <button type="button" onclick="syncFromHtmlEditor()"
                                            class="text-xs text-gray-400 hover:text-white px-2 py-1 rounded bg-gray-700 hover:bg-gray-600 transition">
                                        ↺ Sync visuel
                                    </button>
                                </div>
                            </div>
                            <div id="codemirror-wrapper"></div>
                        </div>

                        <!-- Onglet 3 : Aperçu iframe -->
                        <div id="panel-preview" class="tab-panel hidden">
                            <div class="flex items-center justify-between px-3 py-2 bg-gray-50 border-b border-gray-200">
                                <span class="text-xs text-gray-500">Rendu final (styles inclus)</span>
                                <button type="button" onclick="refreshPreview()"
                                        class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                    ↻ Actualiser
                                </button>
                            </div>
                            <iframe id="preview-iframe"
                                    class="w-full bg-white"
                                    style="height: 600px; border: none;">
                            </iframe>
                        </div>
                    </div>

                    @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
                        {{-- <button type="submit" 
                                name="action" 
                                value="save"
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            Enregistrer l'article
                        </button> --}}
                        
                        <button type="submit" 
                                name="action" 
                                value="save_and_continue"
                                class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Enregistrer
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/theme/dracula.min.css" rel="stylesheet">
<style>
    /* Summernote */
    .note-editor.note-frame {
        border: none !important;
        border-radius: 0 !important;
    }
    .note-editor.note-frame .note-statusbar {
        background-color: #f3f4f6 !important;
        border-top: 1px solid #d1d5db !important;
    }
    .note-toolbar { border-bottom: 1px solid #d1d5db !important; }

    /* Onglets */
    .tab-btn { transition: all 0.15s; }
    .active-tab { border-bottom-color: #3b82f6 !important; color: #2563eb !important; background: white; }

    /* CodeMirror */
    .CodeMirror {
        height: 550px !important;
        font-family: 'JetBrains Mono', 'Fira Code', monospace !important;
        font-size: 13px !important;
        line-height: 1.7 !important;
    }
    .CodeMirror-scroll { padding-bottom: 20px; }
</style>
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.16/mode/htmlmixed/htmlmixed.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ═══════════════════════════════════════════════
        // ÉTAT GLOBAL
        // ═══════════════════════════════════════════════
        let htmlEditor = null;   // instance CodeMirror
        let currentTab = 'visual';
        let syncTimeout = null;

        // ═══════════════════════════════════════════════
        // 1. SUMMERNOTE
        // ═══════════════════════════════════════════════
        $('#content').summernote({
            height: 500,
            toolbar: [
                ['style',    ['style']],
                ['font',     ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color',    ['color']],
                ['para',     ['ul', 'ol', 'paragraph']],
                ['table',    ['table']],
                ['insert',   ['link', 'picture', 'video']],
                ['view',     ['codeview', 'help']]
            ],
            callbacks: {
                onChange: function (contents) {
                    updateWordCount(contents);
                }
            }
        });

        // ═══════════════════════════════════════════════
        // 2. CODEMIRROR
        // ═══════════════════════════════════════════════
        htmlEditor = CodeMirror(document.getElementById('codemirror-wrapper'), {
            value: $('#content').summernote('code') || '',
            mode: 'htmlmixed',
            theme: 'dracula',
            lineNumbers: true,
            lineWrapping: true,
            autoCloseTags: true,
            foldGutter: true,
            tabSize: 2,
            indentWithTabs: false,
            extraKeys: {
                'Ctrl-S': function () { document.getElementById('article-form').requestSubmit(); },
                'Ctrl-Space': 'autocomplete'
            }
        });

        // Sync CodeMirror → textarea (pour le submit)
        htmlEditor.on('change', function () {
            showSyncIndicator();
        });

        // ═══════════════════════════════════════════════
        // 3. GESTION DES ONGLETS
        // ═══════════════════════════════════════════════
        window.switchTab = function (tab) {
            // Quitter l'onglet courant : sync vers CodeMirror si on quitte le visuel
            if (currentTab === 'visual' && tab !== 'visual') {
                const visualHtml = $('#content').summernote('code');
                htmlEditor.setValue(visualHtml);
            }
            // Quitter HTML : sync vers Summernote
            if (currentTab === 'html' && tab === 'visual') {
                syncFromHtmlEditor();
            }
            // Passer en aperçu : rafraîchir
            if (tab === 'preview') {
                refreshPreview();
            }

            // Afficher/masquer les panneaux
            ['visual', 'html', 'preview'].forEach(t => {
                document.getElementById('panel-' + t).classList.add('hidden');
                const btn = document.getElementById('tab-' + t);
                btn.classList.remove('active-tab');
                btn.style.borderBottomColor = 'transparent';
                btn.style.color = '';
            });

            document.getElementById('panel-' + tab).classList.remove('hidden');
            document.getElementById('tab-' + tab).classList.add('active-tab');

            if (tab === 'html') htmlEditor.refresh();

            currentTab = tab;
        };

        // Sync HTML editor → Summernote
        window.syncFromHtmlEditor = function () {
            const code = htmlEditor.getValue();
            // Extraire le body si c'est un HTML complet
            const bodyMatch = code.match(/<body[^>]*>([\s\S]*?)<\/body>/i);
            const bodyContent = bodyMatch ? bodyMatch[1] : code;
            $('#content').summernote('code', bodyContent);
            showSyncIndicator('✔ Synchronisé');
        };

        // Aperçu dans l'iframe
        window.refreshPreview = function () {
            const rawHtml = currentTab === 'html'
                ? htmlEditor.getValue()
                : `<!DOCTYPE html><html><head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width,initial-scale=1">
                </head><body>${$('#content').summernote('code')}</body></html>`;

            const iframe = document.getElementById('preview-iframe');

            // Révoquer l'ancien blob URL si existant
            if (iframe.dataset.blobUrl) {
                URL.revokeObjectURL(iframe.dataset.blobUrl);
            }

            const blob = new Blob([rawHtml], { type: 'text/html; charset=utf-8' });
            const blobUrl = URL.createObjectURL(blob);

            iframe.dataset.blobUrl = blobUrl;
            iframe.src = blobUrl;
        };

        // Formater le HTML (indentation simple)
        window.formatHtml = function () {
            const raw = htmlEditor.getValue();
            try {
                // Formatage basique par regex (pas de dépendance externe)
                let depth = 0;
                const formatted = raw
                    .replace(/>\s*</g, '>\n<')
                    .split('\n')
                    .map(line => {
                        line = line.trim();
                        if (!line) return '';
                        if (line.match(/^<\/[^>]+>/)) depth = Math.max(0, depth - 1);
                        const indent = '  '.repeat(depth);
                        if (line.match(/^<[^/!][^>]*[^/]>$/) && !line.match(/<.*<\//)) depth++;
                        return indent + line;
                    })
                    .filter(l => l !== '')
                    .join('\n');
                htmlEditor.setValue(formatted);
            } catch (e) {
                console.warn('Formatage impossible', e);
            }
        };

        function showSyncIndicator(msg) {
            const el = document.getElementById('sync-indicator');
            el.textContent = msg || '⟳ synchronisation...';
            el.classList.remove('hidden');
            clearTimeout(syncTimeout);
            syncTimeout = setTimeout(() => el.classList.add('hidden'), 1500);
        }

        // ═══════════════════════════════════════════════
        // 4. IMPORT FICHIER HTML
        // ═══════════════════════════════════════════════
        document.getElementById('html-file-import').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                const fullHtml = event.target.result;

                // 1. CodeMirror reçoit le HTML complet (avec <style>)
                htmlEditor.setValue(fullHtml);

                // 2. Summernote reçoit uniquement le <body>
                const parser = new DOMParser();
                const doc = parser.parseFromString(fullHtml, 'text/html');
                $('#content').summernote('code', doc.body.innerHTML);

                // 3. Basculer vers l'aperçu pour voir le résultat
                switchTab('preview');

                // 4. Afficher le nom du fichier
                document.getElementById('import-filename').textContent = '✔ ' + file.name;
                updateWordCount(doc.body.innerHTML);

                e.target.value = '';
            };
            reader.readAsText(file, 'UTF-8');
        });

        // ═══════════════════════════════════════════════
        // 5. SYNC AVANT SUBMIT (important !)
        // ═══════════════════════════════════════════════
        const articleForm = document.getElementById('article-form');
        if (articleForm) {
            articleForm.addEventListener('submit', function () {
                if (currentTab === 'html') {
                    $('#content').summernote('destroy');
                    document.getElementById('content').value = htmlEditor.getValue();
                }
            });
        }

        // ═══════════════════════════════════════════════
        // 6. UTILITAIRES (inchangés)
        // ═══════════════════════════════════════════════
        function updateWordCount(content) {
            const text = $('<div>').html(content).text();
            const words = text.trim().split(/\s+/).filter(w => w.length > 0).length;
            const readingTime = Math.max(1, Math.ceil(words / 200));

            const wcEl = document.getElementById('word-count');
            const rtEl = document.getElementById('reading-time');
            if (wcEl) wcEl.textContent = words;
            if (rtEl) rtEl.textContent = readingTime + ' min';
        }

        document.getElementById('title').addEventListener('input', function () {
            document.getElementById('slug').value = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
        });

        // Gestion modals (inchangée)
        window.showModal = function (id) {
            document.getElementById(id).classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        };
        window.hideModal = function (id) {
            document.getElementById(id).classList.add('hidden');
            document.body.style.overflow = 'auto';
        };
        window.confirmDelete    = () => showModal('delete-modal');
        window.duplicateArticle = () => showModal('duplicate-modal');
        window.publishArticle   = () => showModal('publish-modal');
        window.unpublishArticle = () => showModal('unpublish-modal');

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape')
                document.querySelectorAll('[id$="-modal"]').forEach(m => hideModal(m.id));
        });
        document.querySelectorAll('[id$="-modal"]').forEach(m => {
            m.addEventListener('click', e => { if (e.target === m) hideModal(m.id); });
        });

        // Image upload (inchangé)
        const featuredImageInput = document.getElementById('featured_image');
        const imageDropzone      = document.getElementById('image-dropzone');
        const imagePreview       = document.getElementById('image-preview');
        const previewImg         = document.getElementById('preview-img');

        if (featuredImageInput) {
            featuredImageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = e => {
                    if (previewImg) previewImg.src = e.target.result;
                    if (imagePreview) imagePreview.classList.remove('hidden');
                    if (imageDropzone) imageDropzone.classList.add('border-green-400', 'bg-green-50');
                };
                reader.readAsDataURL(file);
            });
        }

        if (imageDropzone) {
            imageDropzone.addEventListener('click', () => featuredImageInput?.click());

            imageDropzone.addEventListener('dragover', e => {
                e.preventDefault();
                imageDropzone.classList.add('border-blue-400', 'bg-blue-50');
            });

            imageDropzone.addEventListener('dragleave', e => {
                if (!imageDropzone.contains(e.relatedTarget))
                    imageDropzone.classList.remove('border-blue-400', 'bg-blue-50');
            });

            imageDropzone.addEventListener('drop', e => {
                e.preventDefault();
                imageDropzone.classList.remove('border-blue-400', 'bg-blue-50');
                const files = e.dataTransfer.files;
                if (files[0]?.type.match('image.*')) {
                    featuredImageInput.files = files;
                    const reader = new FileReader();
                    reader.onload = ev => {
                        if (previewImg) previewImg.src = ev.target.result;
                        if (imagePreview) imagePreview.classList.remove('hidden');
                        imageDropzone.classList.add('border-green-400', 'bg-green-50');
                    };
                    reader.readAsDataURL(files[0]);
                }
            });
        }
        const removeImageBtn = document.getElementById('remove-image');
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', () => {
                featuredImageInput.value = '';
                previewImg.src = '';
                imagePreview.classList.add('hidden');
                imageDropzone.classList.remove('border-green-400', 'bg-green-50');
            });
        }

        // Word count initial
        updateWordCount($('#content').summernote('code'));
    });
    </script>
@endpush
@endsection
