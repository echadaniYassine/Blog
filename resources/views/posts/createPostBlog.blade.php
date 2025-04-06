<!-- /views/posts/create.blade.php -->


@extends('layouts.app')

@section('content')
    <h1>Create a New Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="type">Type</label>
            <select id="type" name="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="news">News</option>
                <option value="book">Book</option>
                <option value="normal">Normal</option>
            </select>
        </div>

        <div id="news-post-fields" class="form-group" style="display: none;">
            <label for="image">Image</label>
            <input type="file" name="image" class="form-control">
            <label for="caption">Caption</label>
            <input type="text" name="caption" class="form-control">
        </div>

        <div id="book-post-fields" class="form-group" style="display: none;">
            <label for="pdf">PDF</label>
            <input type="file" name="pdf" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Create Post</button>
    </form>

    <script>
        document.getElementById('type').addEventListener('change', function () {
            const type = this.value;
            document.getElementById('news-post-fields').style.display = type === 'news' || type === 'normal' ? 'block' : 'none';
            document.getElementById('book-post-fields').style.display = type === 'book' ? 'block' : 'none';
        });
    </script>
@endsection