@php
$user = Auth::user();
$userRole = $user && $user->roles->isNotEmpty() ? $user->roles[0]->name : 'No Role';
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-0 sm:px-6 lg:px-8">
        <div class="flex items-center h-16">
            <!-- Left: Logo -->
            <div class="flex items-center">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none">
                        <span class="site-logo text-xl md:text-2xl lg:text-3xl text-cyan-600 font-bold text-gray-800">CVHRDC</span>
                    </a>
                </div>
            </div>

            <!-- Center: Navigation Links (desktop) -->
            <div class="hidden sm:flex sm:items-center sm:justify-center sm:flex-1 space-x-8">
                <x-nav-link
                    class="text-decoration-none text-xs md:text-sm lg:text-base hover:underline transition duration-150"
                    :href="route('rmc.dashboard')" :active="request()->routeIs('rmc.dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>

                <x-nav-link
                    class="text-decoration-none text-xs md:text-sm lg:text-base hover:underline transition duration-150"
                    :href="route('rmc.calls.index')" :active="request()->routeIs('rmc.calls.*')">
                    Calls for Proposals
                </x-nav-link>

                <x-nav-link
                    class="text-decoration-none text-xs md:text-sm lg:text-base hover:underline transition duration-150"
                    :href="route('rmc.submissions.index')" :active="request()->routeIs('rmc.submissions.*')">
                    Proposal Submissions
                </x-nav-link>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div class="text-capitalize">{{ $user->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')"
                            class="text-decoration-none hover:text-blue-600 hover:underline transition duration-150">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                class="text-decoration-none hover:text-red-600 hover:underline transition duration-150"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>

                        <x-dropdown-link
                            class="text-decoration-none hover:text-red-600 hover:underline transition duration-150">
                            Logged in as {{ $userRole }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('rmc.dashboard')" :active="request()->routeIs('rmc.dashboard')"
                class="text-sm hover:text-blue-600 hover:underline transition duration-150">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('rmc.calls.index')" :active="request()->routeIs('rmc.calls.*')"
                class="text-sm hover:text-blue-600 hover:underline transition duration-150">
                {{ __('Calls for Proposals') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('rmc.submissions.index')" :active="request()->routeIs('rmc.submissions.*')"
                class="text-sm hover:text-blue-600 hover:underline transition duration-150">
                {{ __('Submissions') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ $user->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ $user->email }}</div>
                <div class="font-medium text-sm text-gray-500">Role: {{ $userRole }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="hover:text-blue-600 hover:underline transition duration-150">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        class="hover:text-blue-600 hover:underline transition duration-150"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

