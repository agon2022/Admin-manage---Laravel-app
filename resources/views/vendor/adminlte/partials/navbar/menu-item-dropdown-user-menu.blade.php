@php( $logout_url = View::getSection('logout_url') ?? config('adminlte.logout_url', 'logout') )
@php( $profile_url = View::getSection('profile_url') ?? config('adminlte.profile_url', 'logout') )

@if (config('adminlte.usermenu_profile_url', false))
    @php( $profile_url = Auth::user()->adminlte_profile_url() )
@endif

@if (config('adminlte.use_route_url', false))
    @php( $profile_url = $profile_url ? route($profile_url) : '' )
    @php( $logout_url = $logout_url ? route($logout_url) : '' )
@else
    @php( $profile_url = $profile_url ? url($profile_url) : '' )
    @php( $logout_url = $logout_url ? url($logout_url) : '' )
@endif

<li class="nav-item dropdown user-menu">

    {{-- Toggler: chỉ hiển thị tên người dùng --}}
    <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
        <span>{{ Auth::user()->name }}</span>
    </a>

    {{-- Dropdown --}}
    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 160px; padding: 10px;">

        {{-- Profile Link --}}
        @if($profile_url)
            <li class="mb-2">
                <a href="{{ $profile_url }}" class="btn btn-default btn-block text-left">
                    <i class="fa fa-fw fa-user text-lightblue mr-2"></i>
                    {{ __('adminlte::menu.profile') }}
                </a>
            </li>
        @endif

        {{-- Logout Link --}}
        <li>
            <a href="#" class="btn btn-default btn-block text-left"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa fa-fw fa-power-off text-red mr-2"></i>
                {{ __('adminlte::adminlte.log_out') }}
            </a>
        </li>

        {{-- Hidden logout form --}}
        <form id="logout-form" action="{{ $logout_url }}" method="POST" style="display: none;">
            @if(config('adminlte.logout_method'))
                {{ method_field(config('adminlte.logout_method')) }}
            @endif
            {{ csrf_field() }}
        </form>
    </ul>

</li>
