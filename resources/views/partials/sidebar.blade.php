<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('home.index')}}" class="brand-link">
        <img src="{{asset('images/fitness.png')}}" alt="Gym Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Gym Management</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{auth()->user()?->avatar?->attachment_url}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{auth()->user()?->name}}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                {{--                <li class="nav-item">--}}
                {{--                    <a href="#" class="nav-link active">--}}
                {{--                        <i class="nav-icon fas fa-tachometer-alt"></i>--}}
                {{--                        <p>--}}
                {{--                            Managers--}}
                {{--                            <i class="right fas fa-angle-left"></i>--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                    <ul class="nav nav-treeview">--}}
                {{--                        <li class="nav-item">--}}
                {{--                            <a href="./index.html" class="nav-link">--}}
                {{--                                <i class="far fa-circle nav-icon"></i>--}}
                {{--                                <p>City</p>--}}
                {{--                            </a>--}}
                {{--                        </li>--}}
                {{--                        <li class="nav-item">--}}
                {{--                            <a href="./index2.html" class="nav-link active">--}}
                {{--                                <i class="far fa-circle nav-icon"></i>--}}
                {{--                                <p>Gym</p>--}}
                {{--                            </a>--}}
                {{--                        </li>--}}
                {{--                    </ul>--}}
                {{--                </li>--}}

                @foreach($links as $link)
                    @role($link['allowedRoles'])
                    <li class="nav-item {{array_key_exists('nestedItems', $link) && str_contains(Route::currentRouteName(), $link['resource']) ? 'menu-open' : ''}}">
                        <a href="{{array_key_exists('nestedItems', $link) ? '#' : route($link['resource'].'.index')}}"
                            class="nav-link {{!array_key_exists('nestedItems', $link)
                                              ? Route::is($link['resource'].'.index') ? 'active' : ''
                                              : ''}}">
                            <i class="nav-icon {{$link['iconClassName']}}"></i>
                            <p>
                                {{Str::ucfirst(Str::replace('-',' ',$link['resource']))}}
                                @if (array_key_exists('nestedItems', $link))
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </p>
                        </a>

                        @if (array_key_exists('nestedItems', $link))
                            <ul class="nav nav-treeview">
                                @foreach($link['nestedItems'] as $item)

                                    @role($item['allowedRoles'])
                                    <li class="nav-item">
                                        <a href="{{route(Str::singular($item['resource']).'-'.$link['resource'].'.index')}}"
                                           class="nav-link {{Route::is(Str::singular($item['resource']).'-'.$link['resource'].'.index') ? 'active' : ''}}">
                                            <i class="{{$item['iconClassName']}} nav-icon"></i>
                                            <p>
                                                {{Str::ucfirst(Str::replace('-',' ',$item['resource']))}}
                                            </p>
                                        </a>
                                    </li>
                                    @endrole
                                @endforeach
                            </ul>
                        @endif
                    </li>
                    @endrole
                @endforeach
                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Cities--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Gyms--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Users--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Training Packages--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Coaches--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Attendance--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

                {{--                <li class="nav-item">--}}
                {{--                    <a href="pages/widgets.html" class="nav-link">--}}
                {{--                        <i class="nav-icon fas fa-th"></i>--}}
                {{--                        <p>--}}
                {{--                            Revenue--}}
                {{--                        </p>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
