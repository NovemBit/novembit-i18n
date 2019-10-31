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
            let link_label = document.createElement('span');
            link_label.innerText = window.novembit.i18n.accept_languages[lang]['name'];

            let link_flag = document.createElement('img');
            link_flag.src = window.novembit.i18n.accept_languages[lang]['flag'];
            link.appendChild(link_flag);
            link.appendChild(link_label);

            list.appendChild(item);
        }

        label.appendChild(list);
    }

})();