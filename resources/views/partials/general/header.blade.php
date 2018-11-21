<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li>
                    @if(Auth::user()->isAdministrator())
                        @include('partials.dashboard.common.top_dropdown_menu')
                    @else
                        @include('partials.account.common.top_dropdown_menu')
                    @endif
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->