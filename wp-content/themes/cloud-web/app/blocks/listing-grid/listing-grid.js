window.coreTheme.blocks('listing-grid', (el, data) => {
    const loadBtn = el.querySelector('.js-load-btn');
    const postsWrap = el.querySelector('.posts');

    // Get Posts
    let getPosts = async (params) => {
        if (params.length < 0) return null;

        try {
            const response = await fetch(`/wp-json/core/v1/posts/`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Set content type to JSON
                },
                body: JSON.stringify(params)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            } else {
                loadBtn.dataset.offset = parseInt(params.offset) + parseInt(params.posts_per_page)
            }

            const posts = await response.json();
            return posts;

        } catch (error) {
            console.error('Fetch error:', error);
        }
    };

    // Update Listings
    let updateListing = (resp) => {
        if (resp.results.length < 1) return;

        if (resp.html.length > 0) {
            resp.html.forEach(article => {
                postsWrap.innerHTML += article;
            });
        }
    };

    // Load More Clicked
    if (loadBtn) {
        loadBtn.addEventListener('click', () => {
            let { offset, totalposts, search, queryargs } = loadBtn.dataset;

            if (offset < totalposts) {
                postsWrap.classList.add('loading');

                let queryArgs = JSON.parse(queryargs.replace(/&quot;/g, '"'));

                const params = {
                    component: 'post-card',
                    offset: offset,
                    fields: data,
                    ...queryArgs
                };

                getPosts(params).then(resp => updateListing(resp)).finally(() => {
                    postsWrap.classList.remove('loading');
                });
            } else {
                loadBtn.remove();
            }
        });
    }
});