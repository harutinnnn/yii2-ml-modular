/**/
getEducationProgramms($('#education_level').val(),$('#education_level'));
$('#education_level').change(function () {

    getEducationProgramms($(this).val(),this);
})


function getEducationProgramms(id,e) {


    $('#educational_programs').html('')

    $.ajax({
        type: 'get',
        url: '/admission/education-programs?id=' + id,
        dataType: 'json',
        beforeSend: function (data) {
            $(e).prop('disabled', true);
        },
        success: function (data) {


            $.each(data, function (id, name) {
                $('#educational_programs').append(
                    `<option value="${id}">${name}</option>`
                );
            })

            $(e).prop('disabled', false);
        },
        error: function (error) {
            $(e).prop('disabled', false);
        }
    })
}