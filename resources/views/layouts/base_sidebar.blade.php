<ul class="sidebar-nav">
    <li class="sidebar-brand">
        <a href="/">
            <img src="{{ asset('logo-videodistrib.png') }}" width=32>
            {{ config('custom.titulo') }}
        </a>
    </li>
    @if(Auth::guest())
    <li><a href="{{ route('login') }}">Login</a></li>
    @else
    @yield('sidebar_items')

    <li style="margin-top: 5px; padding-top: 5px; border-top: solid; border-width: 1px; border-color: #101010;">
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
            <i class="fa fa-sign-out"></i>
            Logout
        </a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </li>
    @endif

</ul>