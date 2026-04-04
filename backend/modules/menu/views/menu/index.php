<?php

use common\models\Menu;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\jui\JuiAsset;

JuiAsset::register($this);

$this->title = 'Menu';
$this->params['breadcrumbs'][] = $this->title;

$grouped = [];
foreach ($menus as $menu) {
    $sectionId = (int) $menu->section_id;
    $parentId = $menu->parent_id === null ? 0 : (int) $menu->parent_id;
    $grouped[$sectionId][$parentId][] = $menu;
}

$renderTree = function ($sectionId, $parentId, $depth) use (&$renderTree, $grouped) {
    $items = $grouped[$sectionId][$parentId] ?? [];
    if ($depth > 3) {
        return '';
    }

    $html = '<ol class="menu-tree" data-section="' . (int) $sectionId . '" data-depth="' . (int) $depth . '" data-parent="' . ($parentId ?: '') . '">';
    foreach ($items as $item) {
        $html .= '<li class="menu-node" data-id="' . (int) $item->id . '">';
        $html .= '<div class="menu-node__card">';
        $html .= '<div class="menu-node__main">';
        $html .= '<span class="menu-node__handle"><i class="fas fa-grip-vertical"></i></span>';
        $html .= '<div class="menu-node__meta">';
        $html .= '<div class="menu-node__title">' . Html::encode($item->getDisplayTitle()) . '</div>';
        $html .= '<div class="menu-node__sub">#' . (int) $item->id . ' | ' . Html::encode($item->url) . ' | ' . ($item->show_in_menu ? 'Show' : 'Hidden') . '</div>';
        $html .= '</div></div>';
        $html .= '<div class="menu-node__actions">';
        $html .= Html::a('View', ['view', 'id' => $item->id], ['class' => 'btn btn-info btn-sm mr-1']);
        $html .= Html::a('Edit', ['update', 'id' => $item->id], ['class' => 'btn btn-success btn-sm mr-1']);
        $html .= Html::a('Remove', ['delete', 'id' => $item->id], ['class' => 'btn btn-danger btn-sm', 'data-method' => 'post', 'data-confirm' => 'Are you sure you want to delete this item?']);
        $html .= '</div></div>';
        if ($depth < 3) {
            $childTree = $renderTree($sectionId, (int) $item->id, $depth + 1);
            $html .= $childTree !== '' ? $childTree : '<ol class="menu-tree menu-tree--child-empty" data-section="' . (int) $sectionId . '" data-depth="' . (int) ($depth + 1) . '" data-parent="' . (int) $item->id . '"></ol>';
        }
        $html .= '</li>';
    }
    $html .= '</ol>';

    return $html;
};

$saveTreeUrl = Url::to(['save-tree']);
$csrf = Yii::$app->request->csrfToken;
$csrfParam = Yii::$app->request->csrfParam;
$saveTreeUrlJson = Json::htmlEncode($saveTreeUrl);
$csrfParamJson = Json::htmlEncode($csrfParam);
$csrfJson = Json::htmlEncode($csrf);

$this->registerCss(<<<CSS
.menu-section-card { border: 1px solid #e5e7eb; border-radius: .5rem; background: #fff; margin-bottom: 1.5rem; }
.menu-section-card__header { display:flex; justify-content:space-between; align-items:center; padding:1rem 1.25rem; border-bottom:1px solid #eef2f7; }
.menu-tree { list-style:none; margin:0; padding: .75rem 1rem 1rem 1rem; min-height: 24px; }
.menu-tree .menu-tree { margin-left: 2rem; padding-top: .5rem; padding-bottom: 0; border-left: 2px dashed #d7dee8; }
.menu-tree--child-empty { min-height: 22px; margin-left: 2rem; padding-top: .5rem; padding-bottom: 0; border-left: 2px dashed #d7dee8; }
.menu-node { margin-bottom: .75rem; }
.menu-node__card { display:flex; justify-content:space-between; align-items:center; gap:1rem; padding:.85rem 1rem; border:1px solid #dbe3ec; border-radius:.5rem; background:linear-gradient(180deg,#ffffff 0%,#f8fafc 100%); }
.menu-node__main { display:flex; align-items:center; gap:.85rem; min-width:0; }
.menu-node__handle { cursor:move; color:#64748b; font-size:1rem; }
.menu-node__title { font-weight:600; color:#1f2937; }
.menu-node__sub { font-size:.85rem; color:#6b7280; }
.menu-node__actions { white-space:nowrap; }
.menu-tree-placeholder { border:2px dashed #60a5fa; border-radius:.5rem; height:56px; background:rgba(96,165,250,.08); margin-bottom:.75rem; }
.menu-empty-dropzone { padding: .75rem 1rem 1rem; color:#94a3b8; font-size:.9rem; }
.menu-toolbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
CSS);

$this->registerJs(<<<JS
(function () {
    const saveUrl = {$saveTreeUrlJson};
    const csrfParam = {$csrfParamJson};
    const csrfToken = {$csrfJson};

    function depthOf(list) {
        return parseInt(list.dataset.depth || '1', 10);
    }

    function collect(list, result) {
        const sectionId = parseInt(list.dataset.section || '0', 10);
        const parentValue = list.dataset.parent || '';
        const parentId = parentValue === '' ? null : parseInt(parentValue, 10);
        const depth = depthOf(list);

        Array.from(list.children).forEach(function (item, index) {
            if (!item.classList.contains('menu-node')) {
                return;
            }
            const id = parseInt(item.dataset.id, 10);
            result.push({
                id: id,
                section_id: sectionId,
                parent_id: parentId,
                position: index + 1,
                depth: depth
            });
        });
    }

    async function saveTree() {
        const items = [];
        document.querySelectorAll('.menu-tree').forEach(function (list) {
            collect(list, items);
        });

        const body = new URLSearchParams();
        body.append('items', JSON.stringify(items));
        body.append(csrfParam, csrfToken);

        const response = await fetch(saveUrl, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': csrfToken
            },
            body: body
        });

        const data = await response.json();
        if (!data.success) {
            alert(data.message || 'Failed to save menu order.');
        }
    }

    $('.menu-tree').sortable({
        connectWith: '.menu-tree',
        handle: '.menu-node__handle',
        placeholder: 'menu-tree-placeholder',
        items: '> .menu-node',
        tolerance: 'pointer',
        start: function (event, ui) {
            ui.placeholder.height(ui.item.outerHeight());
        },
        receive: function (event, ui) {
            const targetDepth = depthOf(event.target);
            if (targetDepth > 3) {
                $(ui.sender).sortable('cancel');
            }
        },
        update: function (event, ui) {
            if (this === ui.item.parent()[0]) {
                saveTree();
            }
        }
    }).disableSelection();
})();
JS);
?>

<div class="menu-index">
    <div class="menu-toolbar">
        <h1 class="m-0"><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Create Menu Item', ['create'], ['class' => 'btn btn-primary']) ?>
    </div>

    <?php foreach ($sections as $section): ?>
        <div class="menu-section-card">
            <div class="menu-section-card__header">
                <div>
                    <strong><?= Html::encode($section->title) ?></strong>
                    <div class="text-muted small"><?= Html::encode($section->key) ?></div>
                </div>
            </div>
            <?= $renderTree((int) $section->id, 0, 1) ?>
            <?php if (empty($grouped[(int) $section->id][0] ?? [])): ?>
                <div class="menu-empty-dropzone">No menu items in this section yet. Drag items here to assign this section.</div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
