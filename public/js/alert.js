document.addEventListener('DOMContentLoaded', function() {
    var successAlert = document.getElementById('successAlert');
    var errorAlert = document.getElementById('errorAlert');

    if (successAlert) {
        successAlert.classList.add('show');
        setTimeout(function() {
            successAlert.classList.remove('show');
            successAlert.classList.add('fade');
        }, 5000);
    }
    if (errorAlert) {
        errorAlert.classList.add('show');
        setTimeout(function() {
            errorAlert.classList.remove('show');
            errorAlert.classList.add('fade');
        }, 5000);
    }
});