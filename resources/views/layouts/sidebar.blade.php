<aside class="custom-sidebar" id="customSidebar">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="sidebar-logo">
            <span class="sidebar-title">حسابیر</span>
        </a>
        <button id="sidebarToggleBtn" class="sidebar-toggle-btn" aria-label="بستن منو">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    @auth
    <div class="sidebar-user">
        <img src="{{ asset('img/user.png') }}" class="sidebar-user-img" alt="User Image">
        <div class="sidebar-user-info">
            <div class="sidebar-user-name">{{ Auth::user()->name ?? 'کاربر' }}</div>
            <div class="sidebar-user-menu" id="userMenuBtn">
                <i class="fas fa-angle-down"></i>
            </div>
        </div>
        <div class="sidebar-user-dropdown" id="userMenuDropdown">
            <a href="{{ route('profile.edit') }}"><i class="fas fa-user-edit"></i> ویرایش پروفایل</a>
            <a href="{{ route('settings.company') }}"><i class="fas fa-cog"></i> تنظیمات</a>
            <a href="#" class="logout" onclick="event.preventDefault();document.getElementById('logout-form-sidebar').submit();"><i class="fas fa-sign-out-alt"></i> خروج</a>
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
    @endauth

    <nav class="sidebar-menu" id="sidebar-menu">
        <ul>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="fas fa-home sidebar-icon icon-blue"></i>
                    <span>داشبورد</span>
                </a>
            </li>
            <li class="has-submenu {{ (request()->is('products*') || request()->is('stocks*') || request()->is('categories*') || request()->is('services*')) ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-warehouse sidebar-icon icon-orange"></i>
                    <span>کالا و خدمات</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('products.create') }}" class="{{ request()->routeIs('products.create') ? 'active' : '' }}"><i class="fas fa-plus icon-green"></i> افزودن محصول</a></li>
                    <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active' : '' }}"><i class="fas fa-box icon-blue"></i> لیست محصولات</a></li>
                    <li><a href="{{ route('services.create') }}" class="{{ request()->routeIs('services.create') ? 'active' : '' }}"><i class="fas fa-plus icon-cyan"></i> افزودن خدمات</a></li>
                    <li><a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.index') ? 'active' : '' }}"><i class="fas fa-box icon-orange"></i> لیست خدمات</a></li>
                    {{-- <li><a href="{{ route('stocks.in') }}" class="{{ request()->routeIs('stocks.in') ? 'active' : '' }}"><i class="fas fa-arrow-down icon-purple"></i> ورود کالا</a></li> --}}
                    {{-- <li><a href="{{ route('stocks.out') }}" class="{{ request()->routeIs('stocks.out') ? 'active' : '' }}"><i class="fas fa-arrow-up icon-red"></i> خروج کالا</a></li> --}}
                    {{-- <li><a href="{{ route('stocks.transfer') }}" class="{{ request()->routeIs('stocks.transfer') ? 'active' : '' }}"><i class="fas fa-exchange-alt icon-yellow"></i> انتقال بین انبارها</a></li> --}}
                    <li><a href="{{ route('categories.create') }}" class="{{ request()->routeIs('categories.create') ? 'active' : '' }}"><i class="fas fa-tags icon-green"></i> دسته‌بندی</a></li>
                    <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.index') ? 'active' : '' }}"><i class="fas fa-list-ul icon-blue"></i> لیست دسته‌بندی</a></li>
                </ul>
            </li>
            <li class="has-submenu {{ request()->is('persons*') ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-users sidebar-icon icon-cyan"></i>
                    <span>اشخاص</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('persons.create') }}" class="{{ request()->routeIs('persons.create') ? 'active' : '' }}"><i class="fas fa-user-friends icon-cyan"></i> شخص جدید</a></li>
                    <li><a href="{{ route('persons.index') }}" class="{{ request()->routeIs('persons.index') ? 'active' : '' }}"><i class="fas fa-list icon-blue"></i> لیست اشخاص</a></li>
                    <li><a href="{{ route('shareholders.index') }}" class="{{ request()->routeIs('shareholders.*') ? 'active' : '' }}"><i class="fas fa-user-shield icon-indigo"></i> سهامداران</a></li>
                    <li><a href="{{ route('sellers.create') }}" class="{{ request()->routeIs('sellers.create') ? 'active' : '' }}"><i class="fas fa-store icon-green"></i> فروشنده جدید</a></li>
                    <li><a href="{{ route('sellers.index') }}" class="{{ request()->routeIs('sellers.index') ? 'active' : '' }}"><i class="fas fa-user-tie icon-purple"></i> لیست فروشندگان</a></li>
                    <li><a href="{{ route('persons.suppliers') }}" class="{{ request()->routeIs('persons.suppliers') ? 'active' : '' }}"><i class="fas fa-truck icon-orange"></i> تامین‌کنندگان</a></li>
                </ul>
            </li>
            <li class="has-submenu {{ request()->is('sales*') ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-shopping-cart sidebar-icon icon-green"></i>
                    <span>فروش</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('sales.create') }}" class="{{ request()->routeIs('sales.create') ? 'active' : '' }}">ثبت فروش جدید</a></li>
                    <li><a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">لیست فاکتورها</a></li>
                    <li><a href="{{ route('sales.quick') }}" class="{{ request()->routeIs('sales.quick') ? 'active' : '' }}"><i class="fas fa-bolt icon-yellow"></i> فروش سریع</a></li>
                    <li><a href="{{ route('sales.returns.index') }}" class="{{ request()->routeIs('sales.returns.*') ? 'active' : '' }}"><i class="fas fa-undo icon-purple"></i> برگشت از فروش</a></li>
                </ul>
            </li>
            <li class="has-submenu {{ request()->is('accounting*') ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-calculator sidebar-icon icon-indigo"></i>
                    <span>حسابداری</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('accounting.journal') }}" class="{{ request()->routeIs('accounting.journal') ? 'active' : '' }}"><i class="fas fa-book icon-purple"></i> دفتر روزنامه</a></li>
                    <li><a href="{{ route('accounting.ledger') }}" class="{{ request()->routeIs('accounting.ledger') ? 'active' : '' }}"><i class="fas fa-book-open icon-cyan"></i> دفتر کل</a></li>
                    <li><a href="{{ route('accounting.balance') }}" class="{{ request()->routeIs('accounting.balance') ? 'active' : '' }}"><i class="fas fa-balance-scale icon-yellow"></i> تراز آزمایشی</a></li>
                </ul>
            </li>
            <li class="has-submenu {{ request()->is('financial*') ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-coins sidebar-icon icon-yellow"></i>
                    <span>امور مالی</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('financial.incomes.index') }}" class="{{ request()->routeIs('financial.income') ? 'active' : '' }}"><i class="fas fa-arrow-down icon-blue"></i> درآمدها</a></li>
                    <li><a href="{{ route('financial.expenses') }}" class="{{ request()->routeIs('financial.expenses') ? 'active' : '' }}"><i class="fas fa-arrow-up icon-red"></i> هزینه‌ها</a></li>
                    <li><a href="{{ route('financial.banking') }}" class="{{ request()->routeIs('financial.banking') ? 'active' : '' }}"><i class="fas fa-university icon-cyan"></i> عملیات بانکی</a></li>
                    <li><a href="{{ route('financial.cheques') }}" class="{{ request()->routeIs('financial.cheques') ? 'active' : '' }}"><i class="fas fa-money-check-alt icon-indigo"></i> مدیریت چک‌ها</a></li>
                </ul>
            </li>
            <li class="has-submenu {{ request()->is('reports*') ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-chart-bar sidebar-icon icon-purple"></i>
                    <span>گزارشات</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('reports.sales') }}" class="{{ request()->routeIs('reports.sales') ? 'active' : '' }}"><i class="fas fa-chart-line icon-green"></i> گزارش فروش</a></li>
                    <li><a href="{{ route('reports.inventory') }}" class="{{ request()->routeIs('reports.inventory') ? 'active' : '' }}"><i class="fas fa-boxes icon-orange"></i> گزارش موجودی</a></li>
                    <li><a href="{{ route('reports.financial') }}" class="{{ request()->routeIs('reports.financial') ? 'active' : '' }}"><i class="fas fa-money-bill-wave icon-blue"></i> گزارش مالی</a></li>
                </ul>
            </li>
            <li class="has-submenu {{ request()->is('settings*') ? 'open' : '' }}">
                <a href="#">
                    <i class="fas fa-cog sidebar-icon icon-red"></i>
                    <span>تنظیمات</span>
                    <i class="fas fa-angle-left submenu-arrow"></i>
                </a>
                <ul class="submenu">
                    <li><a href="{{ route('settings.company') }}" class="{{ request()->routeIs('settings.company') ? 'active' : '' }}"><i class="fas fa-building icon-orange"></i> اطلاعات شرکت</a></li>
                    <li><a href="{{ route('settings.users') }}" class="{{ request()->routeIs('settings.users') ? 'active' : '' }}"><i class="fas fa-users-cog icon-cyan"></i> مدیریت کاربران</a></li>
                    <li><a href="#" id="openCurrencyModal"><i class="fas fa-money-bill-wave icon-green"></i> مدیریت واحدهای پول</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</aside>

<style>
.custom-sidebar {
    position: fixed;
    top: 0; right: 0; bottom: 0;
    width: 265px;
    background: #fff;
    border-left: 1.5px solid #e4e8ef;
    box-shadow: 0 0 18px 0 rgba(44,62,80,.09);
    z-index: 1200;
    height: 100vh;
    overflow-y: auto;
    transition: width .25s cubic-bezier(.4,0,.2,1);
    display: flex;
    flex-direction: column;
}
body.sidebar-collapsed .custom-sidebar {
    width: 60px !important;
    min-width:60px;
}
body.sidebar-collapsed .custom-sidebar .sidebar-title,
body.sidebar-collapsed .custom-sidebar .sidebar-user-info,
body.sidebar-collapsed .custom-sidebar .submenu,
body.sidebar-collapsed .custom-sidebar .sidebar-user-dropdown,
body.sidebar-collapsed .custom-sidebar .sidebar-user-menu,
body.sidebar-collapsed .custom-sidebar nav.sidebar-menu span,
body.sidebar-collapsed .custom-sidebar nav.sidebar-menu .submenu-arrow {
    display: none !important;
}
body.sidebar-collapsed .custom-sidebar .sidebar-logo {
    margin: 0 auto;
}
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: .85em;
    padding: 1.4rem 1rem 1.2rem 1rem;
    border-bottom: 1px solid #f1f1f1;
    background: linear-gradient(90deg, #e3f2fd 0, #fff 80%);
    position: relative;
}
.sidebar-logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
    border-radius: 50%;
    background: #fff;
    border: 2px solid #e0e7ef;
}
.sidebar-title {
    font-size: 1.25em;
    font-weight: 800;
    color: #1976d2;
    letter-spacing: .02em;
}
.sidebar-toggle-btn {
    border: none;
    background: transparent;
    padding: 0.35em 0.45em;
    font-size: 1.3em;
    color: #1976d2;
    border-radius: 8px;
    margin-right: auto;
    margin-left: .5em;
    transition: background .15s;
}
.sidebar-toggle-btn:hover { background: #e3f2fd;}
.sidebar-user {
    display: flex;
    align-items: center;
    gap: .9em;
    padding: 1.2rem 1rem 1.2rem 1rem;
    border-bottom: 1px solid #f1f1f1;
    background: #f7fbff;
    position: relative;
}
.sidebar-user-img {
    width: 43px;
    height: 43px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e0e7ef;
    background: #fff;
}
.sidebar-user-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: .2em;
}
.sidebar-user-name {
    font-weight: 700;
    color: #1976d2;
}
.sidebar-user-menu {
    color: #888;
    cursor: pointer;
    margin-top: 3px;
    font-size: 1.1em;
}
.sidebar-user-dropdown {
    display: none;
    position: absolute;
    top: 70px;
    right: 20px;
    min-width: 160px;
    background: #fff;
    border: 1px solid #e0e7ef;
    border-radius: 12px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.09);
    z-index: 10;
    flex-direction: column;
}
.sidebar-user-dropdown a {
    padding: .9em 1.2em;
    color: #374151;
    text-decoration: none;
    font-size: 1.07em;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: .7em;
    transition: background .17s;
}
.sidebar-user-dropdown a:last-child { border-bottom: none; }
.sidebar-user-dropdown a:hover, .sidebar-user-dropdown a.logout:hover {
    background: #e3f2fd;
    color: #1976d2;
}
.sidebar-menu {
    flex: 1;
    overflow-y: auto;
    padding: 1.2rem 0 1.2rem 0;
}
.sidebar-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
.sidebar-menu > ul > li {
    margin-bottom: .4em;
}
.sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 1.1em;
    color: #26344d;
    text-decoration: none;
    font-weight: 500;
    font-size: 1.09em;
    padding: .8em 1.5em;
    border-radius: 10px;
    transition: background .15s, color .15s;
    position: relative;
}
.sidebar-menu a.active,
.sidebar-menu li.active > a {
    background: #e3f2fd;
    color: #1976d2;
    font-weight: 800;
}
.sidebar-menu a:hover {
    background: #f1f6fa;
    color: #1976d2;
}
.sidebar-icon {
    font-size: 1.2em;
    margin-left: .2em;
    border-radius: 8px;
    padding: .25em;
}
.icon-blue    { color: #1976d2; background:rgba(25,118,210,.12);}
.icon-red     { color: #ef5350; background:rgba(239,83,80,.12);}
.icon-green   { color: #10b981; background:rgba(16,185,129,.12);}
.icon-orange  { color: #ff9800; background:rgba(255,152,0,.12);}
.icon-cyan    { color: #06b6d4; background:rgba(6,182,212,.12);}
.icon-purple  { color: #8e24aa; background:rgba(142,36,170,.12);}
.icon-yellow  { color: #fdd835; background:rgba(253,216,53,.12);}
.icon-indigo  { color: #3f51b5; background:rgba(63,81,181,.12);}
.has-submenu > a .submenu-arrow {
    margin-right: auto;
    color: #aaa;
    transition: transform .22s;
}
.has-submenu.open > a .submenu-arrow {
    transform: rotate(-90deg);
}
.has-submenu .submenu {
    display: none;
    padding-right: 1.5em;
}
.has-submenu.open .submenu {
    display: block;
    animation: fadein .25s;
}
.submenu li a {
    font-size: 1em;
    padding: .7em 1.4em .7em 1.4em;
}
@keyframes fadein {
    from { opacity: 0; transform: translateY(-10px);}
    to   { opacity: 1; transform: none;}
}
@media (max-width: 1000px) {
    .custom-sidebar { width: 215px;}
    .sidebar-title { font-size: 1.04em;}
}
@media (max-width: 650px) {
    .custom-sidebar { width: 100vw; left: 0; right: 0;}
    .sidebar-title { display:none;}
}
::-webkit-scrollbar {width: 7px;}
::-webkit-scrollbar-thumb {background: #e0e7ef; border-radius: 7px;}
::-webkit-scrollbar-track {background: #fff;}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // User dropdown
    const userBtn = document.getElementById('userMenuBtn');
    const userDropdown = document.getElementById('userMenuDropdown');
    if(userBtn && userDropdown) {
        userBtn.onclick = function(e){
            userDropdown.style.display = userDropdown.style.display === 'block' ? 'none' : 'block';
            e.stopPropagation();
        }
        document.addEventListener('click', function(e) {
            if(!userBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.style.display = 'none';
            }
        });
    }
    // Only one submenu open
    document.querySelectorAll('.has-submenu > a').forEach(function(a){
        a.addEventListener('click', function(e){
            e.preventDefault();
            let parent = this.closest('.has-submenu');
            if(parent.classList.contains('open')) {
                parent.classList.remove('open');
            } else {
                document.querySelectorAll('.has-submenu.open').forEach(function(openItem){
                    openItem.classList.remove('open');
                });
                parent.classList.add('open');
            }
        });
    });
    // Sidebar collapse/expand
    const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
    sidebarToggleBtn && sidebarToggleBtn.addEventListener('click', function() {
        document.body.classList.toggle('sidebar-collapsed');
        // منوی باز را ببند اگر جمع شد
        if(document.body.classList.contains('sidebar-collapsed')) {
            document.querySelectorAll('.has-submenu.open').forEach(function(item){
                item.classList.remove('open');
            });
        }
        // تغییر سایز main-content
        const mainContent = document.getElementById('main-content');
        if(mainContent) {
            if(document.body.classList.contains('sidebar-collapsed')) {
                mainContent.style.marginRight = '70px';
            } else {
                mainContent.style.marginRight = '278px';
            }
        }
    });
    // هماهنگ سازی اولیه main-content
    setTimeout(function(){
        if(document.body.classList.contains('sidebar-collapsed')) {
            const mainContent = document.getElementById('main-content');
            if(mainContent) mainContent.style.marginRight = '70px';
        }
    }, 100);
});
</script>
