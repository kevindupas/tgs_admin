import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/ralphjsmit/laravel-filament-media-library/resources/**/*.blade.php',
        './vendor/awcodes/filament-tiptap-editor/resources/**/*.blade.php',
    ],
    theme: {
        extend: {
            // Si besoin d'extensions
        },
    },
    safelist: [
        // Ajoutez ça pour vous assurer que les styles TipTap sont toujours inclus
        {
            pattern: /^(bg|text|border)-/,
            variants: ['hover', 'focus'],
        },
    ],
}