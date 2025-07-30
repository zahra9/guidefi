<!-- partial:partials/_navbar.html -->
@php
use App\Models\Notification;
$notifications = Notification::latest()->take(5)->get();
@endphp
<nav class="navbar p-0 d-flex flex-row">
    <div class="navbar-menu-wrapper d-flex align-items-center">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="ti-align-left"></span>
        </button>
        <div class="navbar-brand-wrapper d-flex align-items-center justify-content-center">
            <a class="navbar-brand brand-logo" href="#"><img src="{{asset('assets/images/logo-01.png')}}" width="150px" alt="logo" /></a>
            <a class="navbar-brand brand-logo-mini" href="#"><img src="{{asset('assets/images/logo-01.png')}}" width="150px" alt="logo" /></a>
        </div>
        <ul class="navbar-nav navbar-nav-right ml-auto">
{{--            <li class="nav-item nav-search d-none d-sm-flex">--}}
{{--                <div class="nav-link d-flex justify-content-center align-items-center">--}}
{{--                    <input type="text" class="form-control hidden" id="search-field" placeholder="Search...">--}}
{{--                    <i class="ti-search mx-0" id="search-icon"></i>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link count-indicator dropdown-toggle d-flex justify-content-center align-items-center" id="messageDropdown" href="#" data-toggle="dropdown">--}}
{{--                    <i class="ti-comment mx-0"></i>--}}
{{--                    <span class="count"></span>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="messageDropdown">--}}
{{--                    <p class="mb-0 font-weight-normal float-left dropdown-header">Messages</p>--}}
{{--                    <a class="dropdown-item preview-item">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <img src="https://via.placeholder.com/36x36" alt="image" class="profile-pic">--}}
{{--                        </div>--}}
{{--                        <div class="preview-item-content flex-grow">--}}
{{--                            <h6 class="preview-subject ellipsis font-weight-normal">Guidefi Admin--}}
{{--                            </h6>--}}
{{--                            <p class="font-weight-light small-text text-muted mb-0">--}}
{{--                                The meeting is cancelled--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <img src="https://via.placeholder.com/36x36" alt="image" class="profile-pic">--}}
{{--                        </div>--}}
{{--                        <div class="preview-item-content flex-grow">--}}
{{--                            <h6 class="preview-subject ellipsis font-weight-normal">Tim Cook--}}
{{--                            </h6>--}}
{{--                            <p class="font-weight-light small-text text-muted mb-0">--}}
{{--                                New product launch--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <img src="https://via.placeholder.com/36x36" alt="image" class="profile-pic">--}}
{{--                        </div>--}}
{{--                        <div class="preview-item-content flex-grow">--}}
{{--                            <h6 class="preview-subject ellipsis font-weight-normal"> Johnson--}}
{{--                            </h6>--}}
{{--                            <p class="font-weight-light small-text text-muted mb-0">--}}
{{--                                Upcoming board meeting--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </li>--}}
<!--            <li class="nav-item dropdown">-->
<!--                <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center" id="notificationDropdown" href="#" data-toggle="dropdown">-->
<!--                    <i class="ti-bell mx-0"></i>-->
<!--                    <span class="count"></span>-->
<!--                </a>-->
<!--                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="notificationDropdown">-->
<!--                    <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>-->
<!--                  -->
<!--                    @if(!is_null($notifications))-->
<!--                    @foreach($notifications as $notification)-->
<!--                    <a class="dropdown-item preview-item" href="{{route('notifications.index')}}">-->
<!--                        <div class="preview-thumbnail">-->
<!--                            <div class="preview-icon bg-gradient-warning">-->
<!--                                <i class="ti-info mx-0"></i>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="preview-item-content">-->
<!--                            <h6 class="preview-subject font-weight-normal">{{$notification->description}}</h6>-->
<!--                            <p class="font-weight-light small-text mb-0 text-muted">-->
<!--                                {{$notification->created_at->diffForHumans()}}-->
<!--                            </p>-->
<!--                        </div>-->
<!--                    </a>-->
<!--                    @endforeach-->
<!--                    @endif-->
<!---->
<!--                </div>-->
<!--            </li>-->
            <li class="nav-item nav-profile dropdown">
                <a class="nav-link" href="#" data-toggle="dropdown" id="profileDropdown">
                    <img src="https://via.placeholder.com/30x30" alt="profile" />
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                   <!-- <a href="{{url('/notification')}}" class="dropdown-item">
                        <i class="ti-bell text-primary"></i>
                        Notifications
                    </a>-->
                    <a href="{{url('/setting-list')}}" class="dropdown-item">
                        <i class="ti-settings text-primary"></i>
                        Settings
                    </a>
                    <a href="{{url('/logout')}}" class="dropdown-item">
                        <i class="ti-new-window text-primary"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="ti-menu"></span>
        </button>
    </div>
</nav>
<!-- partial -->