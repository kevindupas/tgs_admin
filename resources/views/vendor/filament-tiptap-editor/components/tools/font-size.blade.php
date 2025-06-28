{{-- resources/views/vendor/filament-tiptap-editor/components/tools/font-size.blade.php --}}

<div class="relative" x-data="{
    open: false,
    currentSize: '16',
    savedSelection: null,
    pollingInterval: null,
    lastSelection: null,
    getCurrentSize() {
        const ed = editor();
        if (!ed) return;

        const { from, to } = ed.state.selection;

        // Cas 1: Du texte est sélectionné
        if (from !== to) {
            let foundSize = null;

            ed.state.doc.nodesBetween(from, to, (node) => {
                if (foundSize) return false;

                if (node.marks) {
                    for (const mark of node.marks) {
                        if (mark.type.name === 'textStyle' && mark.attrs.style) {
                            const match = mark.attrs.style.match(/font-size:\s*([^;]+)/);
                            if (match) {
                                foundSize = match[1].trim().replace('px', '');
                                break;
                            }
                        }
                    }
                }
            });

            const newSize = foundSize || '16';
            if (this.currentSize !== newSize) {
                this.currentSize = newSize;
                this.$dispatch('font-size-updated', { size: newSize });
            }
            return;
        }

        // Cas 2: Curseur positionné (pas de sélection)
        else {
            // Méthode 1: getAttributes (API TipTap)
            try {
                const attrs = ed.getAttributes('textStyle');
                if (attrs && attrs.style) {
                    const match = attrs.style.match(/font-size:\s*([^;]+)/);
                    if (match) {
                        const size = match[1].trim().replace('px', '');
                        if (this.currentSize !== size) {
                            this.currentSize = size;
                            this.$dispatch('font-size-updated', { size: size });
                        }
                        return;
                    }
                }
            } catch (e) {}

            // Méthode 2: Analyser le caractère précédent
            if (from > 0) {
                try {
                    const prevPos = ed.state.doc.resolve(from - 1);
                    const prevMarks = prevPos.marks();

                    for (const mark of prevMarks) {
                        if (mark.type.name === 'textStyle' && mark.attrs.style) {
                            const match = mark.attrs.style.match(/font-size:\s*([^;]+)/);
                            if (match) {
                                const size = match[1].trim().replace('px', '');
                                if (this.currentSize !== size) {
                                    this.currentSize = size;
                                    this.$dispatch('font-size-updated', { size: size });
                                }
                                return;
                            }
                        }
                    }
                } catch (e) {}
            }

            // Méthode 3: Stored marks
            try {
                const storedMarks = ed.state.storedMarks;
                if (storedMarks) {
                    for (const mark of storedMarks) {
                        if (mark.type.name === 'textStyle' && mark.attrs.style) {
                            const match = mark.attrs.style.match(/font-size:\s*([^;]+)/);
                            if (match) {
                                const size = match[1].trim().replace('px', '');
                                if (this.currentSize !== size) {
                                    this.currentSize = size;
                                    this.$dispatch('font-size-updated', { size: size });
                                }
                                return;
                            }
                        }
                    }
                }
            } catch (e) {}

            // Aucune méthode n'a fonctionné, taille par défaut
            if (this.currentSize !== '16') {
                this.currentSize = '16';
                this.$dispatch('font-size-updated', { size: '16' });
            }
        }
    },
    applyFontSize(size) {
        const ed = editor();
        if (!ed) return;

        // Obtenir les attributs de style actuels
        const currentAttrs = ed.getAttributes('textStyle');
        let newStyle = `font-size: ${size}`;

        // Si il y a déjà des styles, les préserver
        if (currentAttrs && currentAttrs.style) {
            // Supprimer l'ancienne font-size s'il y en a une
            let existingStyle = currentAttrs.style.replace(/font-size:\s*[^;]+;?/g, '').trim();

            // Nettoyer les points-virgules en trop
            existingStyle = existingStyle.replace(/;+/g, ';').replace(/^;|;$/g, '');

            // Combiner avec la nouvelle taille
            if (existingStyle) {
                newStyle = `${existingStyle}; font-size: ${size}`;
            }
        }

        // Appliquer le nouveau style
        ed.chain().focus().setMark('textStyle', { style: newStyle }).run();
    },
    startPolling() {
        // Arrêter l'ancien polling s'il existe
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
        }

        // Démarrer un nouveau polling toutes les 100ms
        this.pollingInterval = setInterval(() => {
            const ed = editor();
            if (ed) {
                const { from, to } = ed.state.selection;
                const currentSelection = `${from}-${to}`;

                // Vérifier seulement si la sélection a changé
                if (this.lastSelection !== currentSelection) {
                    this.lastSelection = currentSelection;
                    this.getCurrentSize();
                }
            }
        }, 100);
    },
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
        }
    },
    openDropdown() {
        const ed = editor();
        if (ed) {
            // Sauvegarder la sélection actuelle
            this.savedSelection = {
                from: ed.state.selection.from,
                to: ed.state.selection.to,
                empty: ed.state.selection.empty
            };
        }
        this.open = true;
    },
    closeDropdown() {
        this.open = false;
        // Remettre le focus sur l'éditeur après fermeture
        setTimeout(() => {
            const ed = editor();
            if (ed) {
                ed.commands.focus();
            }
        }, 10);
    }
}" x-init="getCurrentSize();
startPolling();
// Nettoyer quand le composant est détruit
$watch('$el', () => {
    return () => stopPolling();
});"
    @font-size-updated.window="currentSize = $event.detail.size">

    <!-- Select input style Word -->
    <div @mousedown.prevent @click="openDropdown()"
        class="flex items-center justify-between min-w-[60px] px-1.5 py-1 text-sm border border-gray-300 rounded cursor-pointer hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-200">
        <span x-text="currentSize" class="font-mono text-xs"></span>
        <svg class="w-3 h-3 ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>

    <div x-show="open" @click.away="closeDropdown()" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute z-50 mt-1 w-32 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:ring-gray-700"
        style="display: none;" @mousedown.prevent>
        <div class="py-1 max-h-48 overflow-y-auto">
            @php
                $sizes = ['8px', '10px', '12px', '14px', '16px', '18px', '24px', '30px', '36px', '48px'];
            @endphp

            @foreach ($sizes as $size)
                @php
                    $sizeValue = (int) str_replace('px', '', $size);
                    $displaySize = (string) $sizeValue;
                @endphp
                <button type="button" @mousedown.prevent
                    x-on:click="
                        // Nouvelle méthode qui préserve les styles existants
                        applyFontSize('{{ $size }}');
                        
                        // Mettre à jour la taille affichée (sans px) et diffuser
                        currentSize = '{{ $displaySize }}';
                        $dispatch('font-size-updated', { size: '{{ $displaySize }}' });
                        
                        // Fermer le dropdown et remettre le focus
                        open = false;
                        $nextTick(() => {
                            const ed = editor();
                            if (ed) {
                                ed.commands.focus();
                            }
                            // Forcer une mise à jour après application
                            setTimeout(() => getCurrentSize(), 50);
                        });
                    "
                    class="flex items-center justify-between w-full text-left px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700"
                    :class="currentSize === '{{ $displaySize }}' ?
                        'bg-blue-50 text-blue-700 dark:bg-blue-900 dark:text-blue-200' :
                        'text-gray-700 dark:text-gray-200'">
                    <span class="font-mono">{{ $displaySize }}</span>
                    <span x-show="currentSize === '{{ $displaySize }}'"
                        class="text-blue-600 dark:text-blue-400">✓</span>
                </button>
            @endforeach

            <div class="border-t border-gray-100 dark:border-gray-600 my-1"></div>

            <button type="button" x-on:click="closeDropdown()"
                class="block w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white">
                Annuler
            </button>
        </div>
    </div>
</div>
