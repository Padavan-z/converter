/**
 * Created by padavan on 18.05.17.
 */
$(document).ready(function() {
    Currency.init();
});

var Currency = {
    defaultCurrency: 'USD',
    sendAjax: true,
    resultAmountSelector: '.result-amount',
    resultHistorySelector: '.history',
    defaultHistoryShowRows: 5,

    init: function() {
        $('#conver_go').on('click', function() {
            var data = {
                'from': $('#from-currency').val(),
                'to': $('#to-currency').val(),
                'amount': $('#amount').val()
            }
            Currency.convert(data);
        });

        $('#inverseBtn').on('click', function() {
            var from = $('#from-currency').val();
            var to = $('#to-currency').val();
            $('#from-currency').val(to);
            $('#to-currency').val(from);
        });
    },

    validate: function (data) {
        var result = false;
        if (
            'undefined' != typeof(data)
            && 'undefined' != typeof(data.from)
            && 'undefined' != typeof(data.to)
            && 'undefined' != typeof(data.amount)
            && data.from != data.to
        ) {
            if (/^\d+(\.\d+)?$/.test(data.amount) && 0 < data.amount) {
                result = true;
            } else {
                Currency.drawResult(Currency.resultAmountSelector, 'Numbers Only', true);
            }
        }

        return result;
    },

    convert: function(data) {
        if (Currency.sendAjax && Currency.validate(data)) {
            Currency.sendAjax = false;
            data.case = 'convert';
            $.ajax({
                url: '/ajax',
                data: data,
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    Currency.drawResult(Currency.resultAmountSelector, result);
                    Currency.drawHistory(Currency.resultHistorySelector, data, result);
                },
                error: function (result) {
                    Currency.drawResult(Currency.resultAmountSelector, result);
                },
                complete : function () {
                    Currency.sendAjax = true;
                }
            });
        } else if (
            'undefined' != typeof(data.from)
            && 'undefined' != typeof(data.to)
            && 'undefined' != typeof(data.amount)
            && data.from == data.to
        ) {
            if (0 < data.amount && /^\d+(\.\d+)?$/.test(data.amount)) {
                Currency.drawResult(Currency.resultAmountSelector, data.amount, true);
            } else {
                Currency.drawResult(Currency.resultAmountSelector, 'Numbers Only', true);
            }
        }
    },

    drawResult: function (selector, result, skipFlag) {
        var res = '';
        if (
            'undefined' != typeof(result)
            && 'undefined' != typeof(result.status)
            && 'undefined' != typeof(result.data)
            && 'undefined' != typeof(result.error)
        ) {
            if (result.status && result.data) {
                res = result.data;
            } else {
                res = result.error;
            }
        }

        if (skipFlag) {
            res = result;
        }

        $(selector).html(res).closest('div').show('fast');
    },

    drawHistory: function (selector, dataSend, dataGet) {
        var str;
        if (
            'undefined' != typeof(dataSend)
            && 'undefined' != typeof(dataSend.from)
            && 'undefined' != typeof(dataSend.to)
            && 'undefined' != typeof(dataSend.amount)
            && 'undefined' != typeof(dataGet)
            && 'undefined' != typeof(dataGet.data)
        ) {
            var count = $(selector).find('.history-row').length;
            if (Currency.defaultHistoryShowRows == count) {
                $(selector).find('.history-row:eq(0)').remove();
            }
            str = '<span class="history-row">'
                + dataSend.from
                + ' ' + dataSend.amount + ' -> '
                + dataGet.data + ' ' + dataSend.to
                + '</span>';
            $(selector).append(str).closest('div').show('fast');
        }
    }
}