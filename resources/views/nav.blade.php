@php
    use App\Models\Permission;
    $pages = Permission::where('user_id', session('user_id'))->select('page')->pluck('page');
    $pages = json_decode($pages) != null ? json_decode($pages) : [];

@endphp
<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarnav">
        <li class="sidebar-item">
            <a class="sidebar-link" href="/home" aria-expanded="false">
                <span>
                    <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>
        @if (in_array('property', $pages) || session('acc_type') == 'landlord')
            <li class="sidebar-item">
                <a class="sidebar-link" href="/properties" aria-expanded="false">
                    <span>
                        <i class="ti ti-building"></i>
                    </span>
                    <span class="hide-menu">Properties</span>
                </a>
            </li>
        @endif
        @if (in_array('tenant', $pages) || session('acc_type') == 'landlord')
            <li class="sidebar-item">
                <a class="sidebar-link" href="/tenants" aria-expanded="false">
                    <span>
                        <i class="ti ti-users"></i>
                    </span>
                    <span class="hide-menu">Tenants</span>
                </a>
            </li>
        @endif
        {{-- @if (in_array('tenant', $pages) || session('acc_type') == 'landlord')
        <li class="sidebar-item">
            <a class="sidebar-link" href="/tenancy" aria-expanded="false">
                <span>
                    <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Tenancy</span>
            </a>
        </li>
        @endif --}}
        @if (in_array('invoice', $pages) || session('acc_type') == 'landlord')
            <li class="sidebar-item">
                <a class="sidebar-link" href="/invoices" aria-expanded="false">
                    <span>
                        <i class="ti ti-file-dollar"></i>
                    </span>
                    <span class="hide-menu">Invoices</span>
                </a>
            </li>
        @endif
        @if (in_array('repair', $pages) || session('acc_type') == 'landlord')
            <li class="sidebar-item">
                <a class="sidebar-link" href="/repairs" aria-expanded="false">
                    <span>
                        <i class="ti ti-tool"></i>
                    </span>
                    <span class="hide-menu">Repair and Maintenance</span>
                </a>
            </li>
        @endif
        <li class="sidebar-item">
            <a class="sidebar-link" href="/profile" aria-expanded="false">
                <span>
                    <i class="ti ti-settings"></i>
                </span>
                <span class="hide-menu">Settings</span>
            </a>
        </li>

    </ul>
</nav>
