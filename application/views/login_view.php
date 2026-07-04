<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OTTking Admin</title>
    <!-- Bootstrap 5 CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #311042 100%);
            min-height: 100vh;
        }
        
        /* ১. পেজ এন্ট্রি অ্যানিমেশন */
        @keyframes loginIntro {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-login-ready {
            animation: loginIntro 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* ২. ভুল সাবমিট দিলে শেক (Shake) অ্যানিমেশন */
        @keyframes cardShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px); }
            40%, 80% { transform: translateX(6px); }
        }
        .animate-shake {
            animation: cardShake 0.4s ease-in-out;
        }

        /* ফ্লুইড গ্লাস কার্ড */
        .glass-card {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            width: 100%;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .form-control {
            transition: all 0.25s ease;
            border-radius: 10px;
            padding: 12px 14px;
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(0, 0, 0, 0.15);
        }

        .form-control:focus {
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2);
            border-color: #4f46e5;
        }

        .is-invalid-custom {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }

        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border: none;
            transition: all 0.2s ease;
            padding: 12px;
            border-radius: 10px;
        }

        .btn-submit:hover {
            box-shadow: 0 8px 20px -5px rgba(99, 102, 241, 0.5);
            opacity: 0.95;
        }

        /* এরর মেসেজ স্লাইড ডাউন */
        .error-container {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            max-height: 0;
            opacity: 0;
            overflow: hidden;
        }
        
        .error-container.show {
            max-height: 100px;
            opacity: 1;
            margin-top: 8px;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 sm:p-6 min-h-screen relative overflow-y-auto">

<!-- গ্লোয়িং ব্যাকগ্রাউন্ড ইফেক্ট -->
<div class="absolute top-10 left-10 w-48 h-48 sm:w-72 sm:h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute bottom-10 right-10 w-48 h-48 sm:w-72 sm:h-72 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

<!-- রেসপন্সিভ কন্টেইনার উইথ (অ্যানিমেশন ক্লাস যুক্ত করা হয়েছে) -->
<div class="w-full max-w-[420px] mx-auto my-auto animate-login-ready">
    
    <div id="loginCard" class="glass-card shadow-2xl p-6 sm:p-8">
        
        <!-- ব্র্যান্ড লোগো এবং হেডার -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 text-white mb-3 shadow-md">
                <i class="fa-solid fa-shield-halved text-xl"></i>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Welcome Back</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Sign in to manage your OTTking account</p>
        </div>

        <!-- ফ্ল্যাশডাটা এরর মেসেজ -->
        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger d-flex align-items-center border-0 rounded-xl shadow-sm mb-4 bg-red-50 text-red-700 p-3 text-xs" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2 text-sm"></i>
                <div><?= $this->session->flashdata('error'); ?></div>
            </div>
        <?php endif; ?>

        <!-- ফর্ম সাবমিট -->
        <form id="loginForm" action="<?= base_url('login/login_process'); ?>" method="POST" class="space-y-4" novalidate>
            
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <!-- ইমেইল এড্রেস ফিল্ড -->
            <div>
                <label for="email" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">Email Address</label>
                <input type="email" name="email" id="email" required
                       class="form-control w-full <?= form_error('email') ? 'is-invalid-custom' : ''; ?>" 
                       placeholder="name@ottking.top"
                       value="<?= set_value('email'); ?>">
                
                <div id="email_error" class="error-container <?= form_error('email') ? 'show' : ''; ?> flex items-start gap-2 px-3 py-2 bg-red-50 border-l-4 border-red-500 rounded-r-lg text-red-700">
                    <i class="fa-solid fa-circle-exclamation text-xs mt-0.5"></i>
                    <span class="error-text text-[11px] font-semibold tracking-wide">
                        <?= form_error('email') ? strip_tags(form_error('email')) : ''; ?>
                    </span>
                </div>
            </div>

            <!-- পাসওয়ার্ড ফিল্ড -->
            <div>
                <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" id="password" required
                       class="form-control w-full <?= form_error('password') ? 'is-invalid-custom' : ''; ?>" 
                       placeholder="••••••••">
                
                <div id="password_error" class="error-container <?= form_error('password') ? 'show' : ''; ?> flex items-start gap-2 px-3 py-2 bg-red-50 border-l-4 border-red-500 rounded-r-lg text-red-700">
                    <i class="fa-solid fa-circle-exclamation text-xs mt-0.5"></i>
                    <span class="error-text text-[11px] font-semibold tracking-wide">
                        <?= form_error('password') ? strip_tags(form_error('password')) : ''; ?>
                    </span>
                </div>
            </div>

            <!-- রিমেম্বার মি এবং ফরগট লিংক -->
            <div class="flex items-center justify-between text-[11px] sm:text-xs pt-1">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-0">
                    <label for="remember_me" class="ml-2 block text-slate-600 font-medium select-none">Remember me</label>
                </div>
                <div>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#devModal" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Forgot password?</a>
                </div>
            </div>

            <!-- সাবমিট বাটন -->
            <div class="pt-2">
                <button type="submit" class="btn-submit btn text-white w-100 font-bold tracking-wide flex items-center justify-center gap-2 text-sm sm:text-base">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right text-xs sm:text-sm"></i>
                </button>
            </div>
        </form>

    </div>
    
    <!-- কপিরাইট টেক্সট -->
    <p class="text-center text-slate-500 text-[11px] mt-6 font-light tracking-wide">&copy; <?= date('Y'); ?> OTTKing. All rights reserved.</p>
</div>

<!-- UNDER DEVELOPMENT MODAL -->
<div class="modal fade" id="devModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm sm:modal-md p-4">
        <div class="modal-content border-0 rounded-2xl overflow-hidden">
            <div class="modal-body text-center p-5 bg-white">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-amber-50 text-amber-500 mb-3">
                    <i class="fa-solid fa-laptop-code text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Under Development</h3>
                <p class="text-slate-500 text-xs sm:text-sm leading-relaxed mb-4">
                    পাসওয়ার্ড রিসেট মডিউলের কাজ চলছে। খুব দ্রুত এটি যুক্ত করা হবে।
                </p>
                <button type="button" class="btn btn-secondary w-full py-2.5 rounded-xl border-0 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm transition-all" data-bs-dismiss="modal">
                    Got it!
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('loginForm').addEventListener('submit', function(event) {
    let isValid = true;
    const card = document.getElementById('loginCard');
    
    // ইমেইল চেক
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email_error');
    const emailErrorText = emailError.querySelector('.error-text');
    
    if (!emailInput.value.trim()) {
        emailErrorText.textContent = "Email address cannot be empty.";
        emailError.classList.add('show');
        emailInput.classList.add('is-invalid-custom');
        isValid = false;
    } else if (!emailInput.checkValidity()) {
        emailErrorText.textContent = "Please enter a valid structure (name@example.com).";
        emailError.classList.add('show');
        emailInput.classList.add('is-invalid-custom');
        isValid = false;
    } else {
        emailError.classList.remove('show');
        emailInput.classList.remove('is-invalid-custom');
    }

    // পাসওয়ার্ড চেক
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password_error');
    const passwordErrorText = passwordError.querySelector('.error-text');

    if (!passwordInput.value.trim()) {
        passwordErrorText.textContent = "Password field is strictly required.";
        passwordError.classList.add('show');
        passwordInput.classList.add('is-invalid-custom');
        isValid = false;
    } else {
        passwordError.classList.remove('show');
        passwordInput.classList.remove('is-invalid-custom');
    }

    if (!isValid) {
        event.preventDefault();
        
        // পুরাতন ক্লাস রিমুভ করে নতুন করে Shake অ্যানিমেশন ক্লাস ট্রিগার করা হলো
        card.classList.remove('animate-shake');
        void card.offsetWidth; // রি-ফ্লো ট্রিগার ট্রিক (অ্যানিমেশন রিসেট করার জন্য)
        card.classList.add('animate-shake');
    }
});

// রিয়েল-টাইমে ইনপুট দিলে এরর মেসেজ চলে যাওয়ার মসৃণ প্রসেস
document.getElementById('email').addEventListener('input', function() {
    if (this.checkValidity()) {
        document.getElementById('email_error').classList.remove('show');
        this.classList.remove('is-invalid-custom');
    }
});

document.getElementById('password').addEventListener('input', function() {
    if (this.value.trim()) {
        document.getElementById('password_error').classList.remove('show');
        this.classList.remove('is-invalid-custom');
    }
});
</script>

</body>
</html>