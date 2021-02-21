
async function fetchMoviesJSON() {
    const response = await fetch('/movies');
    const movies = await response.json(); return movies;
}
