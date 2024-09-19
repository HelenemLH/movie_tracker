document.addEventListener('DOMContentLoaded', function() {
    // Toggle Description and Rating Explanation Sections
    document.getElementById('description-button').addEventListener('click', function() {
        const description = document.getElementById('description');
        const ratingExplanation = document.getElementById('rating-explanation');
        description.style.display = description.style.display === 'none' ? 'block' : 'none';
        ratingExplanation.style.display = 'none'; // Hide the rating explanation when description is shown
    });

    document.getElementById('rating-button').addEventListener('click', function() {
        const ratingExplanation = document.getElementById('rating-explanation');
        const description = document.getElementById('description');
        ratingExplanation.style.display = ratingExplanation.style.display === 'none' ? 'block' : 'none';
        description.style.display = 'none'; // Hide the description when rating explanation is shown
    });

    // Random Button Click Handler
    document.getElementById('random-button').addEventListener('click', function() {
        const API_KEY = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzM2QyZWE4ODAyOWQwNzA1YWU2NDIyOTQwMmZiNWZmOCIsIm5iZiI6MTcyMTgyNDI0OS4zODA1NDksInN1YiI6IjY2OTU2NTc4M2NlMDlkZGVjNDRjMjY2YyIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.QY0t-k0EQcIz0rEhakWKqpeqzD5rw4-YA9BpcikeoHs';
        const API_URL = 'https://api.themoviedb.org/3';
        const resultsContainer = document.getElementById('results-container');
        
        resultsContainer.innerHTML = '<p>Loading...</p>';
        
        fetch(`${API_URL}/movie/popular?api_key=${API_KEY}`)
            .then(response => response.json())
            .then(data => {
                const movies = data.results;
                const randomIndex = Math.floor(Math.random() * movies.length);
                const movie = movies[randomIndex];
                resultsContainer.innerHTML = `
                    <div class="movie">
                        <strong>Title:</strong> ${movie.title} <br>
                        <strong>Release Date:</strong> ${movie.release_date} <br>
                        <strong>Rating:</strong> ${movie.vote_average} <br>
                    </div>
                `;
            })
            .catch(error => {
                resultsContainer.innerHTML = '<p>Failed to load movie data.</p>';
            });
    });
});
