(function () {
    window.onload = function () {
        var lists = document.querySelectorAll('.novembit-i18n-admin-nested-fields>.novembit-i18n-admin-nested-fields');
        for (var i = 0; i < lists.length; i++) {
            var list = lists[i];
            var label = list.previousSibling;
            console.log(label);
            label.addEventListener("click", function () {
                if (this.nextSibling.offsetParent === null) {
                    this.nextSibling.style.display = "block";
                    this.classList.add('open');
                } else {
                    this.nextSibling.style.display = "none";
                    this.classList.remove('open');
                }
            });
        }
    };

})();