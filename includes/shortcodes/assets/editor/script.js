(function () {

    if (
        !window.hasOwnProperty('novembit')
        || !window.novembit.hasOwnProperty('i18n')
        || !window.novembit.i18n.hasOwnProperty('editor')
    ) {
        console.log('NovemBit i18n request editor not initialized.');
        return;
    }

    let switchers = document.getElementsByClassName('novembit-i18n-translation-editor');

    for (let i = 0; i < switchers.length; i++) {

        let switcher = switchers[i];

        let title = switcher.dataset.title;
        let exit_label = switcher.dataset.exit_label;

        //switcher.getElementsByClassName('loading')[0].style.display = 'none';

        let urls = window.novembit.i18n.editor.url_translations;

        let label = switcher.getElementsByClassName('i18n-label');

        if (window.novembit.i18n.editor.is_editor) {
            let close_link = document.createElement('a');
            close_link.innerText = exit_label;
            close_link.setAttribute('href', window.novembit.i18n.orig_request_uri);
            label[0].innerText = '';
            label[0].appendChild(close_link);
        } else {
            label[0].innerText = title;
        }

        switcher.appendChild(label[0]);

        let list = document.createElement('ul');
        list.classList.add('i18n-list');


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

        switcher.appendChild(list);
    }

})();