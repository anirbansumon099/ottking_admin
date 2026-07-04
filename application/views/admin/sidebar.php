<aside id="sidebar" class="sidebar glass-sidebar fixed inset-y-0 left-0 z-50 flex flex-col w-64 transform -translate-x-full md:translate-x-0 md:sticky md:h-screen flex-shrink-0">
    
    <div class="flex items-center justify-between h-16 px-6 border-b border-white/5 bg-slate-950/40 text-white flex-shrink-0">
        <div class="flex items-center gap-2.5">
            <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-500/30">
                <i class="fa-solid fa-shield-halved text-sm"></i>
            </div>
            <span class="font-extrabold text-lg tracking-tight text-white">OTT<span class="text-indigo-400">King</span></span>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white transition-colors">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
    </div>

    <nav class="flex-1 py-4 space-y-1 overflow-y-auto no-scrollbar">
        <p class="px-8 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 leading-none">Main Menu</p>
        
        <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link-custom">
            <i class="fa-solid fa-chart-pie w-5 text-base"></i> 
            <span class="ml-2.5 font-medium text-sm">Dashboard</span>
        </a>

        <div class="space-y-1">
            <button onclick="toggleSubMenu(event, 'channelsSubMenu', 'channelsChevron')" class="w-full text-left nav-link-custom flex items-center justify-between group focus:outline-none">
                <div class="flex items-center">
                    <i class="fa-solid fa-tv w-5 text-base"></i> 
                    <span class="ml-2.5 font-medium text-sm">Channels</span>
                </div>
                <i id="channelsChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-transform duration-200 mr-1"></i>
            </button>
            <div id="channelsSubMenu" class="hidden pl-9 pr-4 space-y-1 transition-all duration-300 overflow-hidden">
                <a href="<?= base_url('admin/channels/add'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-circle-plus text-[10px] text-indigo-400 w-3.5 text-center"></i>
                    Add Channel
                </a>
                <a href="<?= base_url('admin/channels'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-list text-[10px] text-purple-400 w-3.5 text-center"></i>
                    View Channels
                </a>
            </div>
        </div>

        <div class="space-y-1">
            <button onclick="toggleSubMenu(event, 'categoriesSubMenu', 'categoriesChevron')" class="w-full text-left nav-link-custom flex items-center justify-between group focus:outline-none">
                <div class="flex items-center">
                    <i class="fa-solid fa-tags w-5 text-base"></i> 
                    <span class="ml-2.5 font-medium text-sm">Categories</span>
                </div>
                <i id="categoriesChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-transform duration-200 mr-1"></i>
            </button>
            <div id="categoriesSubMenu" class="hidden pl-9 pr-4 space-y-1 transition-all duration-300 overflow-hidden">
                <a href="<?= base_url('admin/categories/add'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-folder-plus text-[10px] text-indigo-400 w-3.5 text-center"></i>
                    Add Category
                </a>
                <a href="<?= base_url('admin/categories'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-border-all text-[10px] text-purple-400 w-3.5 text-center"></i>
                    View Categories
                </a>
            </div>
        </div>

        <div class="space-y-1">
            <button onclick="toggleSubMenu(event, 'usersSubMenu', 'usersChevron')" class="w-full text-left nav-link-custom flex items-center justify-between group focus:outline-none">
                <div class="flex items-center">
                    <i class="fa-solid fa-users w-5 text-base"></i> 
                    <span class="ml-2.5 font-medium text-sm">Users</span>
                </div>
                <i id="usersChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-transform duration-200 mr-1"></i>
            </button>
            <div id="usersSubMenu" class="hidden pl-9 pr-4 space-y-1 transition-all duration-300 overflow-hidden">
                <a href="<?= base_url('admin/users/add'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-user-plus text-[10px] text-indigo-400 w-3.5 text-center"></i>
                    Add User
                </a>
                <a href="<?= base_url('admin/users'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-user-group text-[10px] text-purple-400 w-3.5 text-center"></i>
                    View Users
                </a>
            </div>
        </div>

        <div class="space-y-1">
            <button onclick="toggleSubMenu(event, 'subSubMenu', 'subChevron')" class="w-full text-left nav-link-custom flex items-center justify-between group focus:outline-none">
                <div class="flex items-center">
                    <i class="fa-solid fa-crown w-5 text-base text-amber-400/90"></i> 
                    <span class="ml-2.5 font-medium text-sm">Subscriptions</span>
                </div>
                <i id="subChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-transform duration-200 mr-1"></i>
            </button>
            <div id="subSubMenu" class="hidden pl-9 pr-4 space-y-1 transition-all duration-300 overflow-hidden">
                <a href="<?= base_url('admin/subscriptions/packages'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-box text-[10px] text-amber-400 w-3.5 text-center"></i>
                    Packages
                </a>
                <a href="<?= base_url('admin/subscriptions'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-id-badge text-[10px] text-indigo-400 w-3.5 text-center"></i>
                    User Subscriptions
                </a>
            </div>
        </div>

        <div class="space-y-1">
            <button onclick="toggleSubMenu(event, 'billingSubMenu', 'billingChevron')" class="w-full text-left nav-link-custom flex items-center justify-between group focus:outline-none">
                <div class="flex items-center">
                    <i class="fa-solid fa-credit-card w-5 text-base"></i> 
                    <span class="ml-2.5 font-medium text-sm">Billing & Revenue</span>
                </div>
                <i id="billingChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-transform duration-200 mr-1"></i>
            </button>
            <div id="billingSubMenu" class="hidden pl-9 pr-4 space-y-1 transition-all duration-300 overflow-hidden">
                <a href="<?= base_url('admin/billing/payments'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-money-bill-wave text-[10px] text-emerald-400 w-3.5 text-center"></i>
                    Payments
                </a>
                <a href="<?= base_url('admin/billing/transactions'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-receipt text-[10px] text-sky-400 w-3.5 text-center"></i>
                    Transactions
                </a>
            </div>
        </div>

        <hr class="border-white/5 my-4 mx-4">
        <p class="px-8 text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-3 leading-none">Management & Control</p>

        <div class="space-y-1">
            <button onclick="toggleSubMenu(event, 'settingsSubMenu', 'settingsChevron')" class="w-full text-left nav-link-custom flex items-center justify-between group focus:outline-none">
                <div class="flex items-center">
                    <i class="fa-solid fa-sliders w-5 text-base"></i> 
                    <span class="ml-2.5 font-medium text-sm">App Settings</span>
                </div>
                <i id="settingsChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-transform duration-200 mr-1"></i>
            </button>
            
            <div id="settingsSubMenu" class="hidden pl-9 pr-4 space-y-1 transition-all duration-300 overflow-hidden">
                <a href="<?= base_url('admin/systems'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-server text-[11px] text-purple-400 w-3.5 text-center"></i>
                    System Settings
                </a>
                <a href="<?= base_url('admin/config_settings'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-gear text-[11px] text-indigo-400 w-3.5 text-center"></i>
                    App Config
                </a>
                <a href="<?= base_url('admin/api_settings'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-key text-[11px] text-amber-400 w-3.5 text-center"></i>
                    App API Key
                </a>
                <a href="<?= base_url('admin/auth'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-user-lock text-[11px] text-rose-400 w-3.5 text-center"></i>
                    Authentication
                </a>
                <a href="<?= base_url('admin/settings/apk'); ?>" class="flex items-center gap-2.5 py-2 px-3 text-xs font-medium text-slate-400 hover:text-white rounded-lg hover:bg-white/5 transition-colors text-decoration-none">
                    <i class="fa-solid fa-android text-[11px] text-emerald-400 w-3.5 text-center"></i>
                    APK Update & Build
                </a>
            </div>
        </div>

        <?php if(in_array($this->session->userdata('role'), ['superadmin', 'admin'])): ?>
            <a href="<?= base_url('admin/view_admin'); ?>" class="nav-link-custom">
                <i class="fa-solid fa-user-shield w-5 text-base"></i> 
                <span class="ml-2.5 font-medium text-sm">Admin Management</span>
            </a>
        <?php endif; ?>
    </nav>
</aside>