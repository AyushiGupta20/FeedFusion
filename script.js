function loadNews() {
    const topics = document.getElementById('topics').value;

    if (!topics) {
        alert('Please enter at least one topic.');
        return;
    }

    fetch(`fetch_news.php?topics=${encodeURIComponent(topics)}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text(); // Read response as text
    })
    .then(text => {
        try {
            const data = JSON.parse(text); // Parse the text as JSON
            if (data.error) {
                throw new Error(data.error);
            }

            const newsContainer = document.getElementById('news-container');
            newsContainer.innerHTML = '';

            if (data.articles.length === 0) {
                newsContainer.innerHTML = '<p>No news articles found.</p>';
                return;
            }

            data.articles.forEach(article => {
                const articleElement = document.createElement('div');
                articleElement.className = 'news-article';

                articleElement.innerHTML = `
                    ${article.urlToImage ? `<img src="${article.urlToImage}" alt="${article.title}">` : ''}
                    <h3>${article.title}</h3>
                    <p>${article.author ? `By ${article.author}` : ''}</p>
                    <p>${article.description}</p>
                    <a href="${article.url}" target="_blank">Read more</a>
                `;

                newsContainer.appendChild(articleElement);
            });
        } catch (error) {
            console.error('Error parsing JSON:', error);
            const newsContainer = document.getElementById('news-container');
            newsContainer.innerHTML = `<p>Error: ${error.message}</p>`;
        }
    })
    .catch(error => {
        console.error('Error loading news:', error);
        const newsContainer = document.getElementById('news-container');
        newsContainer.innerHTML = `<p>Error: ${error.message}</p>`;
    });
}

function refreshContent() {
    document.getElementById('topics').value = '';
    document.getElementById('news-container').innerHTML = '';
}
