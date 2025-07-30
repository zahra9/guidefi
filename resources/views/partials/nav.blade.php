<nav class="sidebar sidebar-info" id="sidebar">
    <div class="sidebar-content-wrapper sidebar-offcanvas">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="/">
                    <i class="ti-home menu-icon"></i>
                    <span class="menu-title">Financial Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#financial_analysis" aria-expanded="false" aria-controls="ui-apps">
                    <i class="ti-user menu-icon"></i>
                    <span class="menu-title">Advisor</span>
                    <i class="ti-angle-down menu-arrow"></i>

                </a>
                <div class="collapse" id="financial_analysis">
                    <ul class="nav flex-column sub-menu">

                        <li class="nav-item">
                            <a class="nav-link" href="/advisor-list">
                                <span class="menu-title">Advisor List</span>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" href="/advisor-requests">
                                <span class="menu-title">Advisor Applications</span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>



            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#financial_reports" aria-expanded="false" aria-controls="ui-basic">
                    <!-- <i class="fas fa-chart-bar menu-icon"></i> -->
                    <!-- <img src="images/file-icons/report.png" class="menu-icon" style="width: 18px;height: 18px;"> -->
                    <i class="ti-user menu-icon"></i>
                    <span class="menu-title">Clients</span>
                    <i class="ti-angle-down menu-arrow-1"></i>
                </a>
                <div class="collapse" id="financial_reports">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/client-list')}}">
                                <!-- <i class="ti-view-list-alt menu-icon"></i> -->
                                <span class="menu-title">Client List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/client-requests')}}">
                                <!-- <i class="ti-user menu-icon"></i> -->
                                <span class="menu-title">Client Request</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item videos">
                <a class="nav-link" data-toggle="collapse" href="#physician" aria-expanded="false" aria-controls="ui-advanced">
                    <!-- <i class="fas fa-user-md menu-icon"></i> -->
                    <!-- <img src="images/file-icons/physician.png" class="menu-icon" style="width: 18px;height: 18px;"> -->
                    <i class="ti-video-camera menu-icon"></i>
                    <span class="menu-title">Videos</span>
                    <i class="ti-angle-down menu-arrow-3"></i>
                </a>
                <div class="collapse" id="physician">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('videos.index')}}">
                                <!-- <i class="ti-view-list-alt menu-icon"></i> -->
                                <span class="menu-title">Video List</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item videos">
                <a class="nav-link" data-toggle="collapse" href="#quote" aria-expanded="false" aria-controls="ui-advanced">
                    <!-- <i class="fas fa-user-md menu-icon"></i> -->
                    <!-- <img src="images/file-icons/physician.png" class="menu-icon" style="width: 18px;height: 18px;"> -->
                    <i class="ti-video-camera menu-icon"></i>
                    <span class="menu-title">Quote</span>
                    <i class="ti-angle-down menu-arrow-3"></i>
                </a>
                <div class="collapse" id="quote">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('quotes.index')}}">
                                <!-- <i class="ti-view-list-alt menu-icon"></i> -->
                                <span class="menu-title">Quote List</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#blog" aria-expanded="false" aria-controls="ui-basic">
                    <i class="ti-user menu-icon"></i>
                    <span class="menu-title">Blog</span>
                    <i class="ti-angle-down menu-arrow-6"></i>
                </a>
                <div class="collapse" id="blog">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.index') }}" class="list-group-item list-group-item-action @if(Request::route()->getName() === 'blogetc.admin.index') active @endif  ">
                                <!-- <i class="fa fa-th fa-fw" aria-hidden="true"></i> -->
                                All Posts
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.create_post') }}" class="list-group-item list-group-item-action  @if(Request::route()->getName() === 'blogetc.admin.create_post') active @endif">
                                <!-- <i class="fa fa-plus fa-fw" aria-hidden="true"></i> -->
                                Add Post
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.comments.index') }}" class="list-group-item list-group-item-action  @if(Request::route()->getName() === 'blogetc.admin.comments.index' && !Request::get(" waiting_for_approval")) active @endif ">
                                <!-- <i class=" fa fa-fw fa-comments" aria-hidden="true"></i> -->
                                All Comments
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.categories.index') }}" class="list-group-item list-group-item-action  @if(Request::route()->getName() === 'blogetc.admin.categories.index') active @endif">
                                <!-- <i class="fa fa-object-group fa-fw" aria-hidden="true"></i> -->
                                All Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.categories.create_category') }}" class="list-group-item list-group-item-action  @if(Request::route()->getName() === 'blogetc.admin.categories.create_category') active @endif  ">
                                <!-- <i class="fa fa-plus fa-fw" aria-hidden="true"></i> -->
                                Add Category
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.images.all') }}" class="list-group-item list-group-item-action  @if(Request::route()->getName() === 'blogetc.admin.images.all') active @endif  ">
                                <!-- <i class="fa fa-picture-o fa-fw" aria-hidden="true"></i> -->
                                View All
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('blogetc.admin.images.upload') }}" class="list-group-item list-group-item-action  @if(Request::route()->getName() === 'blogetc.admin.images.upload') active @endif  ">
                                <!-- <i class="fa fa-upload fa-fw" aria-hidden="true"></i> -->
                                Upload
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/call-list')}}">
                    <i class="ti-mobile menu-icon"></i>
                    <span class="menu-title">Discovery Call Logs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/payment-logs')}}">
                    <i class="ti-money menu-icon"></i>
                    <span class="menu-title">Payment Logs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/feedback-list')}}">
                    <i class="ti-comments menu-icon"></i>
                    <span class="menu-title">Feedback</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/signup-request')}}">
                    <i class="ti-user menu-icon"></i>
                    <span class="menu-title">Sign Up Requests</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ url('/setting-list')}}">
                    <i class="ti-settings menu-icon"></i>
                    <span class="menu-title">Settings</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/notifications')}}">
<!--                    <i class="ti-settings menu-icon"></i>-->
                    <i class="ti-bell menu-icon"></i>
                    <span class="menu-title">Notifications</span>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="{{ url('/blog_admin')}}">
                    <i class="ti-settings menu-icon"></i>
                    <span class="menu-title">Blog</span>
                </a>
            </li> -->

            


        </ul>
    </div>
</nav>