<?php
/**
 * contact.php — SQL-injection-safe, CSRF-protected, XSS-output-escaped.
 */
require_once 'includes/db.php';

if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$page_title = 'Contact Us';
$success = $error = '';

$allowed_tanks = ['500L – 1,000L','1,500L – 3,000L','5,000L – 10,000L','15,000L – 24,000L','25,000L+','Not sure'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF check
    if (!hash_equals($csrf_token, $_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission. Please refresh and try again.';
    } else {
        $name    = trim(strip_tags($_POST['full_name'] ?? ''));
        $phone   = trim(strip_tags($_POST['phone']     ?? ''));
        $email   = trim(strip_tags($_POST['email']     ?? ''));
        $loc     = trim(strip_tags($_POST['location']  ?? ''));
        $tank    = trim(strip_tags($_POST['tank_size'] ?? ''));
        $message = trim(strip_tags($_POST['message']   ?? ''));

        // Validate email if supplied
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        }
        // Validate phone if supplied
        if (!$error && $phone && !preg_match('/^[0-9\s\+\-]{7,20}$/', $phone)) {
            $error = 'Please enter a valid phone number.';
        }
        // Whitelist tank size
        if ($tank && !in_array($tank, $allowed_tanks, true)) $tank = '';

        if (!$error && $name && $message) {
            $stmt = $conn->prepare(
                "INSERT INTO contact_messages (full_name, phone, email, location, tank_size, message)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('ssssss', $name, $phone, $email, $loc, $tank, $message);

            if ($stmt->execute()) {
                $stmt->close();
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
                $success   = "Message received! We'll get back to you soon, {$safe_name}.";
            } else {
                $stmt->close();
                $error = "Something went wrong. Please call us on 0114676477.";
            }
        } elseif (!$error) {
            $error = "Please fill in your name and message.";
        }
    }
}

$csrf_token = $_SESSION['csrf_token'];
?>
<?php include 'includes/header.php'; ?>

<!-- Page Hero -->
<div class="page-hero">
    <div class="container" style="text-align:center; position:relative; z-index:2;">
        <span class="section-label" style="color:var(--cyan);">We're Here to Help</span>
        <h1 style="font-family:var(--font-display); font-size:clamp(2rem,5vw,3rem); color:white; margin-bottom:14px;">
            Contact Us
        </h1>
        <p style="color:rgba(255,255,255,0.75); max-width:500px; margin:0 auto;">
            Have a question or ready to book? Reach out and we'll respond promptly.
        </p>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div>
                <h3 style="font-family:var(--font-display); color:var(--navy); margin-bottom:24px; font-size:1.5rem;">
                    Get In Touch
                </h3>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-phone-alt"></i></div>
                    <div>
                        <div class="contact-item-label">Phone Numbers</div>
                        <div class="contact-item-value">
                            <a href="tel:+254114676477">0114 676 477</a><br>
                            <a href="tel:+254104852047">0104 852 047</a>
                        </div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon" style="background:#25D366;"><i class="fab fa-whatsapp"></i></div>
                    <div>
                        <div class="contact-item-label">WhatsApp</div>
                        <div class="contact-item-value">
                            <a href="https://wa.me/254114676477?text=Hello%20Longguard%2C%20I%20need%20help%20with%20my%20water%20tank."
                               target="_blank" rel="noopener noreferrer">
                                Chat with us on WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                    <div>
                        <div class="contact-item-label">Email Address</div>
                        <div class="contact-item-value">
                            <a href="mailto:info@longguardtankcare.co.ke">info@longguardtankcare.co.ke</a>
                        </div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <div class="contact-item-label">Location</div>
                        <div class="contact-item-value">Nairobi, Kenya</div>
                    </div>
                </div>
                <div class="contact-item">
                    <div class="contact-item-icon"><i class="fas fa-clock"></i></div>
                    <div>
                        <div class="contact-item-label">Working Hours</div>
                        <div class="contact-item-value">
                            Mon – Sat: 7:00 AM – 6:00 PM<br>
                            <span style="color:var(--mid-gray); font-size:0.85rem;">Emergency calls accepted on Sundays</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:28px; padding:20px; background:linear-gradient(135deg,var(--navy),var(--blue)); border-radius:var(--radius-md); color:white; text-align:center;">
                    <p style="font-size:0.9rem; opacity:0.8; margin-bottom:14px;">Need urgent help? WhatsApp us now!</p>
                    <a href="https://wa.me/254114676477" target="_blank" rel="noopener noreferrer"
                       class="btn btn-wa" style="width:100%; justify-content:center;">
                        <i class="fab fa-whatsapp"></i> Open WhatsApp
                    </a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="form-card fade-in">
                <h3 style="font-family:var(--font-display); color:var(--navy); margin-bottom:24px; font-size:1.3rem;">
                    Send Us a Message
                </h3>

                <?php if ($success): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> <?php echo $success; ?></div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
                <?php endif; ?>

                <form method="POST" class="validate-form" novalidate id="contactForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-alert" id="formAlert" style="display:none;" role="alert"></div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name <span aria-hidden="true">*</span></label>
                            <input type="text" id="full_name" name="full_name"
                                   placeholder="Your name"
                                   required minlength="2" maxlength="100"
                                   autocomplete="name"
                                   pattern="[A-Za-z\s\-\.']+"
                                   aria-required="true">
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone"
                                   placeholder="0712 345 678"
                                   pattern="[0-9\s\+\-]{7,20}"
                                   autocomplete="tel">
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email"
                               placeholder="your@email.com"
                               maxlength="150"
                               autocomplete="email">
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="location">Your Location</label>
                            <input type="text" id="location" name="location"
                                   placeholder="e.g. Westlands, Nairobi"
                                   maxlength="150"
                                   autocomplete="address-level2">
                        </div>
                        <div class="form-group">
                            <label for="tank_size">Tank Size (if known)</label>
                            <select id="tank_size" name="tank_size">
                                <option value="">Select size</option>
                                <?php foreach ($allowed_tanks as $t): ?>
                                <option value="<?php echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="message">Message <span aria-hidden="true">*</span></label>
                        <textarea id="message" name="message" rows="5"
                                  required minlength="10" maxlength="2000"
                                  placeholder="Tell us about your tank, location, or any questions you have..."
                                  aria-required="true"></textarea>
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
(function () {
    const form  = document.getElementById('contactForm');
    const alert = document.getElementById('formAlert');

    function showFieldError(input, msg) {
        const span = input.closest('.form-group')?.querySelector('.field-error');
        if (span) { span.textContent = msg; span.style.cssText = 'display:block;color:#dc2626;font-size:.78rem;margin-top:4px;'; }
        input.style.borderColor = '#dc2626';
    }
    function clearFieldError(input) {
        const span = input.closest('.form-group')?.querySelector('.field-error');
        if (span) span.textContent = '';
        input.style.borderColor = '';
    }

    function validateField(el) {
        clearFieldError(el);
        if (el.required && !el.value.trim()) { showFieldError(el, 'This field is required.'); return false; }
        if (el.id === 'email' && el.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(el.value)) {
            showFieldError(el, 'Please enter a valid email address.'); return false;
        }
        if (el.id === 'phone' && el.value && !/^[0-9\s\+\-]{7,20}$/.test(el.value)) {
            showFieldError(el, 'Enter a valid phone number (e.g. 0712 345 678).'); return false;
        }
        if (el.id === 'message' && el.value && el.value.trim().length < 10) {
            showFieldError(el, 'Message must be at least 10 characters.'); return false;
        }
        return true;
    }

    form.querySelectorAll('input, select, textarea').forEach(function (el) {
        el.addEventListener('blur',  function () { validateField(el); });
        el.addEventListener('input', function () { clearFieldError(el); });
    });

    form.addEventListener('submit', function (e) {
        let valid = true;
        ['full_name','email','phone','message'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el && !validateField(el)) valid = false;
        });
        if (!valid) {
            e.preventDefault();
            alert.style.cssText = 'display:flex;align-items:center;gap:8px;background:#fff5f5;border:1px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:8px;font-size:.875rem;margin-bottom:16px;';
            alert.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please fix the errors above before submitting.';
        }
    });
})();
</script>

<?php include 'includes/footer.php'; ?>
