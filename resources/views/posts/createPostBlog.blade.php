@extends('layouts.app')

@section('content')
    <h1>Create a New Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="type">Type</label>
            <select id="type" name="type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="news" {{ old('type') == 'news' ? 'selected' : '' }}>News</option>
                <option value="book" {{ old('type') == 'book' ? 'selected' : '' }}>Book</option>
                <option value="cours" {{ old('type') == 'cours' ? 'selected' : '' }}>cour</option>
            </select>
            @error('type')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="caption">Caption</label>
            <input type="text" name="caption" class="form-control" id="caption" value="{{ old('caption') }}">
            @error('caption')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- News post - multiple images -->
        <div class="form-group" id="images-field">
            <label for="images">Images (Multiple for News)</label>
            <input type="file" name="images[]" class="form-control" id="images" multiple>
            @error('images.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Book/Course post - single image -->
        <div class="form-group" id="image-field" style="display: none;">
            <label for="image">Image (Single)</label>
            <input type="file" name="image" class="form-control" id="image">
            @error('image')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group" id="pdf-field" style="display: none;">
            <label for="pdf">PDF</label>
            <input type="file" name="pdf" class="form-control" id="pdf">
            @error('pdf')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-3">Create Post</button>
    </form>

    <script>
        document.getElementById('type').addEventListener('change', function() {
            const type = this.value;
            const pdfField = document.getElementById('pdf-field');
            const imagesField = document.getElementById('images-field');
            const imageField = document.getElementById('image-field');

            // Show PDF field only for book posts
            if (type === 'book') {
                pdfField.style.display = 'block';
                imageField.style.display = 'block';
                imagesField.style.display = 'none';
            } else if (type === 'cours') {
                pdfField.style.display = 'none';
                imageField.style.display = 'block';
                imagesField.style.display = 'none';
            } else if (type === 'news') {
                pdfField.style.display = 'none';
                imageField.style.display = 'none';
                imagesField.style.display = 'block';
            } else {
                // Default state
                pdfField.style.display = 'none';
                imageField.style.display = 'none';
                imagesField.style.display = 'none';
            }
        });

        // Trigger change event on page load to ensure correct fields are visible
        document.getElementById('type').dispatchEvent(new Event('change'));
    </script>
@endsection