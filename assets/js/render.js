function hwRecaptcha() {
    $(".hwRecaptcha").each(function() {
        var el = $(this);
        var clientId =  grecaptcha.render($(el).attr("id"), {
            'sitekey': $(el).attr('data-sitekey'),
            'badge': $(el).attr('data-badge'),
            'theme': $(el).attr('data-theme'),
            'size' : 'invisible',
            'callback' : function(token) {
                $(el).parent(".g-recaptcha-response").val(token);
                $(el).parent().submit();
            }
        });
        grecaptcha.ready(function() {
            grecaptcha.execute(clientId, {
                action: 'submit'
            })
        });
    });





    }
