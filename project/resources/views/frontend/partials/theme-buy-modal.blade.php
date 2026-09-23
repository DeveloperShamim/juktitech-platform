<div class="theme-buy-modal" id="themeBuyModal" aria-hidden="true">
    <div class="theme-buy-backdrop" data-theme-buy-close></div>
    <div class="theme-buy-dialog" role="dialog" aria-modal="true" aria-labelledby="themeBuyTitle">
        <button type="button" class="theme-buy-close" data-theme-buy-close aria-label="বন্ধ করুন">&times;</button>
        <div class="theme-buy-icon"><i class="fas fa-globe"></i></div>
        <h3 id="themeBuyTitle">কোন ডোমেইনে ইনস্টল করবেন?</h3>
        <p id="themeBuyProduct" class="theme-buy-product"></p>

        <form method="POST" id="themeBuyForm">
            @csrf
            <label for="themeInstallDomain">ডোমেইন নাম <span>*</span></label>
            <div class="theme-buy-input">
                <i class="fas fa-link"></i>
                <input type="text"
                       id="themeInstallDomain"
                       name="install_domain"
                       placeholder="example.com"
                       maxlength="255"
                       inputmode="url"
                       autocomplete="url"
                       required>
            </div>
            <small>শুধু আপনার ডোমেইন লিখুন। পেমেন্ট সফল হলে এই ডোমেইনের লাইসেন্স অটো তৈরি হবে।</small>
            <button type="submit" class="theme-buy-submit">
                পেমেন্টে যান <i class="fas fa-arrow-right"></i>
            </button>
        </form>
    </div>
</div>

<style>
.theme-buy-modal{position:fixed;inset:0;z-index:100000;display:none;align-items:center;justify-content:center;padding:18px}
.theme-buy-modal.open{display:flex}
.theme-buy-backdrop{position:absolute;inset:0;background:rgba(15,23,42,.68);backdrop-filter:blur(4px)}
.theme-buy-dialog{position:relative;width:min(440px,100%);background:#fff;border-radius:22px;padding:30px;box-shadow:0 30px 80px rgba(15,23,42,.35);z-index:1}
.theme-buy-close{position:absolute;right:14px;top:10px;border:0;background:#f1f5f9;color:#475569;width:34px;height:34px;border-radius:50%;font-size:23px;line-height:1;cursor:pointer}
.theme-buy-icon{width:58px;height:58px;border-radius:17px;background:rgba(var(--primary-rgb,124,58,237),.12);color:var(--primary-color);display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:16px}
.theme-buy-dialog h3{font-size:1.35rem;font-weight:800;color:#0f172a;margin:0 35px 5px 0}
.theme-buy-product{color:#64748b;margin-bottom:20px}
.theme-buy-dialog label{display:block;font-weight:700;color:#334155;margin-bottom:8px}
.theme-buy-dialog label span{color:#ef4444}
.theme-buy-input{position:relative}
.theme-buy-input i{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#94a3b8}
.theme-buy-input input{width:100%;height:50px;border:1px solid #cbd5e1;border-radius:12px;padding:0 15px 0 42px;font-size:16px;outline:none}
.theme-buy-input input:focus{border-color:var(--primary-color);box-shadow:0 0 0 3px rgba(var(--primary-rgb,124,58,237),.12)}
.theme-buy-dialog small{display:block;color:#64748b;line-height:1.5;margin:9px 0 18px}
.theme-buy-submit{width:100%;height:50px;border:0;border-radius:12px;background:var(--primary-color);color:#fff;font-weight:800;cursor:pointer;box-shadow:0 10px 24px rgba(var(--primary-rgb,124,58,237),.22)}
body.theme-buy-locked{overflow:hidden}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('themeBuyModal');
    var form = document.getElementById('themeBuyForm');
    var input = document.getElementById('themeInstallDomain');
    var productName = document.getElementById('themeBuyProduct');

    function closeModal() {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('theme-buy-locked');
    }

    document.querySelectorAll('.js-theme-buy').forEach(function (button) {
        button.addEventListener('click', function () {
            form.action = button.getAttribute('data-checkout-url');
            productName.textContent = button.getAttribute('data-product-name') || '';
            input.value = '';
            modal.classList.add('open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.classList.add('theme-buy-locked');
            setTimeout(function () { input.focus(); }, 50);
        });
    });

    modal.querySelectorAll('[data-theme-buy-close]').forEach(function (button) {
        button.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && modal.classList.contains('open')) closeModal();
    });
});
</script>
