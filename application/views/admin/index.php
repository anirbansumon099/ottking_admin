<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title : 'Dashboard'; ?> | OTTKING </title>
    <link class="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- jQuery আগে কোথাও লোড হতো না, অথচ view_categories.php এর মতো পেজ $.get/$.post
         ব্যবহার করে — ফলে ব্রাউজার কনসোলে "$ is not defined" এরর দিত এবং AJAX পেজ কাজ করত না -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=300;400;500;600;700&display=swap');
        
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
        }

        @keyframes panelIntro {
            from { opacity: 0; transform: scale(0.99); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-panel {
            animation: panelIntro 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .sidebar {
            transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-sidebar {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .glass-header {
            background: rgba(15, 23, 42, 0.4);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            margin: 0.25rem 1rem;
            border-radius: 12px;
            color: #94a3b8;
            transition: all 0.25s ease;
            text-decoration: none !important;
        }
        .nav-link-custom:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: #ffffff !important;
        }
        .nav-link-custom.active {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff !important;
            box-shadow: 0 8px 20px -5px rgba(99, 102, 241, 0.5);
        }

        .sidebar-hidden {
            transform: translateX(-100%) !important;
            position: fixed !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="flex min-h-screen overflow-x-hidden relative">

    <div class="absolute top-10 left-10 w-72 h-72 sm:w-96 sm:h-96 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>
    <div class="absolute bottom-10 right-10 w-72 h-72 sm:w-96 sm:h-96 bg-purple-500/10 rounded-full blur-3xl pointer-events-none z-0"></div>

    <div class="flex-1 flex w-full h-screen z-10 animate-panel">

        <?php $this->load->view('admin/sidebar'); ?>

        <div id="sidebar-overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 hidden" onclick="toggleSidebar()"></div>

        <div id="main-content" class="flex-1 flex flex-col min-w-0 h-screen transition-all duration-300">
            
           <header class="flex items-center justify-between h-16 px-6 glass-header sticky top-0 z-30 flex-shrink-0">
    <button onclick="toggleSidebar()" class="text-slate-300 focus:outline-none p-2 hover:bg-white/5 rounded-xl transition-colors">
        <i class="fa-solid fa-bars-staggered text-xl"></i>
    </button>

    <div class="relative">
        <button onclick="toggleUserDropdown()" id="userDropdownBtn" class="flex items-center gap-3 focus:outline-none hover:bg-white/5 p-1.5 rounded-2xl transition-all group">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-semibold text-slate-200 mb-0.5 transition-colors group-hover:text-white"><?= $this->session->userdata('username'); ?></div>
                <?php 
                    $role = $this->session->userdata('role');
                    $badge_color = ($role == 'superadmin') ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : (($role == 'admin') ? 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20' : 'bg-gray-500/10 text-gray-400 border-gray-500/20');
                ?>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border <?= $badge_color; ?> uppercase tracking-wide">
                    <?= $role; ?>
                </span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg shadow-indigo-600/20 select-none group-hover:scale-105 transition-transform">
                <?= strtoupper(substr($this->session->userdata('username'), 0, 1)); ?>
            </div>
            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-200 transition-colors hidden sm:block mr-1"></i>
        </button>

        <div id="userDropdownMenu" class="absolute right-0 mt-2 w-52 rounded-2xl border border-white/5 bg-slate-900/95 backdrop-blur-xl shadow-2xl shadow-black/50 p-1.5 origin-top-right transform scale-95 opacity-0 pointer-events-none transition-all duration-200 z-50">
            <div class="px-3 py-2 border-b border-white/5 mb-1 sm:hidden">
                <p class="text-xs font-bold text-white truncate mb-0.5"><?= $this->session->userdata('username'); ?></p>
                <span class="text-[9px] text-indigo-400 font-bold uppercase tracking-wider"><?= $role; ?></span>
            </div>
            
            <a href="profile" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition-all text-decoration-none">
                <i class="fa-solid fa-id-card text-sm text-indigo-400 w-4 text-center"></i>
                Profile Settings
            </a>
            
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-xs font-medium text-slate-300 hover:text-white hover:bg-white/5 rounded-xl transition-all text-decoration-none">
                <i class="fa-solid fa-sliders text-sm text-purple-400 w-4 text-center"></i>
                IPTV Preference
            </a>
            
            <hr class="border-white/5 my-1 mx-1">
            
            <a href="<?= base_url('admin/logout'); ?>" class="flex items-center gap-3 px-3 py-2.5 text-xs font-semibold text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-all text-decoration-none">
                <i class="fa-solid fa-right-from-bracket text-sm w-4 text-center"></i>
                Logout Session
            </a>
        </div>
    </div>
</header>
            <main class="flex-1 overflow-y-auto p-4 md:p-6">
                <div class="max-w-[1600px] mx-auto">
                    <?php 
                        if (isset($main_content)) {
                            $this->load->view($main_content);
                        }
                    ?>
                </div>
            </main>
        </div>

    </div>

    <script>
// ১. মেইন সাইডবার টগল ফাংশন (ডেস্কটপ এবং মোবাইল রেসপন্সিভ)
window.toggleSidebar = function() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (!sidebar) return;
    
    if (window.innerWidth >= 768) {
        sidebar.classList.toggle('sidebar-hidden');
    } else {
        if (sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
        }
    }
}

// ২. টপবারের ইউজার প্রোফাইল ড্রপডাউন টগল ফাংশন
window.toggleUserDropdown = function() {
    const dropdown = document.getElementById('userDropdownMenu');
    if (!dropdown) return;
    
    const isHidden = dropdown.classList.contains('pointer-events-none');
    
    if (isHidden) {
        dropdown.classList.remove('scale-95', 'opacity-0', 'pointer-events-none');
        dropdown.classList.add('scale-100', 'opacity-1');
    } else {
        dropdown.classList.remove('scale-100', 'opacity-1');
        dropdown.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
    }
}

// ৩. সাইডবারের সব সাব-মেনু (Channels, Categories, Settings ইত্যাদি) টগল করার ডাইনামিক ফাংশন
window.toggleSubMenu = function(event, menuId, chevronId) {
    if (event) {
        event.preventDefault();
        event.stopPropagation(); // ইভেন্ট বাবলিং থামানোর জন্য যাতে সাথে সাথে বন্ধ না হয়
    }
    
    const subMenu = document.getElementById(menuId);
    const chevron = document.getElementById(chevronId);
    if (!subMenu) return;

    if (subMenu.classList.contains('hidden')) {
        subMenu.classList.remove('hidden');
        if (chevron) {
            chevron.classList.add('rotate-180');
            chevron.style.transform = 'rotate(180deg)';
        }
    } else {
        subMenu.classList.add('hidden');
        if (chevron) {
            chevron.classList.remove('rotate-180');
            chevron.style.transform = 'rotate(0deg)';
        }
    }
}

// ৪. গ্লোবাল ক্লিক লিসেনার: প্রোফাইল ড্রপডাউনের বাইরে ক্লিক করলে অটো-ক্লোজ করার জন্য
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('userDropdownMenu');
    const button = document.getElementById('userDropdownBtn');
    
    if (dropdown && button && !button.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.classList.remove('scale-100', 'opacity-1');
        dropdown.classList.add('scale-95', 'opacity-0', 'pointer-events-none');
    }
});
</script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>