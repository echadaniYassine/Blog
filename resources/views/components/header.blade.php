<!-- /views/components/header.blade.php -->


<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">Blog App</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Left side links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('posts.index', ['type' => 'news']) }}">News</a>
                </li>
                <li class="nav-item"><a class="nav-link" href="{{ route('posts.index', ['type' => 'book']) }}">Books</a>
                </li>
                <li class="nav-item"><a class="nav-link"
                        href="{{ route('posts.index', ['type' => 'normal']) }}">Normal</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('members') }}">Members</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('posts.createPostBlog') }}">Create Post</a>

            </ul>

            <!-- Right side auth -->
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item d-flex align-items-center">
                        <a class="nav-link d-flex align-items-center gap-2"
                            href="{{ route('user.profile', auth()->id()) }}">
                            <!-- Profile Image -->
                            @if (auth()->user()->profile_image)
                                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile Image"
                                    class="rounded-circle" width="30" height="30">
                            @else
                                <img src="https://via.placeholder.com/30" alt="Default Image" class="rounded-circle"
                                    width="30" height="30">
                            @endif
                            <!-- User Name -->
                            <span>{{ auth()->user()->name }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
