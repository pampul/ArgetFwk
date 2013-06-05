/**
 * Classe de génération d'Ajax (utilise jquery)
 *
 * @author f.mithieux
 */
function AjaxLib() {
  /**
   * Initialisation de l'attribut à la fin
   * (Constructeur)
   */
  this.pathAjax = "app/ajax.php";
  this.type = "POST";
  this.dataType = "html";
  this.cacheEnabled = false;
  this.asyncValue = false;
  this.controller = '';
  this.method = '';
  this.dataString = '';

  this.setController = function (value) {
    this.controller = value;
  }

  this.setMethod = function (value) {
    this.method = value;
  }

  this.setType = function (value) {
    this.type = value;
  }

  this.setDataType = function (value) {
    this.dataType = value;
  }

  this.setDataString = function (value) {
    this.dataString = value;
  }

  this.setAsyncValue = function (value) {
    this.asyncValue = value;
  }

  this.doAjax = function () {


  }

  this.execute = function () {

    var resultReturned = '';

    $.ajax({
      type: '' + this.type + '',
      url: '' + this.pathAjax + '',
      data: 'controller=' + this.controller + '&method=' + this.method + this.dataString,
      cache: this.cacheEnabled,
      async: this.asyncValue,
      beforeSend: function () {
        $('body').append('<div id="waiting-div" align="center" style="position: absolute; top: 29%; left: 49%; z-index: 10;"><img src="web/img/bibliotheque/wait.gif"></div>');
      },
      dataType: '' + this.dataType + '',
      success: function (result) {
        resultReturned = result;
        $('#waiting-div').remove();
      }
    });

    return resultReturned;

  }

  this.executeTable = function () {

    var resultReturned = '';
    $.ajax({
      type: '' + this.type + '',
      url: '' + this.pathAjax + '',
      data: 'controller=' + this.controller + '&method=' + this.method + this.dataString,
      cache: this.cacheEnabled,
      async: this.asyncValue,
      dataType: '' + this.dataType + '',
      beforeSend: function () {
        $('body').append('<div id="waiting-div" align="center" style="position: absolute; top: 29%; left: 49%; z-index: 10;"><img src="web/img/bibliotheque/wait.gif"></div>');
      },
      success: function (xml) {
        resultReturned = xml;
        $('#refresh-table').html($('refreshTable', resultReturned).text());
        $('#total-top').html($('totalRows', resultReturned).text());
        $('#total-bottom').html($('totalRows', resultReturned).text());
        $('#total-top-plus').html($('totalResults', resultReturned).text());
        $('#total-bottom-plus').html($('totalResults', resultReturned).text());
        $('#waiting-div').remove();

        var argetFwkUtilsLib = new ArgetFwkUtilsLib();
        argetFwkUtilsLib.checkPagination();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError);
      }
    });

    return resultReturned;

  }

  this.executePagination = function () {

    var resultReturned = '';

    $.ajax({
      type: '' + this.type + '',
      url: '' + this.pathAjax + '',
      data: 'controller=' + this.controller + '&method=' + this.method + this.dataString,
      cache: this.cacheEnabled,
      async: this.asyncValue,
      dataType: '' + this.dataType + '',
      beforeSend: function () {
        $('body').append('<div id="waiting-div" align="center" style="position: absolute; top: 29%; left: 49%; z-index: 10;"><img src="web/img/bibliotheque/wait.gif"></div>');
      },
      success: function (xml) {
        resultReturned = xml;
        $('#last-tr').before($('refreshTable', resultReturned).text());
        $('#total-top').html($('totalRows', resultReturned).text());
        $('#total-bottom').html($('totalRows', resultReturned).text());
        $('#total-top-plus').html($('totalResults', resultReturned).text());
        $('#total-bottom-plus').html($('totalResults', resultReturned).text());
        $('#waiting-div').remove();

        var argetFwkUtilsLib = new ArgetFwkUtilsLib();
        argetFwkUtilsLib.checkPagination();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError);
      }
    });

    return resultReturned;

  }


}


