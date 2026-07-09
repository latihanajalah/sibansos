<x-guest-layout>
    <div style="text-align: center; padding: 3rem 1rem;">
        <div style="margin-bottom: 2rem;">
            <i class="bi bi-info-circle" style="font-size: 3rem; color: #DC2626;"></i>
        </div>
        <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 0.5rem;">Pendaftaran Tidak Tersedia</h2>
        <p style="color: #6B7280; margin-bottom: 2rem; line-height: 1.5;">
            Sistem ini tidak memiliki fitur pendaftaran publik. Silakan hubungi administrator untuk membuat akun.
        </p>
        <a href="{{ route('login') }}" style="display: inline-block; background: #DC2626; color: white; padding: 0.875rem 1.5rem; border-radius: 0.5rem; text-decoration: none; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='#991B1B'; this.style.transform='translateY(-2px)';" onmouseout="this.style.background='#DC2626'; this.style.transform='translateY(0)';">
            Kembali ke Login
        </a>
    </div>
</x-guest-layout>
