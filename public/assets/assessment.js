/**
 * submit login form
 * @returns {boolean}
 */
function submitAssessment()
{
    const assessmentId = $("#selectAssessment").val();
    const data = {
        assessmentId: assessmentId
    };

    $.ajax({
        url: '/assessment',
        type: "POST",
        contentType: 'application/json',
        dataType: 'json',
        data: JSON.stringify(data)
    }).done(function (data) { // successful login
        // TODO: user friendly message
        // alert(data.message);
        // TODO: redirect to ?urlTo=url
        window.location.reload();
    }).fail(function (xhr) {
        // alert('Error');
    });

    return false;
}