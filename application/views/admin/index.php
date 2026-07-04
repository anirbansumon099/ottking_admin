<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard'; ?> | OTTKING </title>
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" defer></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            color: #e2e8f0;
        }

        /* =============================================
           ANIMATIONS
           ============================================= */
        @keyframes panelIntro {
            from { opacity: 0; transform: scale(0.99); }
            to { opacity: 1; transform: scale(1); }
        }
        
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-panel {
            animation: panelIntro 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .page-content {
            animation: slideIn 0.4s ease-out;
        }

        /* =============================================
           GLASS EFFECT COMPONENTS
           ============================================= */
        .glass-sidebar {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-header {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-card {
            background: rgba(30, 27, 75, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }

        /* =============================================
           SIDEBAR STYLES
           ============================================= */
        .sidebar {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 50;
        }

        .sidebar-hidden {
            transform: translateX(-100%) !important;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 12px;
            color: #94a3b8;
            transition: all 0.25s ease;
            text-decoration: none !important;
            cursor: pointer;
        }

        .nav-link-custom:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff;
            padding-left: 1.25rem;
        }

        .nav-link-custom.active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            box-shadow: 0 8px 20px -5px rgba(99, 102, 241, 0.5);
            padding-left: 1.25rem;
        }

        .no-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .no-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .no-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }

        .no-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* =============================================
           LOADER
           ============================================= */
        .page-loader {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .page-loader.active {
            display: flex;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid rgba(99, 102, 241, 0.2);
            border-top: 4px solid #4f46e5;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* =============================================
           RESPONSIVE DESIGN
           ============================================= */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                height: 100%;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                backdrop-filter: blur(4px);
                z-index: 40;
            }

            .sidebar-overlay.active {
                display: block;
            }

            #main-content {
                padding: 0;
            }
        }

        /* =============================================
           UTILITY CLASSES
           ============================================= */
        .text-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px -5px rgba(99, 102, 241, 0.5);
            color: white;
        }

        .badge-role {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.35rem 0.75rem;
        }

        .badge-superadmin {
            background: rgba(168, 85, 247, 0.2);
            color: #d8b4fe;
            border: 1px solid rgba(168, 85, 247, 0.3);
        }

        .badge-admin {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            border: 1px solid rgba(99, 102, 241, 0.3);
        }

        .badge-user {
            background: rgba(107, 114, 128, 0.2);
            color: #d1d5db;
            border: 1px solid rgba(107, 114, 128, 0.3);
        }

        /* Alert Styles */
        .alert {
            border: none;
            border-radius: 12px;
            border-left: 4px solid;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border-left-color: #ef4444;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            color: #86efac;
            border-left-color: #22c55e;
        }

        .alert-warning {
            background: rgba(234, 179, 8, 0.1);
            color: #fde047;
            border-left-color: #eab308;
        }

        .alert-info {
            background: rgba(59, 130, 246, 0.1);
            color: #93c5fd;
            border-left-color: #3b82f6;
        }
    </style>
</head>
<body class="overflow-x-hidden">

    <!-- Background Blur Elements -->
    <div class="absolute top-10 left-10 w-72 h-72 sm:w-96 sm:h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 sm:w-96 sm:h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>

    <!-- Main Container -->
    <div class="flex w-full h-screen animate-panel relative z-10">

        <!-- Sidebar -->
        <?php $this->load->view('admin/sidebar'); ?>

        <!-- Sidebar Overlay (Mobile) -->
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Main Content Area -->
        <div id="main-content" class="flex-1 flex flex-col min-w-0 h-screen transition-all duration-300">
            
            <!-- Header -->
            <header class="flex items-center justify-between h-16 px-4 md:px-6 glass-header sticky top-0 z-30 flex-shrink-0">
                <button onclick="toggleSidebar()" class="text-slate-300 hover:text-white focus:outline-none p-2 hover:bg-white/5 rounded-xl transition-colors md:hidden">
                    <i class="fa-solid fa-bars-staggered text-lg"></i>
                </button>

                <!-- Logo (Visible on Desktop) -->
                <div class="hidden md:flex items-center gap-2 text-sm font-semibold text-slate-300">
                    <i class="fa-solid fa-chart-pie text-indigo-400"></i>
                    Dashboard
                </div>

                <!-- User Profile Dropdown -->
                <div class="relative ml-auto">
                    <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="flex items-center gap-2 md:gap-3 focus:outline-none hover:bg-white/5 px-2 md:px-3 py-1.5 rounded-xl transition-all group">
                        <div class="text-right hidden sm:block">
                            <div class="text-xs md:text-sm font-semibold text-slate-200"><?= $this->session->userdata('username'); ?></div>
                            <?php 
                                $role = $this->session->userdata('role');
                                $badge_class = 'badge-' . strtolower($role);
                            ?>
                            <span class="badge-role <?= $badge_class; ?>">
                                <?= $role; ?>
                            </span>
                        </div>
                        <div class="w-8 h-8 md:w-9 md:h-9 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-transform">
                            <?= strtoupper(substr($this->session->userdata('username'), 0, 1)); ?>
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="userDropdownMenu" class="absolute right-0 mt-2 w-48 md:w-52 rounded-xl border border-white/10 bg-slate-900/95 backdrop-blur-xl shadow-2xl p-2 origin-top-right scale-95 opacity-0 pointer-events-none transition-all duration-200 z-50">
                        <a href="<?= base_url('admin/profile'); ?>" class="flex items-center gap-3 px-3 py-2 text-xs font-medium text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-all">
                            <i class="fa-solid fa-user text-indigo-400 w-4 text-center"></i>
                            Profile
                        </a>
                        
                        <a href="#" class="flex items-center gap-3 px-3 py-2 text-xs font-medium text-slate-300 hover:text-white hover:bg-white/5 rounded-lg transition-all">
                            <i class="fa-solid fa-sliders text-purple-400 w-4 text-center"></i>
                            Settings
                        </a>
                        
                        <hr class="border-white/5 my-1">
                        
                        <a href="<?= base_url('admin/logout'); ?>" class="flex items-center gap-3 px-3 py-2 text-xs font-semibold text-red-400 hover:bg-red-500/10 rounded-lg transition-all">
                            <i class="fa-solid fa-sign-out-alt text-red-400 w-4 text-center"></i>
                            Logout
                        </a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-3 md:p-6 relative">
                <!-- Page Loader -->
                <div id="page-loader" class="page-loader">
                    <div class="flex flex-col items-center gap-3">
                        <div class="spinner"></div>
                        <p class="text-slate-200 text-sm font-medium">Loading page...</p>
                    </div>
                </div>

                <!-- Content Area -->
                <div class="max-w-[1600px] mx-auto">
                    <div id="content-area" class="page-content">
                        <?php 
                            if (isset($main_content)) {
                                $this->load->view($main_content);
                            }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // ================================================
        // 1. SIDEBAR TOGGLE
        // ================================================
        window.toggleSidebar = function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (!sidebar) return;
            
            if (window.innerWidth >= 768) {
                sidebar.classList.toggle('sidebar-hidden');
            } else {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('active');
            }
        }

        // ================================================
        // 2. USER DROPDOWN TOGGLE
        // ================================================
        window.toggleUserDropdown = function() {
            const dropdown = document.getElementById('userDropdownMenu');
            if (!dropdown) return;
            
            dropdown.classList.toggle('scale-95');
            dropdown.classList.toggle('opacity-0');
            dropdown.classList.toggle('pointer-events-none');
        }

        // ================================================
        // 3. SUBMENU TOGGLE
        // ================================================
        window.toggleSubMenu = function(event, menuId, chevronId) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const subMenu = document.getElementById(menuId);
            const chevron = document.getElementById(chevronId);
            if (!subMenu) return;

            subMenu.classList.toggle('hidden');
            if (chevron) {
                chevron.style.transform = subMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        }

        // ================================================
        // 4. CLOSE DROPDOWN ON OUTSIDE CLICK
        // ================================================
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdownMenu');
            const button = document.getElementById('userDropdownBtn');
            
            if (dropdown && button && !button.contains(e.target) && !dropdown.contains(e.target)) {
                if (!dropdown.classList.contains('pointer-events-none')) {
                    dropdown.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
                }
            }
        });

        // ================================================
        // 5. AJAX PAGE LOADING
        // ================================================
        window.loadPage = function(url, title = '') {
            const contentArea = document.getElementById('content-area');
            const loader = document.getElementById('page-loader');
            
            if (!contentArea || !loader) return;
            
            loader.classList.add('active');
            
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                timeout: 15000,
                success: function(data) {
                    contentArea.innerHTML = data;
                    
                    if (title) {
                        document.title = title + ' | OTTking Admin';
                    }
                    
                    setTimeout(() => {
                        loader.classList.remove('active');
                    }, 300);
                    
                    // Close sidebar on mobile
                    if (window.innerWidth < 768) {
                        const sidebar = document.getElementById('sidebar');
                        if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                            sidebar.classList.add('-translate-x-full');
                            document.getElementById('sidebar-overlay').classList.remove('active');
                        }
                    }
                },
                error: function() {
                    contentArea.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            <strong>Error:</strong> Failed to load page. Please try again.
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `;
                    loader.classList.remove('active');
                }
            });
        }

        // ================================================
        // 6. FORM SUBMISSION
        // ================================================
        window.submitFormAjax = function(formId, successCallback) {
            const form = document.getElementById(formId);
            if (!form) return;
            
            const formData = new FormData(form);
            
            $.ajax({
                url: form.action,
                type: form.method || 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: successCallback,
                error: function() {
                    alert('Error submitting form!');
                }
            });
        }

        // ================================================
        // 7. INITIALIZE
        // ================================================
        document.addEventListener('DOMContentLoaded', function() {
            // Prevent sidebar from closing on desktop resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 768) {
                    const sidebar = document.getElementById('sidebar');
                    if (sidebar) sidebar.classList.remove('-translate-x-full');
                    document.getElementById('sidebar-overlay').classList.remove('active');
                }
            });
        });
    </script>
</body>
</html>
