function hwRecaptcha() {
    var siteKey = document.getElementById("recaptchaKey").value;
    var logoPosition = document.getElementById("logoPosition").value;
    var clientId = grecaptcha.render('grecaptcha-box', {
        'sitekey': siteKey,
        'badge': logoPosition,
        'size': 'invisible'
    });

    grecaptcha.ready(function() {
        grecaptcha.execute(clientId, {
            action: 'submit'
        })
    });
}
