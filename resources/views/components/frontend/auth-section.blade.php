<section class="section bg-light">
    <div class="container space-y-5">
        <div class="grid grid-cols-12 gap-x-8 gap-y-10">
            <div class="col-span-12 xl:col-span-3">
                <div class="bg-white border border-gray-200 rounded-md">
                    <div class="flex items-center p-4">
                        <div>
                            <img src="{{ uploaded_file(auth()->user()->image) }}" class="w-16 h-16 rounded-full object-cover" alt="" />
                        </div>
                        <div class="ml-3">
                            <div class="flex items-center space-x-1">
                                <p class="font-semibold text-primary-500">{{ auth()->user()->full_name }}</p>
                                @if (auth()->user()->hasVerifiedEmail())
                                    <i data-feather="check-circle" class="w-4 h-4 text-success-500"></i>
                                @endif

                            </div>
                            <small class="text-sm text-gray-500">{{ auth()->user()->email }}</small>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4">
                        <p class="">Accounts Credits</p>
                        <h6 class="text-primary-500">£52.25</h6>
                    </div>
                    <ul>
                        <li>
                            <a href="{{ route('profile') }}" @class(['profile-link', 'active' => request()->is('profile')]) wire:navigate>
                                <i data-feather="info" class="w-4 h-4"></i>
                                <span>About</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}" @class(['profile-link', 'active' => request()->is('profile/edit')]) wire:navigate>
                                <i data-feather="edit" class="w-4 h-4"></i>
                                <span>Edit Profile</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('change-password') }}" @class(['profile-link', 'active' => request()->is('change-password')]) wire:navigate>
                                <i data-feather="lock" class="w-4 h-4"></i>
                                <span>Change Password</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('order') }}" @class(['profile-link', 'active' => request()->is('order-history*')]) wire:navigate>
                                <i data-feather="shopping-bag" class="w-4 h-4"></i>
                                <span>Order History</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('address') }}" @class(['profile-link', 'active' => request()->is('address')]) wire:navigate>
                                <i data-feather="map-pin" class="w-4 h-4"></i>
                                <span>Address</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="profile-link">
                                <i data-feather="log-out" class="w-4 h-4"></i>
                                <span>logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-span-12 xl:col-span-9">
                {{ $slot }}
            </div>
        </div>
    </div>
</section>
