/**
 * submit login form
 * @returns {boolean}
 */
function submitLogin()
{
    const data = {
        username: $("#inputUser").val(),
        password: $("#inputPassword").val()
    };

    $.ajax({
        url: '/login',
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
        alert('Invalid login');
    });

    return false;
}