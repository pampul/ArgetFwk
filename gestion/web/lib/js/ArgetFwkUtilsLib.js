/**
 * Classe de regroupant des fonctions utiles en JS
 *
 * @author f.mithieux
 */
function ArgetFwkUtilsLib()
{

  var pagination = $('#dynamic-table').attr('data-nbrsaved') / $('#dynamic-table').attr('data-max-result') - 1;
  var maxResults = $('#last-tr').attr('data-max-result');
  var nbrSaved = maxResults;
  var orderBy = '';
  var orderDir = '';
  var search = '';
  var methods = '';
  var dataProperty = '';


  this.trim = function(str) {
    return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
  }

  this.getBytesWithUnit = function(bytes) {
    if (isNaN(bytes)) {
      return;
    }
    var units = [' o', ' ko', ' mo', ' go', ' to', ' po', ' eo', ' zo', ' yo'];
    var amountOf2s = Math.floor(Math.log(+bytes) / Math.log(2));
    if (amountOf2s < 1) {
      amountOf2s = 0;
    }
    var i = Math.floor(amountOf2s / 10);
    bytes = +bytes / Math.pow(2, 10 * i);

    // Rounds to 3 decimals places.
    if (bytes.toString().length > bytes.toFixed(3).toString().length) {
      bytes = bytes.toFixed(2);
    }
    return bytes + units[i];
  };

  this.modifyInfoInactiv = function() {
    $('.value').each(function() {
      if ($(this).attr('data-modify-type') !== 'password') {
        if ($(this).children('input')) {
          var parent = $(this).parent('div');
          parent.attr('class', 'modif-container');
          var value = $(this).attr('data-val');
          $(this).html(value);
        }

      } else {
        if ($(this).children('input')) {
          var parent = $(this).parent('div');
          parent.attr('class', 'modif-container');
          $(this).html('********');
        }
      }
    });
  }

  this.inputsActive = function(elem) {

    if (elem.attr('data-method') !== 'id') {

      elem.attr('data-edit', 'true');
      var value = this.trim(elem.html());
      var currentWidth = elem.width();
      var inputWidth = currentWidth - 25;
      var currentHeight = elem.height();
      var inputHeight = currentHeight - 2;

      var stylesInput = ' style="line-height: ' + inputHeight + 'px; height: ' + inputHeight + 'px; width: ' + inputWidth + 'px;"';

      elem.html('<input type="text" value="' + value + '" id="modifyInput" ' + stylesInput + ' data-value="' + value + '" /> <i class="icon-ok hand modifySave" id="iconValidation"></i>');

      elem.css('width', currentWidth + 'px');
      elem.css('background', '#fff');

      elem.children('input').select();

      this.messageBox('#messageBox', 'Mode édition actif.');
    }

  }

  this.modifInfoValidation = function(target) {

    if (target.attr('id') === 'modif-input') {
      var newValue = target.val();
      var method = target.parent('.value').attr('data-modify-type');
      var idLine = $('#id-user').val();
      var classCalled = $('#class-user').val();

      var objAjax = new AjaxLib();
      objAjax.setController('table');
      objAjax.setMethod('modifyLine');
      objAjax.setDataString('&class=' + classCalled + '&idProduct=' + idLine + '&newMethod=' + method + '&newValue=' + newValue);
      returnValue = objAjax.execute();

      if (returnValue === 'done.') {
        target.parent('.value').attr('data-val', target.val());
        this.modifyInfoInactiv();
        this.messageBox('#messageBox', 'Votre modification a été prise en compte.');
      } else {
        this.messageBox('#messageBox', 'Problème lors de la mise à jour ...', 2000);
      }

    }

  }

  this.inputsValidation = function(target) {

    if (target.attr('id') === 'modifyInput') {
      var newValue = target.val();
      var method = target.parent('.modify-item').attr('data-method');
      var idLine = target.parent('.modify-item').attr('data-id');
      var classCalled = target.parent('.modify-item').attr('data-class');

      var objAjax = new AjaxLib();
      objAjax.setController('table');
      objAjax.setMethod('modifyLine');
      objAjax.setDataString('&class=' + classCalled + '&idProduct=' + idLine + '&newMethod=' + method + '&newValue=' + newValue);
      returnValue = objAjax.execute();

      if (returnValue === 'done.') {
        target.attr('data-value', target.val());
        this.inputsUnselect();
        this.messageBox('#messageBox', 'Votre modification a été prise en compte.');
      } else {
        this.messageBox('#messageBox', 'Problème lors de la mise à jour ...', 2000);
      }

    }

  }

  this.inputsUnselect = function() {

    $('.modify-item').each(function() {

      if ($(this).attr('data-edit') === 'true') {
        var valueInput = $(this).children('input').attr('data-value');
        $(this).attr('data-edit', 'false');
        $(this).attr('style', '');
        $(this).html(valueInput);
      }

    })

  }

  this.waitToPlayJs = function(check, onComplete, delay, timeout) {
    // Si check return true, on execute le onComplete()
    if (check()) {
      onComplete();
      return;
    }
    if (!delay)
      delay = 100;
    var timeoutPointer;
    var intervalPointer = setInterval(function() {
      if (!check())
        return; // On recommence un tour si check return false
      // Si tout est bon, on clearInterval et execute onComplete()
      clearInterval(intervalPointer);
      if (timeoutPointer)
        clearTimeout(timeoutPointer);
      onComplete();
    }, delay);
    // On annule si le timeout est dépassé
    if (timeout)
      timeoutPointer = setTimeout(function() {
        clearInterval(intervalPointer);
      }, timeout);
  }

  this.waitDelayAndPlayJs = function(onComplete, delay) {

    setTimeout(function() {
      onComplete();
    }, delay);

  }

  this.messageBox = function(boxChoice, content, time) {

    if (!time)
      time = 10;

    $(boxChoice).children('.contentBox').html(content);

    $(boxChoice).fadeIn('fast');

    this.waitDelayAndPlayJs(function() {
      $(boxChoice).fadeOut('normal');
    }, time);


  }

  this.refreshContent = function(elem, arrayParams) {

    if (arrayParams["csv"]) {
      var paramCsv = 'true';
      var paramIds = arrayParams["ids"];
    } else {
      var paramCsv = 'false';
      var paramIds = '';
    }

    if($('.search-query').length > 0){
      search = $('.search-query').val();
      methods = $('.search-query').attr('title');
    }

    var sendPagination = 0;
    var removeCriteria = 0;
    var className = $('#dynamic-table').attr('name');
    var columns = $('#columns').attr('name');
    if (elem.attr('id') === 'sort') {
      orderBy = elem.attr('type');
      orderDir = elem.attr('name');
      if (elem.attr('data-property'))
        dataProperty = elem.attr('data-property');
      else
        dataProperty = '';
    } else if (elem.attr('id') === 'pagination-button') {
      pagination++;
      if (orderBy === '') {
        orderBy = $('#last-tr').attr('data-order-by');
        orderDir = $('#last-tr').attr('data-order-dir');
      }
      var paginationSaved = pagination;
      paginationSaved++;
      nbrSaved = maxResults * paginationSaved;
      $('#dynamic-table').attr('data-nbrsaved', nbrSaved);
      sendPagination = 1;
    } else if (elem.attr('id') === 'search') {
      search = $('.search-query').val();
      methods = $('.search-query').attr('title');
    } else if (elem.attr('id') === 'effacer') {
      removeCriteria = 1;
      pagination = 0;
      maxResults = $('#last-tr').attr('data-max-result');
      nbrSaved = maxResults;
      $('#dynamic-table').attr('data-nbrsaved', maxResults);
      $('.search-query').val('');
      sendPagination = 0;
      search = '';
      dataProperty = '';
      orderBy = $('#last-tr').attr('data-order-by');
      orderDir = $('#last-tr').attr('data-order-dir');
      $('.sortSelect').each(function() {
        $(this).val('');
      });
    }

    var selectsVals = '';
    $('.sortSelect').each(function() {
      selectsVals += 'class==' + $(this).attr('data-class') + '__method==' + $(this).attr('data-method') + '__value==' + $(this).val() + '||';
    });

    var objAjax = new AjaxLib();
    objAjax.setController('table');
    objAjax.setMethod('refreshBody');
    objAjax.setDataString('&class=' + className + '&sort=' + orderBy + '&order=' + orderDir + '&columns=' + columns + '&pagination=' + pagination + '&search=' + $('.search-query').val() + '&maxResult=' + maxResults + '&nbrSaved=' + $('#dynamic-table').attr('data-nbrsaved') + '&sendPagination=' + sendPagination + '&methods=' + methods + '&actionButtons=' + $('#actionButton').val() + '&nbrExisting=' + $('#total-top').html() + '&data_property=' + dataProperty + '&paramCsv=' + paramCsv + '&paramIds=' + paramIds + '&removeCriteria='+removeCriteria+'&selectsVals=' + selectsVals);
    if (!arrayParams["csv"]) {
      objAjax.setDataType("xml");
      objAjax.setAsyncValue(true);
      if (elem.attr('id') === 'pagination-button')
        objAjax.executePagination();
      else
        objAjax.executeTable();
    } else {
      objAjax.setAsyncValue(false);
      var path = objAjax.execute();
      window.open(arrayParams["href"] + path);
    }

  }

  this.checkChecked = function() {

    var numberOfChecked = 0;
    $('td.checkItemTd input').each(function() {
      if ($(this).attr('checked') === 'checked') {
        $(this).parent('span').parent('div').parent('td').parent('tr').attr('class', 'checkedTr');
        numberOfChecked++;
      } else {
        $(this).parent('span').parent('div').parent('td').parent('tr').attr('class', '');
      }
    });

    if (numberOfChecked > 0) {
      $('#linkDelete').show();
      if ($('#exportCsvRefresh')) {
        $('#exportCsvRefresh').show();
      }
    } else {
      $('#linkDelete').hide();
      if ($('#exportCsvRefresh')) {
        $('#exportCsvRefresh').hide();
      }
      var uniUpdate = $('#checkAll').removeAttr('checked');
      $.uniform.update(uniUpdate);
    }
  }

  this.checkPagination = function() {

    var nbrSaved = parseInt($('#dynamic-table').attr('data-nbrsaved'));

    if (parseInt($('#total-top').html()) < nbrSaved) {
      $('#pagination-button').hide();
    } else {
      $('#pagination-button').show();
    }

    $('input[type=checkbox],input[type=radio],input[type=file]').uniform();
    this.checkChecked();

  }


  this.checkInputs = function(elem, expreg, type, ajax, repository, method, datalength) {

    if (ajax !== '' && repository !== '' && method !== '') {
      var objAjax = new AjaxLib();
      objAjax.setController('table');
      objAjax.setMethod('sendToRepository');
      objAjax.setDataString('&repository=' + repository + '&methodRepo=' + method);
      objAjax.setAsyncValue(false);
      var result = objAjax.execute();

      if (result !== 'ok')
        return false;
      else
        return true;

    } else if (type !== '') {

      switch (type) {

        case 'email':
          var reg = new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i');
          if (reg.test(elem.val()))
            return true;
          else
            return false;
          break;

        case 'name':
          var reg = new RegExp('^[a-zA-Z0-9àâäçèéêëìíîïòóôùúûü- ]{2,}$', 'i');
          if (reg.test(elem.val()))
            return true;
          else
            return false;
          break;

        case 'tel':
          var reg = new RegExp('^0[0-9]{9,9}$', 'i');
          if (reg.test(elem.val()))
            return true;
          else
            return false;
          break;

        case 'title':
          var reg = new RegExp('^[a-zA-Z0-9àâäçèéêëìíîïòóôùúûü& \'-]{2,}$', 'i');
          if (reg.test(elem.val()))
            return true;
          else
            return false;
          break;

        case 'password':
          var reg = new RegExp('^[a-zA-Z0-9àâäçèéêëìíîïòóôùúûü&@-]{5,15}$', 'i');
          if (reg.test(elem.val()))
            return true;
          else
            return false;
          break;

      }

    } else if (expreg !== '') {

      var reg = new RegExp(expreg, 'i');
      if (reg.test(elem.val()))
        return true;
      else
        return false;

    } else {
      if (datalength === '')
        datalength = '1,';
      var reg = new RegExp('^[a-zA-Z0-9àâäçèéêëìíîïòóôùúûü_ \'\.!-?:" ]{' + datalength + '}$', 'i');
      if (reg.test(elem.val()))
        return true;
      else
        return false;
    }

  }

  this.refreshReponses = function(idTicket) {

    var objAjax = new AjaxLib();
    objAjax.setController('dashboard');
    objAjax.setMethod('refreshReponses');
    objAjax.setAsyncValue(false);
    objAjax.setDataString('&idTicket=' + idTicket);
    var result = objAjax.execute();

    $('#listing-rep').html(result);

  }

  this.nl2br = function(str) {

    var breakTag = '<br/>';

    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
  }

  this.br2nl = function(str) {
    return str.replace(/<br>|<br\/>|<br \/>/g, '\r');
  }

  this.trim = function(str) {
    return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
  }

}