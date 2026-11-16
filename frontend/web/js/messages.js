// Send a message


const $el = $('#user-messages');

const coversationMessages = $('#conversation-messages');

function sendMessage(e, id) {

    const conversationId = $('#conversationId').val();

    const message = $('#chat-message');

    const formData = new FormData();
    formData.append('ConversationForm[message]', message.val().trim());
    formData.append('ConversationForm[conversationId]', parseInt(conversationId));

    const files = $('#message-attachments')[0].files;

    for (let i = 0; i < files.length; i++) {
        formData.append('ConversationForm[files][]', files[i]);
    }


    formData.append(
        yii.getCsrfParam(),
        yii.getCsrfToken()
    );


    if (parseInt(conversationId)) {

        if (id && message.val().trim().length > 3) {

            $.ajax({
                type: 'POST',
                url: '/' + lang + '/send-message',
                data: formData,
                dataType: 'json',
                // Required for FormData
                processData: false,
                contentType: false,

                beforeSend: function () {
                    $(e).prop('disabled', true);
                },

                success: function (data) {

                    if (data.success) {
                        message.val('');
                        $('#message-attachments').val('');
                        $('#attachments').val('');

                        socket.emit('private-message', {
                            userId: userId,
                            data: data
                        });

                        addMessage(data);
                        $(e).prop('disabled', false);
                    } else {

                        if (Object.keys(data.errors).length) {
                            let message = '';
                            $.each(data.errors, function (key, value) {
                                if (key == 'files') {
                                    $.each(value, function (key1, val1) {
                                        message += `<div class="error-msg message">${val1}</div>`;
                                    });
                                } else {
                                    message += `<div class="error-msg message">${value.join(', ')}</div>`;
                                }

                            });
                            showMessageAlert(message);
                        }

                        if(data.message.length){
                            showMessageAlert(`<div class="error-msg message">${data.message}</div>`)
                        }
                    }
                },

                error: function (xhr) {
                    console.error('Upload error:', xhr.responseText);
                    $(e).prop('disabled', false);
                },

                complete: function () {
                    $(e).prop('disabled', false);
                }
            });


        } else {

            showMessageAlert('Minimum message lengt more 3 symbols');

        }
    } else {
        showMessageAlert('You are not choose the conversation!');
    }
}

socket.on('message', function (data) {

    if (data.messageObj.message.conversation_id == conversationId) {

        addMessage(data);
    } else {
        //TODO set notification
    }
});


function addMessage(data) {

    let files = "";

    if (data.messageObj.uploadedFiles) {
        files += '<div class="message-files">';
        data.messageObj.uploadedFiles.forEach(file => {
            files += '<div class="message-file-item">';
            files += ` <a href="${file.url}" target="_blank">`;
            files += `      <img src="${file.icon}" alt=""/>`;
            files += '</div>';
        })
        files += '</div>';
    }

    $('#user-messages').append(
        `<div class="user-message-item ${data.messageObj.user.id === userId ? 'current-user' : ''}">
            <div class="user-message-item-header">
                <strong class="user-message-item-name">
                    ${data.messageObj.user.full_name}
                </strong>
            <div class="user-message-date">
                ${data.messageObj.message.created_at}
            </div>
        </div>
        <div>
            ${data.messageObj.message.message}
        </div>
        ${files}
    </div>`
    );

    $el.animate({
        scrollTop: $el[0].scrollHeight
    }, 500);
}


$el.animate({
    scrollTop: $el[0].scrollHeight
}, 500);

function showMessageAlert(message) {
    $(coversationMessages).html(message);
    $(coversationMessages).removeClass('hidden');
    // setTimeout(function () {
    //     $(coversationMessages).addClass('hidden');
    // }, 5000);
}

