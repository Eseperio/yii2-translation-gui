/**
 * Created on : 2014.08.24., 5:26:26
 * Author     : Lajos Molnar <lajax.m@gmail.com>
 * since 1.0
 */

var translate = (function () {

    /**
     * @type string
     */
    var _originalMessage;

    /**
     * @param {object} $this
     */
    function _modelTranslateLanguage($this) {

        var data = {
            language_id: $this.data('id'),
            action: 'getModalContent',
        };

        $.ajax({
            url: $this.data('url'), // Obtener la URL del botón
            type: 'POST',  // O el método HTTP que estás utilizando
            dataType: 'json',
            data: data,
            success: function (response) {
                $("#modal_languaje_id").html(data.language_id);
                $("#bulk-translation-confirm").attr('data-id', data.language_id);
                $("#modal_total_charts").html(response.totalCharts);
                $("#modal_total_translations").html(response.totalTranslations);
                $("#bulk-translation-modal").modal("show");
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("Error to write modal content:", errorThrown);
            }
        });
    }

    function _bulkTranslateLanguage($this) {

        $("#loadingSpinner").show();
        var data = {
            language_id: $this.data('id'),
            action: 'translateLanguage',
        };

        $.ajax({
            url: $this.data('url'), // Obtener la URL del botón
            type: 'POST',  // O el método HTTP que estás utilizando
            dataType: 'json',
            data: data,
            success: function (response) {
                console.log(response);

                var sCount = $("#modal_translated").text();
                if (!isNaN(parseInt(sCount))) {
                    sCount = parseInt(sCount) + parseInt(response.translated_string);
                    console.log(sCount + ' + ' + response.translated_string);
                } else {
                    sCount = parseInt(response.translated_string);
                }
                $("#modal_translated").html(sCount);

                $("#loadingSpinner").hide();
                // $("#bulk-translation-content").html('success');

                // if (sCount < 40) {
                //     setTimeout(
                //         $('#languages').on('click', '#bulk-translation-confirm', function () {_bulkTranslateLanguage($(this));}),
                //         10000);
                // }
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("Error to translate bulk content:", errorThrown);
                $("#loadingSpinner").hide();
                // $("#bulk-translation-content").html('error');
            }
        });
    }

    return {
        init: function () {
            $('#languages').on('click', '.bulk-translation.btn', function () {
                _modelTranslateLanguage($(this));

            });
            $('#languages').on('click', '#bulk-translation-confirm', function () {
                _bulkTranslateLanguage($(this));
            });
        }
    };
})();

$(document).ready(function () {
    translate.init();
});
