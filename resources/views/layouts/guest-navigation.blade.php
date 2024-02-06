<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('welcome') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
            </div>

            <div class="relative flex ml-3 space-x-4">
                <x-nav-link :navigate="false" href="{{ route('filament.admin.auth.login') }}" :active="request()->routeIs('filament.admin.auth.login')">
                    {{ __('Admin Panel') }}
                </x-nav-link>

                <x-nav-link :navigate="false" href="{{ route('filament.employee.auth.login') }}" :active="request()->routeIs('filament.employee.auth.login')">
                    {{ __('Employee Panel') }}
                </x-nav-link>
            </div>
        </div>
    </div>

</nav>
