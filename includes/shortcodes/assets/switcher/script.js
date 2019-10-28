(function () {

    let switchers = document.getElementsByClassName('novembit-i18n-language-switcher');

    for (let i = 0; i < switchers.length; i++) {

        let switcher = switchers[i];

        switcher.getElementsByClassName('loading')[0].style.display = 'none';

        let urls = window.novembit.i18n.url_translations;

        let label = document.createElement('div');
        label.classList.add('label');
        label.innerText = window.novembit.i18n.accept_languages[window.novembit.i18n.current_language];

        switcher.appendChild(label);

        let list = document.createElement('ul');
        list.classList.add('list');


        for (let lang in urls) {

            if (!urls.hasOwnProperty(lang)) continue;

            let url = urls[lang];

            let item = document.createElement('li');

            let link = document.createElement('a');

            link.setAttribute('href', url);
            link.innerText = window.novembit.i18n.accept_languages[lang];
            item.appendChild(link);

            list.appendChild(item);
        }

        label.appendChild(list);
    }

})();