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
    function _translateLanguage($this) {
        var $translation = $this.closest('tr').find('.translation');
        var $source = $this.closest('tr').find('.source');

        var data = {
            id: $translation.data('id'),
            language_id: $('#language_id').val(),
            translation: $.trim($translation.val()),
            source: $.trim($source.val()),
            auto_translate: $this.hasClass('auto-translate-button'),
        };

        // Like helpers.post($this.data('url'), data)) but this method haven't got ajax response
        $.ajax({
            url: $this.data('url'), // Obtener la URL del botón
            type: 'POST',  // O el método HTTP que estás utilizando
            dataType: 'json',
            data: data,
            success: function (response) {
                if (response.status === 'success') {
                    if (data.auto_translate == true) {
                        $translation.val(response.translation);
                    }
                }
                $translation.focus(
                    // TODO: change color to red if focus is lost
                    $translation.css('border-color', 'green')
                );
            },
            error: function (xhr, textStatus, errorThrown) {
                console.log("Error en la solicitud AJAX:", errorThrown);
            }
        });
    }

    /**
     * @param {object} $this
     */
    function _copySourceToTranslation($this) {
        var $translation = $this.closest('tr').find('.translation'),
            isEmptyTranslation = $.trim($translation.val()).length === 0,
            sourceMessage = $.trim($this.val());

        if (!isEmptyTranslation) {
            return;
        }

        $translation.val(sourceMessage);
        _translateLanguage($this);
    }

    return {
        init: function () {
            $('#translates').on('click', '.source', function () {
                _copySourceToTranslation($(this));
            });
            $('#translates').on('click', 'button', function () {
                _translateLanguage($(this));
            });
            $('#translates').on('focus', '.translation', function () {
                _originalMessage = $.trim($(this).val());
            });
            // Comment because
            // $('#translates').on('blur', '.translation', function () {
            //     if ($.trim($(this).val()) !== _originalMessage) {
            //
            //         console.log("segunda pasada");
            //         _translateLanguage($(this).closest('tr').find('button'));
            //     }
            // });
            $('#translates').on('change', "#search-form select", function () {
                $(this).parents("form").submit();
            });
        }
    };
})();

$(document).ready(function () {
    translate.init();
});
