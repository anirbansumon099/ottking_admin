<div class="animate-panel max-w-4xl mx-auto">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-white">Profile Details</h2>
        <p class="text-slate-400 text-sm">Overview of your account information and credentials.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-1 bg-slate-900/50 backdrop-blur-xl border border-white/5 rounded-3xl p-6 flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center text-3xl font-bold text-white shadow-lg shadow-indigo-600/20 mb-4">
                <?= strtoupper(substr($this->session->userdata('username'), 0, 1)); ?>
            </div>
            <h3 class="text-lg font-bold text-white"><?= $this->session->userdata('username'); ?></h3>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold border border-indigo-500/20 bg-indigo-500/10 text-indigo-400 uppercase tracking-widest mt-2">
                <?= $this->session->userdata('role'); ?>
            </span>
        </div>

        <div class="md:col-span-2 bg-slate-900/50 backdrop-blur-xl border border-white/5 rounded-3xl p-6">
            <h4 class="text-white font-semibold mb-6 flex items-center gap-2">
                <i class="fa-solid fa-user-circle text-indigo-400"></i> Account Information
            </h4>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-white/5 p-4 rounded-xl">
                        <label class="text-[10px] text-slate-500 uppercase font-bold">Username</label>
                        <div class="text-white font-medium"><?= $this->session->userdata('username'); ?></div>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl">
                        <label class="text-[10px] text-slate-500 uppercase font-bold">Email Address</label>
                        <div class="text-white font-medium"><?= $this->session->userdata('email') ?: 'Not provided'; ?></div>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl">
                        <label class="text-[10px] text-slate-500 uppercase font-bold">Account Role</label>
                        <div class="text-white font-medium capitalize"><?= $this->session->userdata('role'); ?></div>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl">
                        <label class="text-[10px] text-slate-500 uppercase font-bold">Last Login</label>
                        <div class="text-white font-medium"><?= $this->session->userdata('last_login') ?: 'Just now'; ?></div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    Edit Profile
                </button>
                <button class="bg-white/5 hover:bg-white/10 text-slate-300 px-6 py-2.5 rounded-xl text-sm font-semibold transition-all">
                    Change Password
                </button>
            </div>
        </div>
    </div>
</div>