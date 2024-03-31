<aside class="main-sidebar sidebar-dark-primary">
    <a class="navbar-brand" href="#" style="background:#fff;padding:0;">
        <span class="navbar-brand-full"><img src="{{asset('assets2/img/sr-logo.png')}}" alt="" style="width:180px; background-repeat:none;"></span>
        {{-- <span class="navbar-brand-minimized"><img src="/assets/img/skillsrator-logo.webp" alt="" style="width:200px; background-repeat:none;"></span> --}}
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @include('layouts.menu')
            </ul>
        </nav>
    </div>
</aside>
