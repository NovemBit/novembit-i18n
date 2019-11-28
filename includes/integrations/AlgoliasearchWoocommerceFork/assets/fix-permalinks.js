(function () {

    if (window.hasOwnProperty('novembit')
        && window.novembit.hasOwnProperty('i18n')
        && window.novembit.i18n.hasOwnProperty('default_language')
    ) {

        let default_language = window.novembit.i18n.default_language;

        let removeDefaultLanguageFromPermalink = function (item) {
            let pattern = new RegExp('^\/' + default_language + '\/');
            item.permalink = item.permalink.replace(pattern, '/');
            return item;
        };

        if (window.hasOwnProperty('algolia') && window.algolia.hasOwnProperty('addFilter')) {
            window.algolia.addFilter('algoliaWC/infiniteHitsWidgetItemData', removeDefaultLanguageFromPermalink, 1000000000);
            window.algolia.addFilter('algolia/autocompleteItemData', removeDefaultLanguageFromPermalink, 1000000000);
        }
    }

})();