<header>
    <div class="topbar d-flex align-items-center" @if (is_kitchen_panel()) style="left: 0;" @endif>
        <nav class="navbar navbar-expand">
            <div class="mobile-toggle-menu">
                <i class="fa-solid fa-bars"></i>
            </div>

            <div class="search-bar flex-grow-1">
                <div class="position-relative search-bar-box">
                    <input id="searchItems" type="search" class="form-control search-control"
                           placeholder="Type to search...">
                    <span class="position-absolute top-50 search-show translate-middle-y">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <span class="position-absolute top-50 search-close translate-middle-y">
                        <i class="fa-solid fa-xmark"></i>
                    </span>
                </div>
            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item mobile-search-icon">
                        <a class="nav-link" href="#">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </a>
                    </li>

                    <li>
                        <div class="header-icon theme-mode ms-md-2 me-1" id="switchTheme" title="Switch Theme">
                            <img src="{{ asset('build/assets/backend/images/icons/moon.svg') }}" alt="Moon" />
                        </div>
                    </li>


                    @if (!is_kitchen_panel() && !Request::is('staff/pos'))
                        <li class="nav-item dropdown dropdown-large">
                            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown"
                               aria-expanded="false">
                                <span class="alert-count">{{ count($stocks) }}</span>
                                <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.14614 1.24812C7.4433 0.516158 8.16138 0 9 0C9.83863 0 10.5567 0.516158 10.8539 1.24812C13.8202 2.06072 16 4.77579 16 8V12.6972L17.8321 15.4453C18.0366 15.7522 18.0557 16.1467 17.8817 16.4719C17.7077 16.797 17.3688 17 17 17H12.4646C12.2219 18.6961 10.7632 20 9 20C7.23677 20 5.77806 18.6961 5.53545 17H1C0.631206 17 0.292346 16.797 0.118327 16.4719C-0.0556921 16.1467 -0.0366195 15.7522 0.167951 15.4453L2 12.6972V8C2 4.77579 4.17983 2.06072 7.14614 1.24812ZM7.58535 17C7.79127 17.5826 8.34689 18 9 18C9.65311 18 10.2087 17.5826 10.4146 17H7.58535ZM9 3C6.23858 3 4 5.23858 4 8V13C4 13.1974 3.94156 13.3904 3.83205 13.5547L2.86852 15H15.1315L14.168 13.5547C14.0584 13.3904 14 13.1974 14 13V8C14 5.23858 11.7614 3 9 3Z"
                                        fill="#0D0D0D"/>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="msg-header">
                                    <p class="msg-header-title">Stock Out Items</p>
                                    {{-- <p class="msg-header-clear ms-auto">Marks all as read</p> --}}
                                </div>
                                <div class="header-message-list" id="stock_out_item_list">
                                    @if(count($stocks) > 0)
                                        @foreach ($stocks as $stock)
                                            <a class="dropdown-item" href="javascript:;">
                                                <div class="d-flex align-items-center">
                                                    <div class="notify">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="msg-name">{{ $stock->ingredient->name }}</h6>
                                                        <span class="msg-time">{{ $stock->qty_amount }}</span>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        <div class="text-danger text-center py-2">Stock out items not available</div>
                                    @endif
                                </div>
                                <a href="{{ route('ingredient.index') }}">
                                    <div class="text-center msg-footer">View Stock</div>
                                </a>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="user-box dropdown">
                <a class="d-flex align-items-center nav-link dropdown-toggle-nocaret" href="#" role="button"
                   data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ uploaded_file(auth('staff')->user()->image) }}" class="user-img" alt="user avatar">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" style="right: 10px">
                    <li class="user-details">
                        <div class="user-info ">
                            <p class="user-name mb-0">{{ auth('staff')->user()->name }}</p>
                            <p class="designattion mb-0">{{ format_date() }}</p>
                        </div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.profile.index') }}">
                            <i class="fa-solid fa-user"></i>
                            <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.password.update') }}">
                            <i class="fa-solid fa-key"></i>
                            <span>Password Update</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('staff.logout') }}" id="logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>

        </nav>
    </div>
</header>
