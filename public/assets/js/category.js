(function () {
    var page = document.querySelector('.category-page[data-category-ajax]');
    if (!page) {
        return;
    }

    var content = page.querySelector('[data-category-content]');
    var toolbar = page.querySelector('[data-category-toolbar]');
    if (!content || !toolbar) {
        return;
    }

    var controller = null;
    var requestId = 0;

    function setLoading(isLoading) {
        page.classList.toggle('is-loading', isLoading);
        content.setAttribute('aria-busy', isLoading ? 'true' : 'false');
    }

    function updateFromHtml(html, url, push) {
        var doc = new DOMParser().parseFromString(html, 'text/html');
        var nextPage = doc.querySelector('.category-page[data-category-ajax]');
        var nextToolbar = nextPage ? nextPage.querySelector('[data-category-toolbar]') : null;
        var nextContent = nextPage ? nextPage.querySelector('[data-category-content]') : null;

        if (!nextToolbar || !nextContent) {
            window.location.href = url;
            return false;
        }

        toolbar.innerHTML = nextToolbar.innerHTML;
        content.innerHTML = nextContent.innerHTML;

        if (push) {
            history.pushState({ categoryAjax: true }, '', url);
        }

        return true;
    }

    function load(url, push) {
        if (controller) {
            controller.abort();
        }

        controller = new AbortController();
        var currentId = ++requestId;
        setLoading(true);

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: controller.signal,
            credentials: 'same-origin',
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Bad response');
                }
                return response.text();
            })
            .then(function (html) {
                if (currentId !== requestId) {
                    return;
                }
                updateFromHtml(html, url, push);
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }
                window.location.href = url;
            })
            .finally(function () {
                if (currentId === requestId) {
                    setLoading(false);
                }
            });
    }

    page.addEventListener('click', function (event) {
        var link = event.target.closest('a');
        if (!link || !page.contains(link)) {
            return;
        }

        var inToolbar = link.closest('[data-category-toolbar]');
        var inPagination = link.closest('[data-category-content] .pagination');
        if (!inToolbar && !inPagination) {
            return;
        }

        var url = link.href;
        if (!url || link.origin !== window.location.origin) {
            return;
        }

        event.preventDefault();
        if (url === window.location.href) {
            return;
        }

        load(url, true);
    });

    window.addEventListener('popstate', function () {
        load(window.location.href, false);
    });
})();
