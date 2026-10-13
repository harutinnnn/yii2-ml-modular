<?php

use softark\duallistbox\DualListbox;
use yii\helpers\ArrayHelper;
use yii\web\View;

/** @var yii\web\View $users [] */

$items = ArrayHelper::map($users, 'id', 'name');

echo DualListbox::widget([
    'name' => 'userIds',
    'selection' => $conversationMemberIds ?? [],
    'items' => $items,
    'options' => [
        'id' => 'users-list',
        'multiple' => true,
        'size' => 20,
    ],
    'clientOptions' => [
        'moveOnSelect' => false,
        'selectedListLabel' => 'Selected Users',
        'nonSelectedListLabel' => 'Available Users',
        'showFilterInputs' => true,
    ],
]);
?>

<?php

$this->registerJs(<<<JS

const list = $('#users-list');

// start from the already-selected members
let previousIds = (list.val() || []).map(String);

list.on('change', function () {
    const currentIds = ($(this).val() || []).map(String);

    const added   = currentIds.filter(id => !previousIds.includes(id));
    const removed = previousIds.filter(id => !currentIds.includes(id));

    added.forEach(id => updateUser(id, 'assign'));
    removed.forEach(id => updateUser(id, 'remove'));

    previousIds = currentIds;
});

function updateUser(id, action) {
    $.ajax({
        type: 'post',
        url: '/admin/conversations/update-assignment',
        data: {
            userId: id,
            action: action,
            conversation_id: {$conversation_id},
            _csrf: yii.getCsrfToken()
        },
        success: function (res) {
            console.log(action, id, res);
        },
        error: function (err) {
            console.error('Failed', action, id, err);
        }
    });
}
JS, View::POS_READY);
?>
