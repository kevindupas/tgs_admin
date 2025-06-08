<?php

// app/TiptapExtensions/FontSize.php

namespace App\TiptapExtensions;

use Tiptap\Core\Extension;

class FontSize extends Extension
{
    public static $name = 'fontSize';

    public function addGlobalAttributes(): array
    {
        return [
            [
                'types' => ['textStyle'],
                'attributes' => [
                    'fontSize' => [
                        'default' => null,
                        'parseHTML' => function ($DOMNode) {
                            return $DOMNode->style->fontSize ?? null;
                        },
                        'renderHTML' => function ($attributes) {
                            if (! isset($attributes->fontSize) || ! $attributes->fontSize) {
                                return null;
                            }

                            return [
                                'style' => "font-size: {$attributes->fontSize}",
                            ];
                        },
                    ],
                ],
            ],
        ];
    }
}
