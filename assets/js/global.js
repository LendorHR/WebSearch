function createSearchCleaner() {
    $('#search_input').on('input propertychange', function () {
        var visible = Boolean($(this).val());

        $('#search_clear').toggleClass('hidden', !visible);
    }).trigger('propertychange');

    $('#search_clear').click(function () {
        $('#search_input').val('').trigger('propertychange').focus();
    });
}

function fillInputWithQuery() {
    var pageURL = window.location.search.substring(1),
        urlVariables = pageURL.split('&'),
        parameters;

    for (var i = 0; i < urlVariables.length; i++) {
        parameters = urlVariables[i].split('=');

        if (parameters[0] === 'search_query') {
            var result = undefined ? true : decodeURIComponent(parameters[1].replace(/\+/g, ' '));
            $('#search_input').val(result).trigger('propertychange').focus();
        }
    }
}

$(function main() {
    createSearchCleaner();
    fillInputWithQuery();
});