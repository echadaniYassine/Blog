<!-- /views/posts/show.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Post Card -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>{{ ucfirst($post->type) }} Post</h4>
                <div>
                    @if (auth()->check() && (auth()->id() == $post->user_id || auth()->user()->role == 'admin'))
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            <div class="card-body">
                @if($post->image)
                    <div class="text-center mb-3">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->caption }}" 
                             class="img-fluid" style="max-height: 300px;">
                    </div>
                @endif
                
                <h5 class="card-title">{{ $post->caption }}</h5>
                <p class="card-text"><small class="text-muted">Posted by: {{ $post->user->name }} on {{ $post->created_at->format('M d, Y') }}</small></p>
                
                <div class="d-flex gap-2 mt-3">
                    <form action="{{ route('likes.toggle', $post->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" 
                            class="btn {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'btn-danger' : 'btn-primary' }}">
                            <i class="fas fa-heart"></i> 
                            {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                            <span class="badge bg-light text-dark">{{ $post->likes->count() }}</span>
                        </button>
                    </form>
                    
                    <div class="dropdown">
                        <button type="button" class="btn btn-primary" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-paper-plane"></i> Share
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                            <li><a class="dropdown-item" href="https://twitter.com/share?url={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank"><i class="fab fa-twitter"></i> Twitter</a></li>
                            <li><a class="dropdown-item" href="https://wa.me/?text={{ urlencode(request()->fullUrl()) }}"
                                    target="_blank"><i class="fab fa-whatsapp"></i> WhatsApp</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PDF Display for Book Type Posts -->
        @if($post->type == 'book' && $post->pdf)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Book PDF</h5>
                </div>
                <div class="card-body">
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ asset('storage/' . $post->pdf) }}" allowfullscreen></iframe>
                    </div>
                    <div class="mt-3">
                        <a href="{{ asset('storage/' . $post->pdf) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-download"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Comments Section -->
        <div class="card">
            <div class="card-header">
                <h5>Comments</h5>
            </div>
            <div class="card-body">
                @if($post->comments->count() > 0)
                    @foreach ($post->comments as $comment)
                        <div class="comment border p-2 my-2 rounded">
                            <p><strong>{{ $comment->user->name }}</strong>: {{ $comment->content }}</p>
                            
                            <form action="{{ route('comments.likes.toggle', $comment->id) }}" method="POST" class="d-inline">
                                @csrf
                                @auth
                                    <button type="submit"
                                        class="btn btn-sm {{ $comment->likes->contains('user_id', auth()->id()) ? 'btn-danger' : 'btn-outline-primary' }}">
                                        {{ $comment->likes->contains('user_id', auth()->id()) ? 'Unlike' : 'Like' }}
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-outline-primary"
                                        onclick="return confirm('Please login to like this comment.')">
                                        Like
                                    </button>
                                @endauth
                                <span class="ms-1">Likes: {{ $comment->likes->count() }}</span>
                            </form>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">No comments yet.</p>
                @endif

                <form action="{{ route('comments.store', $post->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <textarea name="content" class="form-control" placeholder="Add a comment" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Submit Comment</button>
                </form>
            </div>
        </div>
    </div>
@endsection