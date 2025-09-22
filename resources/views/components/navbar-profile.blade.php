<a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
    <span class="avatar avatar-sm" style="background-image: url({{ Auth::user()->avatar }})"></span>
    <div class="d-none d-xl-block ps-2">
    <div>{{ Auth::user()->name }}</div>
    <div class="mt-1 small text-secondary">{{ Auth::user()->email }}</div>
    </div>
</a>
<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-popper="static">
    <a href="{{ route('users.profile') }}" class="dropdown-item">Profile</a>
    <div class="dropdown-divider"></div>
    <a onclick="event.preventDefault(); document.getElementById('logout-form').submit();" href="#" class="dropdown-item">Logout</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>