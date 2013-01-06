$(function() {

    $('input[type=checkbox],input[type=radio],input[type=file]').uniform();


    var argetFwkUtilsLib = new ArgetFwkUtilsLib();


    /* 
     * ArgetFwk - Lib : Login Function
     */
    $('#submit').click(function() {

        var login = $('#inputEmail').val();
        var password = $('#inputPassword').val();

        var objAjax = new AjaxLib();
        objAjax.setController('login');
        objAjax.setMethod('login');
        objAjax.setDataString('&login=' + login + '&password=' + password);

        var html = objAjax.execute();
        var result = html;
        var regex = /^\d/;
        if (result === 'User checked.') {
            $('#validation-login').submit();
        }
        else if (result.match(regex)) {
            $('#texteLogin').html('<p>Trop de tentatives de connexion.<br/>Merci de patienter ' + result + ' secondes.</p>');
            $("#warning").modal({
                keyboard: false
            });
        } else {
            $('#texteLogin').html('<p>Vos identifiants sont incorrects.</p>');
            $("#warning").modal({
                keyboard: false
            });
        }

    });

    /* 
     * ArgetFwk - Lib : Forget Password Function
     */
    $('#submitForget').click(function(e) {

        e.preventDefault();

        var messageSent = 'Le champ email est incorrect ...';
        var expreg = '';
        var type = 'email';
        var ajax = '';
        var repo = '';
        var method = '';

        if (!argetFwkUtilsLib.checkInputs($('#inputEmail'), expreg, type, ajax, repo, method)) {
            argetFwkUtilsLib.messageBox('#messageBox', messageSent, 2000);
        } else {
            var login = $('#inputEmail').val();

            var objAjax = new AjaxLib();
            objAjax.setController('login');
            objAjax.setMethod('forget');
            objAjax.setDataString('&login=' + login);

            var html = objAjax.execute();
            var result = html;
            var regex = /^\d/;
            if (result === 'User checked.') {
                $('#validation-login').submit();
            }
            else if (result.match(regex)) {
                $('#texteLogin').html('<p>Trop de tentatives de demandes de mot de passe.<br/>Merci de patienter ' + result + ' secondes.</p>');
                $("#warning").modal({
                    keyboard: false
                });
            } else {
                $('#texteLogin').html('<p>Votre message a bien été envoyé.</p>');
                $("#warning").modal({
                    keyboard: false
                });
            }

        }

    });


    /**
     * ArgetFwk - Lib : Gestion dynamique des tableaux
     * -- Rafraîchissement des lignes de body
     */

    /*
     * Tri ASC ou DESC
     */
    $('.sort').click(function() {
        argetFwkUtilsLib.refreshContent($(this), new Array());
    });


    $('.delete-item').live('click', function(e) {
        e.preventDefault();

        $('#confirmBox').modal({backdrop: false});
        $('#confirmTrue').attr('href', $(this).attr('href'));
        $('#confirmTrue').attr('data-id', $(this).attr('data-id'));
        $('#confirmTrue').attr('data-class', $(this).attr('data-class'));

    });

    $('#confirmTrue').live('click', function(e) {

        e.preventDefault();
        var objAjax = new AjaxLib();
        objAjax.setController('table');
        objAjax.setMethod('deleteLine');
        objAjax.setAsyncValue(false);
        objAjax.setDataString('&class=' + $(this).attr('data-class') + '&idProduct=' + $(this).attr('data-id'));
        var result = objAjax.execute();

        $('#confirmBox').modal('hide');

        if (result === 'done.') {
            if ($(this).attr('data-refresh')) {
                if ($(this).attr('data-refresh') === 'refreshreponses') {
                    argetFwkUtilsLib.refreshReponses($(this).attr('data-idticket'));
                }
            } else
                argetFwkUtilsLib.refreshContent($(document), new Array());
        } else if (result === 'error.')
            argetFwkUtilsLib.messageBox('#messageBox', 'Erreur serveur. Merci de contacter l\'administrateur.', 2000);
        else
            argetFwkUtilsLib.messageBox('#messageBox', 'Impossible de supprimer cet élément. D\'autre tables sont liées.', 2000);

    });

    $('.modify-item').live('click', function(e) {

        if ($(this).attr('data-edit') === 'false' && $(e.target).attr('id') !== 'iconValidation') {
            argetFwkUtilsLib.inputsUnselect();
            argetFwkUtilsLib.inputsActive($(this));
        }

    });

    $('#modif-save').live('click', function() {

        var target = $(this).prev('input');
        argetFwkUtilsLib.modifInfoValidation(target);

    });

    $('.modif-container').live('click', function() {

        argetFwkUtilsLib.modifyInfoInactiv();
        $(this).attr('class', 'modif-container-edit');
        var stylesInput = ' style="font-size: 14px; margin-top: 6px; height: 14px;"';
        var value = $(this).children('span').html();
        if ($(this).children('span').attr('data-modify-type') !== 'password') {
            $(this).children('span').html('<input class="modif-input"' + stylesInput + ' value="' + value + '" type="text" id="modif-input" /> <i class="icon-ok hand modif-input-save" id="modif-save"></i>');
        } else {
            $(this).children('span').html('<input class="modif-input"' + stylesInput + ' value="password" type="password" id="modif-input" /> <i class="icon-ok hand modif-input-save" id="modif-save"></i>');
        }

        $(this).children('span').children('input').select();
    });

    $('.modifySave').live('click', function(e) {

        e.preventDefault();
        var target = $(this).prev('input');
        argetFwkUtilsLib.inputsValidation(target);

    });

    $('.addEditItem').live('click', function(e) {

        e.preventDefault();
        var link = $(this).attr('href');
        var modalWidth = $('#addItem').attr('data-width');
        var dataStr = '';
        if ($(this).attr('data-id'))
            dataStr = '&idItem=' + $(this).attr('data-id');
        arrayLink = link.split('/');
        var objAjax = new AjaxLib();
        objAjax.setController(arrayLink[0]);
        objAjax.setMethod(arrayLink[1]);
        objAjax.setAsyncValue(false);
        objAjax.setDataString(dataStr);
        $('#editBody').html(objAjax.execute());

        $('#editBox').modal({backdrop: false}).css({
            width: modalWidth,
            'margin-left': function() {
                return -($(this).width() / 2);
            }
        });

        $('input[type=checkbox],input[type=radio],input[type=file]').uniform();

    });

    $('form').live('submit', function(e) {

        if ($(this).attr('class') !== 'sendFile') {

            e.preventDefault();

            var checked = true;

            if ($(this).attr('id')) {
                $('#' + $(this).attr('id') + ' :input').each(function() {
                    if ($(this).attr('data-verif')) {
                        var messageSent = 'Le champ ' + $(this).attr('name') + ' est incorrect ...';
                        var expreg = '';
                        var type = '';
                        var ajax = '';
                        var repo = '';
                        var method = '';
                        if ($(this).attr('data-expreg'))
                            expreg = $(this).attr('data-expreg');
                        if ($(this).attr('data-message'))
                            messageSent = $(this).attr('data-message');
                        if ($(this).attr('data-type'))
                            type = $(this).attr('data-type');
                        if ($(this).attr('data-ajax'))
                            ajax = $(this).attr('data-ajax');
                        if ($(this).attr('data-repo'))
                            repo = $(this).attr('data-repo');
                        if ($(this).attr('data-method'))
                            method = $(this).attr('data-method');

                        if (!argetFwkUtilsLib.checkInputs($(this), expreg, type, ajax, repo, method)) {
                            argetFwkUtilsLib.messageBox('#messageBox', messageSent, 2000);
                            checked = false;
                        }
                    }
                });
            }

            if ($(this).attr('id') === 'editAddForm' && checked) {

                checked = false;

                var objAjax = new AjaxLib();
                objAjax.setController($(this).attr('data-controller'));
                objAjax.setMethod($(this).attr('data-method'));
                objAjax.setDataString('&' + $(this).serialize());
                objAjax.setAsyncValue(false);
                var result = objAjax.execute();

                var regexpDuplicate = '1062 Duplicate entry \'([a-zA-Z0-9àâäçèéêëìíîïòóôùúûü& -\.@]+)\' for key';
                var regDuplicate = new RegExp(regexpDuplicate, 'i');

                if (result === '') {
                    argetFwkUtilsLib.refreshContent($('#search'), new Array());
                    $('#editBox').modal('hide');
                } else if (regDuplicate.test(result)) {
                    if (result.match(regexpDuplicate)[1])
                        var txt = ' l\'élément : "' + result.match(regexpDuplicate)[1] + '"';
                    else
                        var txt = ' un autre élément.'
                    argetFwkUtilsLib.messageBox('#messageBox', 'Impossible de sauvegarder.<br/>Il existe des similarités avec' + txt, 2500);
                } else
                    argetFwkUtilsLib.messageBox('#messageBox', 'Impossible de sauvegarder cet élément.<br/> Une erreur est survenue.', 2000);
            }

            if (checked)
                this.submit();

        }

    });

    $(document).keydown(function(e) {

        var code = (e.keyCode ? e.keyCode : e.which);

        if (code === 9) {
            var target = $(e.target);
            if (target.attr('id') === 'modifyInput') {
                e.preventDefault();
                var parent = target.parent('.modify-item');
                argetFwkUtilsLib.inputsUnselect();
                if (parent.next('.modify-item').length > 0)
                    argetFwkUtilsLib.inputsActive(parent.next('.modify-item'));
                else {
                    var nextTr = parent.parent('tr').next('tr');
                    argetFwkUtilsLib.inputsActive(nextTr.children('.modify-item').eq(0));
                }
            }

        } else if (code === 27) {
            e.preventDefault();
            argetFwkUtilsLib.inputsUnselect();
            argetFwkUtilsLib.modifyInfoInactiv();
        } else if (code === 13) {
            var target = $(e.target);
            if (target.attr('type') === 'search') {
                argetFwkUtilsLib.refreshContent($('#search'), new Array());
            } else {
                argetFwkUtilsLib.inputsValidation(target);
                argetFwkUtilsLib.modifInfoValidation(target);
            }
        }

    });

    $(document).live('click', function(e) {
        var target = $(e.target);
        if (target.attr('class') !== 'modify-item' && target.attr('id') !== 'modifyInput' && target.attr('class') !== 'modifySave' && target.attr('class') !== 'modif-container' && target.attr('class') !== 'modif-container-edit' && target.attr('class') !== 'value' && target.attr('id') !== 'modif-save' && target.attr('class') !== 'modif-input') {
            argetFwkUtilsLib.inputsUnselect();
            argetFwkUtilsLib.modifyInfoInactiv();
        }

    });

    $('#exportCsvRefresh').click(function(e) {
        e.preventDefault();
        var arrayParams = new Array();

        var ids = '';
        $('td.checkItemTd input:checked').each(function() {
            ids += $(this).attr('data-id') + ',';
        });

        arrayParams['ids'] = ids;
        arrayParams['csv'] = true;
        arrayParams['href'] = $(this).attr('href');
        argetFwkUtilsLib.refreshContent($('#search'), arrayParams);
    });

    $('#checkAll').click(function() {

        var checkedVal = $(this).attr('checked');

        $('td.checkItemTd input').each(function() {
            if (checkedVal !== 'checked') {
                var uniUpdate = $(this).removeAttr('checked');
                $.uniform.update(uniUpdate);
            } else {
                var uniUpdate = $(this).attr('checked', checkedVal);
                $.uniform.update(uniUpdate);
            }
            argetFwkUtilsLib.checkChecked();
        });

    });

    $('.checkitem').live('change', function() {

        argetFwkUtilsLib.checkChecked();

    });

    $('#linkDelete').click(function() {

        var ids = '';
        var classItem = '';
        $('td.checkItemTd input:checked').each(function() {
            ids += $(this).attr('data-id') + ',';
            classItem = $(this).attr('data-class');
        });

        $('#confirmBox').modal({backdrop: false});
        $('#confirmTrue').attr('href', '');
        $('#confirmTrue').attr('data-id', ids);
        $('#confirmTrue').attr('data-class', classItem);

    });

    $('.ajaxImageUpload').live('change', function() {

        $('#editAddForm').attr('class', 'sendFile');

        var elem = $(this).parent().next().next();
        elem.html('');
        elem.html('<br/><img src="web/img/bibliotheque/wait.gif" alt="Uploading ...">');

        if ($(this).attr('data-max-size'))
            var maxsizeVar = $(this).attr('data-max-size');
        else
            var maxsizeVar = 5000;

        if ($(this).attr('data-formats'))
            var formatsVar = $(this).attr('data-formats');
        else
            var formatsVar = 'jpg,jpeg,gif,png';

        if ($(this).attr('data-filename'))
            var fileNameVar = $(this).attr('data-filename');
        else
            var fileNameVar = 'logo';

        if ($(this).attr('data-path'))
            var filePathVar = $(this).attr('data-path');
        else
            var filePathVar = 'default';

        if ($(this).attr('data-perso'))
            var dataPerso = $(this).attr('data-perso');
        else
            var dataPerso = 0;

        $("#editAddForm").ajaxForm({
            url: 'app/ajax.php',
            data: {method: 'imageUpload', controller: 'dashboard', upmaxsize: maxsizeVar, upformat: formatsVar, upfilename: fileNameVar, upfilepath: filePathVar, dataPersoId: dataPerso},
            success: function(data) {
                $('#editAddForm').attr('class', '');
                if (data.length > 2) {

                    elem.html('<img src="' + data + '" style="max-width: 75px; max-height: 75px;"');
                    elem.prev('input').val(data);

                } else if(data.length === 1){
                    window.location.reload();
                }else {
                    elem.html('');
                }
            }
        }).submit();

    });

    $.fn.UItoTop = function(options) {

        var defaults = {
            text: 'Top',
            min: 200,
            inDelay: 600,
            outDelay: 400,
            containerID: 'toTop',
            containerHoverID: 'toTopHover',
            scrollSpeed: 1200,
            easingType: 'linear'
        };

        var settings = $.extend(defaults, options);
        var containerIDhash = '#' + settings.containerID;
        var containerHoverIDHash = '#' + settings.containerHoverID;

        $('body').append('<a href="#" id="' + settings.containerID + '">' + settings.text + '</a>');
        $(containerIDhash).hide().click(function() {
            $('html, body').animate({scrollTop: 0}, settings.scrollSpeed, settings.easingType);
            $('#' + settings.containerHoverID, this).stop().animate({'opacity': 0}, settings.inDelay, settings.easingType);
            return false;
        })
                .prepend('<span id="' + settings.containerHoverID + '"></span>')
                .hover(function() {
            $(containerHoverIDHash, this).stop().animate({
                'opacity': 1
            }, 600, 'linear');
        }, function() {
            $(containerHoverIDHash, this).stop().animate({
                'opacity': 0
            }, 700, 'linear');
        });

        $(window).scroll(function() {
            var sd = $(window).scrollTop();
            if (typeof document.body.style.maxHeight === "undefined") {
                $(containerIDhash).css({
                    'position': 'absolute',
                    'top': $(window).scrollTop() + $(window).height() - 50
                });
            }
            if (sd > settings.min)
                $(containerIDhash).fadeIn(settings.inDelay);
            else
                $(containerIDhash).fadeOut(settings.Outdelay);
        });

    };


});