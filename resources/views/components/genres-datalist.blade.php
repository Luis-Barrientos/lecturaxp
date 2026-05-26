<datalist id="genresList">
    @foreach(config('genres.list') as $genre)
        <option value="{{ $genre }}">
    @endforeach
</datalist>