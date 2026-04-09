<?php

namespace common\helpers;

use yii\helpers\Html;

class EditorJsHelper
{
    public static function encodeInitialData(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return json_encode(['blocks' => []], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }

        return json_encode([
            'blocks' => [
                [
                    'type' => 'paragraph',
                    'data' => [
                        'text' => $value,
                    ],
                ],
            ],
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function render(?string $value): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        $decoded = json_decode($value, true);
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
            return Html::tag('div', $value, ['class' => 'editorjs-content']);
        }

        $blocks = $decoded['blocks'] ?? [];
        if (!is_array($blocks)) {
            return '';
        }

        $html = [];
        foreach ($blocks as $block) {
            if (!is_array($block)) {
                continue;
            }

            $type = $block['type'] ?? '';
            $data = is_array($block['data'] ?? null) ? $block['data'] : [];

            switch ($type) {
                case 'header':
                    $level = (int) ($data['level'] ?? 2);
                    $level = max(1, min(6, $level));
                    $html[] = Html::tag('h' . $level, $data['text'] ?? '', ['class' => 'editorjs-header']);
                    break;
                case 'image':
                    $imageUrl = trim((string) (($data['file']['url'] ?? $data['url'] ?? '')));
                    if ($imageUrl === '') {
                        break;
                    }

                    $caption = trim((string) ($data['caption'] ?? ''));
                    $imageTag = Html::img($imageUrl, [
                        'alt' => $caption,
                        'class' => 'img-fluid rounded',
                    ]);
                    if ($caption !== '') {
                        $imageTag .= Html::tag('div', Html::encode($caption), ['class' => 'text-muted mt-2']);
                    }
                    $html[] = Html::tag('figure', $imageTag, ['class' => 'editorjs-image']);
                    break;
                case 'embed':
                    $service = trim((string) ($data['service'] ?? ''));
                    $embedUrl = trim((string) ($data['embed'] ?? ''));
                    if ($service !== 'youtube' || $embedUrl === '') {
                        break;
                    }

                    $iframe = Html::tag('iframe', '', [
                        'src' => $embedUrl,
                        'allowfullscreen' => true,
                        'frameborder' => '0',
                        'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
                        'style' => 'width:100%; aspect-ratio:16/9;',
                    ]);
                    $html[] = Html::tag('div', $iframe, ['class' => 'editorjs-embed mb-3']);
                    break;
                case 'youtube':
                    $videoUrl = trim((string) ($data['url'] ?? ''));
                    $videoId = self::extractYoutubeVideoId($videoUrl);
                    if ($videoId === null) {
                        break;
                    }

                    $iframe = Html::tag('iframe', '', [
                        'src' => 'https://www.youtube.com/embed/' . $videoId,
                        'allowfullscreen' => true,
                        'frameborder' => '0',
                        'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
                        'style' => 'width:100%; aspect-ratio:16/9;',
                    ]);
                    $html[] = Html::tag('div', $iframe, ['class' => 'editorjs-embed mb-3']);
                    break;
                case 'list':
                    $style = ($data['style'] ?? 'unordered') === 'ordered' ? 'ol' : 'ul';
                    $items = [];
                    foreach (($data['items'] ?? []) as $item) {
                        $items[] = Html::tag('li', (string) $item);
                    }
                    if ($items !== []) {
                        $html[] = Html::tag($style, implode('', $items), ['class' => 'editorjs-list']);
                    }
                    break;
                case 'quote':
                    $text = Html::tag('p', $data['text'] ?? '');
                    $caption = trim((string) ($data['caption'] ?? ''));
                    if ($caption !== '') {
                        $text .= Html::tag('footer', Html::encode($caption));
                    }
                    $html[] = Html::tag('blockquote', $text, ['class' => 'editorjs-quote']);
                    break;
                case 'delimiter':
                    $html[] = Html::tag('hr', '', ['class' => 'editorjs-delimiter']);
                    break;
                case 'paragraph':
                default:
                    $html[] = Html::tag('p', $data['text'] ?? '', ['class' => 'editorjs-paragraph']);
                    break;
            }
        }

        return Html::tag('div', implode("\n", $html), ['class' => 'editorjs-content']);
    }

    private static function extractYoutubeVideoId(string $url): ?string
    {
        if ($url === '') {
            return null;
        }

        if (!preg_match('/^.*(?:youtu\.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/', $url, $matches)) {
            return null;
        }

        return isset($matches[1]) && strlen($matches[1]) === 11 ? $matches[1] : null;
    }
}
