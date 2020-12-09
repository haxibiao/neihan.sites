<div class="hot-movies-intro">
    @foreach ($hotMovies as $movie)
        <div class="movie-info">
            <h3 class="movie-name">{{ $movie->name }}</h3>
            <p class="movie-abstract webkit-ellipsis">
                {{ $movie->introduction }}
            </p>
        </div>
    @endforeach
</div>
<div id="hot-movies" class="hot-movies-panel">
    @foreach ($hotMovies as $movie)
        <a href="/movie/{{ $movie->id }}" target="_blank" class="movie-item">
            <img data-src="{{ $movie->cover }}" alt="{{ $movie->name }}" class="movie-pic">
        </a>
    @endforeach
</div>
