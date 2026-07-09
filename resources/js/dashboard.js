window.showSuccessToast = function (message) {
    if (window.Swal) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: message,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            customClass: {
                popup: 'shadow border border-white',
            },
        });
        return;
    }

    const tempAlert = document.createElement('div');
    tempAlert.className = 'alert alert-success border-0 shadow-sm d-flex align-items-center gap-2 mb-4';
    tempAlert.style.position = 'fixed';
    tempAlert.style.top = '1rem';
    tempAlert.style.right = '1rem';
    tempAlert.style.zIndex = '1050';
    tempAlert.innerHTML = `<i class="bi bi-check-circle-fill fs-5"></i><div>${message}</div>`;

    document.body.appendChild(tempAlert);
    setTimeout(() => tempAlert.remove(), 3000);
};

document.addEventListener('DOMContentLoaded', function () {
    const sidebarToggle = document.getElementById('sb-sidebar-toggle');
    const sidebar = document.getElementById('sb-sidebar');
    const content = document.getElementById('sb-content');

    if (sidebarToggle && sidebar && content) {
        sidebarToggle.addEventListener('click', function (e) {
            e.preventDefault();
            
            // Check if mobile or desktop
            if (window.innerWidth <= 768) {
                // On mobile, toggle class to slide in/out
                sidebar.classList.toggle('show-mobile');
            } else {
                // On desktop, toggle collapse
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
            }
        });
    }

    // Close mobile sidebar when window is resized to desktop width
    window.addEventListener('resize', function () {
        if (window.innerWidth > 768) {
            sidebar.classList.remove('show-mobile');
        }
    });
});

// Input filters: numeric-only and text-only enforcement
function attachInputFilters() {
    function isControlKey(e) {
        const code = e.keyCode;
        // allow: backspace(8), tab(9), enter(13), escape(27), delete(46), arrows(37-40)
        return [8,9,13,27,46,37,38,39,40].includes(code) || e.ctrlKey || e.metaKey;
    }

    // Numeric-only inputs (class 'only-number')
    document.querySelectorAll('input.only-number, input[type="number"][data-enforce-number], input[inputmode="numeric"]').forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (isControlKey(e)) return;
            const k = e.key;
            // allow digits
            if (/^[0-9]$/.test(k)) return;
            // prevent other keys
            e.preventDefault();
        });

        // handle paste
        el.addEventListener('paste', function (e) {
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^[0-9]*$/.test(paste)) {
                e.preventDefault();
                // insert only numbers
                const numbers = paste.replace(/[^0-9]/g, '');
                const start = el.selectionStart || 0;
                const end = el.selectionEnd || 0;
                const value = el.value;
                el.value = value.slice(0, start) + numbers + value.slice(end);
            }
        });

        // sanitize input on input event (covers mobile keyboards)
        el.addEventListener('input', function () {
            const cleaned = this.value.replace(/[^0-9]/g, '');
            if (this.value !== cleaned) this.value = cleaned;
        });
    });

    // Text-only inputs (class 'only-text') — prevent digits
    document.querySelectorAll('input.only-text').forEach(function (el) {
        el.addEventListener('keydown', function (e) {
            if (isControlKey(e)) return;
            const k = e.key;
            if (/^[0-9]$/.test(k)) {
                e.preventDefault();
            }
        });

        el.addEventListener('paste', function (e) {
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            if (/[0-9]/.test(paste)) {
                e.preventDefault();
                const letters = paste.replace(/[0-9]/g, '');
                const start = el.selectionStart || 0;
                const end = el.selectionEnd || 0;
                const value = el.value;
                el.value = value.slice(0, start) + letters + value.slice(end);
            }
        });

        el.addEventListener('input', function () {
            const cleaned = this.value.replace(/[0-9]/g, '');
            if (this.value !== cleaned) this.value = cleaned;
        });
    });
}

document.addEventListener('DOMContentLoaded', attachInputFilters);

// Currency formatter: auto-format input as Rupiah with thousand separators
function parseCurrencyDigits(value) {
    const raw = String(value || '').trim();
    if (!raw) {
        return '';
    }

    const sanitized = raw.replace(/[^0-9.]/g, '');
    const withDecimal = sanitized.match(/^(.*)\.(\d{2})$/);
    if (withDecimal) {
        return withDecimal[1].replace(/\D/g, '');
    }

    return sanitized.replace(/\D/g, '');
}

function formatCurrencyInput(value) {
    const cleaned = parseCurrencyDigits(value);
    if (!cleaned) return '';
    return cleaned.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function attachCurrencyFormatters() {
    // target inputs with class 'format-currency'
    document.querySelectorAll('input.format-currency').forEach(function (el) {
        // Store original numeric value in data attribute
        el.dataset.numericValue = parseCurrencyDigits(el.value);
        
        // Initial display of formatted value if has value
        if (el.dataset.numericValue) {
            el.value = formatCurrencyInput(el.dataset.numericValue);
        }

        // Keyboard input: only allow numeric keys
        el.addEventListener('keydown', function (e) {
            const code = e.keyCode;
            // Allow: backspace, delete, tab, escape, left, right, home, end, insert
            const isControl = [8, 9, 27, 46, 37, 38, 39, 40, 36, 35, 45].includes(code);
            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            const isCmdKey = (e.ctrlKey === true || e.metaKey === true);
            
            if (!isControl && !isCmdKey && !/^[0-9]$/.test(e.key)) {
                e.preventDefault();
            }
        });

        // Handle paste: clean non-numeric and reformat
        el.addEventListener('paste', function (e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            const numeric = parseCurrencyDigits(text);
            const formatted = formatCurrencyInput(numeric);
            this.value = formatted;
            this.dataset.numericValue = numeric;
            
            // Trigger input event for any dependent code
            this.dispatchEvent(new Event('input', { bubbles: true }));
        });

        // Format display on input
        el.addEventListener('input', function () {
            const numeric = parseCurrencyDigits(this.value);
            this.dataset.numericValue = numeric;
            
            // Update display with formatted version
            if (numeric) {
                const formatted = formatCurrencyInput(numeric);
                if (this.value !== formatted) {
                    this.value = formatted;
                }
            }
        });

        // Before form submit, restore clean numeric value
        const form = el.closest('form');
        if (form) {
            const submitHandler = function() {
                const numericVal = el.dataset.numericValue || parseCurrencyDigits(el.value);
                el.value = numericVal;
            };
            form.addEventListener('submit', submitHandler);
        }
    });
}

document.addEventListener('DOMContentLoaded', attachCurrencyFormatters);
