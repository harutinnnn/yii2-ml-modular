const generatePassword = (length) => {
    const chars =
        'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*';

    let password = '';

    for (let i = 0; i < length; i++) {
        password += chars[Math.floor(Math.random() * chars.length)];
    }

    document.getElementById('user-password').value = password;
    return password;
}

function ReaderImageDisplay(event, imageBoxId, w) {
    const file = event.target.files[0];
    var reader = new FileReader();
    w = (w) ? w : 100;
    reader.onload = function (e) {

        $('#' + imageBoxId).html('<img style="width:' + w + 'px;" class="img-thumbnail" src="' + e.target.result + '"/>');
    };

    reader.readAsDataURL(file);
}