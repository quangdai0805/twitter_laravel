<div class="row border-bottom white-bg">
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-header">
            <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <i class="fa fa-reorder"></i>
            </button>
            @if(Auth::check())
                <a href="#" class="navbar-brand">Welcome, {{ Auth::user()->name }}</a>          
            @else
                <a href="#" class="navbar-brand">Welcome</a>
            @endif
           
        </div>
        <div class="navbar-collapse collapse" id="navbar">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a aria-expanded="false" role="button" href="{{ route('accounts.index') }}"> Quản lý Accounts</a>
                </li>
                {{-- <li class="dropdown">
                    <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Menu item <span class="caret"></span></a>
                    <ul role="menu" class="dropdown-menu">
                        <li><a href="">Menu item</a></li>
                        <li><a href="">Menu item</a></li>
                        <li><a href="">Menu item</a></li>
                        <li><a href="">Menu item</a></li>
                    </ul>
                </li> --}}
            </ul>
            <ul class="nav navbar-top-links navbar-right">
                
                    @if(Auth::check())
                        <li>Welcome, {{ Auth::user()->name }}</li>
                        <li><a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i>Logout</a></li>
                    @else
                        <li><a href="{{ route('login') }}"><i class="fa fa-sign-out"></i>Login</a></li>
                    @endif
            </ul>
        </div>
    </nav>
</div>