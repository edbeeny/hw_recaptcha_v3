function hwRecaptcha() {
    var siteKey = document.getElementById("recaptchaKey").value;
    var clientId = grecaptcha.render('grecaptcha-box', {
        'sitekey': siteKey,
        'badge': 'inline',
        'size': 'invisible'
    });

    grecaptcha.ready(function() {
        grecaptcha.execute(clientId, {
            action: 'submit'
        })
    });
}