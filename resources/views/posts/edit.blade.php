<!-- /views/posts/edit.blade.php -->


@extends('layouts.app')

@section('content')
    <h1>Edit Post</h1>

    <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="type">Type</label>
            <select id="type" name="type" class="form-control" required>
                <option value="news" {{ $post->type == 'news' ? 'selected' : '' }}>News</option>
                <option value="book" {{ $post->type == 'book' ? 'selected' : '' }}>Book</option>
                <option value="normal" {{ $post->type == 'normal' ? 'selected' : '' }}>Normal</option>
            </select>
        </div>

        <div id="news-post-fields" class="form-group" style="{{ $post->type == 'news' || $post->type == 'normal' ? 'display:block;' : 'display:none;' }}">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control">
            <label for="caption">Caption</label>
            <input type="text" name="caption" class="form-control" value="{{ old('caption', $post->caption) }}">
        </div>

        <div id="book-post-fields" class="form-group" style="{{ $post->type == 'book' ? 'display:block;' : 'display:none;' }}">
            <label for="pdf">PDF</label>
            <input type="file" name="pdf" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
    </form>

    <script>
        document.getElementById('type').addEventListener('change', function () {
            const type = this.value;
            document.getElementById('news-post-fields').style.display = type === 'news' || type === 'normal' ? 'block' : 'none';
            document.getElementById('book-post-fields').style.display = type === 'book' ? 'block' : 'none';
        });
    </script>
@endsection