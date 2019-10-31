(function () {
    let switchers = document.getElementsByClassName('novembit-i18n-translation-editor');

    for (let i = 0; i < switchers.length; i++) {

        let switcher = switchers[i];

        let title = switcher.dataset.title;
        let exit_label = switcher.dataset.exit_label;

        switcher.getElementsByClassName('loading')[0].style.display = 'none';

        let urls = window.novembit.i18n.editor.url_translations;

        let label = document.createElement('a');
        label.classList.add('label');

        if (window.novembit.i18n.editor.is_editor) {
            let close_link = document.createElement('a');
            close_link.innerText = exit_label;
            close_link.setAttribute('href', window.novembit.i18n.orig_request_uri);
            label.appendChild(close_link);
        } else {
            label.innerText = title;
        }

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

            item.appendChild(link);

            list.appendChild(item);
        }

        label.appendChild(list);
    }

})();