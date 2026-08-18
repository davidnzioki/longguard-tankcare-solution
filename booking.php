<?php
/**
 * booking.php — SQL-injection-safe, CSRF-protected, XSS-output-escaped.
 * Uses prepared statements everywhere; no raw string interpolation in SQL.
 */
require_once 'includes/db.php';

// ── CSRF helper (shared across forms) ─────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$page_title = 'Book a Service';
$success = $error = '';

// Pre-fill tank size from URL (sanitised for output only — never used raw in SQL)
$prefill_size = isset($_GET['size']) ? htmlspecialchars(strip_tags($_GET['size']), ENT_QUOTES, 'UTF-8') : '';

// Allowed values for select fields (server-side whitelist)
$allowed_tanks    = ['500L – 1,000L','1,500L – 3,000L','5,000L – 10,000L','15,000L – 24,000L','25,000L+','Not sure'];
$allowed_services = ['Cleaning','Disinfection','Cleaning & Disinfection','Maintenance','Emergency'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ── CSRF verification ──────────────────────────────────────────────────────
    if (!hash_equals($csrf_token, $_POST['csrf_token'] ?? '')) {
        $error = 'Invalid form submission. Please refresh and try again.';
    } else {
        // ── Collect & sanitise inputs ──────────────────────────────────────────
        $name     = trim(strip_tags($_POST['full_name']  ?? ''));
        $phone    = trim(strip_tags($_POST['phone']      ?? ''));
        $location = trim(strip_tags($_POST['location']   ?? ''));
        $tank     = trim(strip_tags($_POST['tank_size']  ?? ''));
        $service  = trim(strip_tags($_POST['service_type'] ?? 'Cleaning'));
        $date_raw = trim($_POST['preferred_date'] ?? '');
        $message  = trim(strip_tags($_POST['message']   ?? ''));

        // Validate date format (YYYY-MM-DD) and ensure it's not in the past
        $date = '';
        if ($date_raw) {
            $d = DateTime::createFromFormat('Y-m-d', $date_raw);
            if ($d && $d->format('Y-m-d') === $date_raw && $d >= new DateTime('today')) {
                $date = $date_raw;
            }
        }

        // Whitelist selects
        if (!in_array($tank,    $allowed_tanks,    true)) $tank    = '';
        if (!in_array($service, $allowed_services, true)) $service = 'Cleaning';

        // Phone: allow digits, spaces, +, hyphens (basic Kenyan format check)
        if ($phone && !preg_match('/^[0-9\s\+\-]{7,20}$/', $phone)) {
            $error = 'Please enter a valid phone number.';
        }

        if (!$error && $name && $phone && $location && $tank) {
            // ── Prepared statement — zero SQL injection risk ────────────────────
            $stmt = $conn->prepare(
                "INSERT INTO bookings (full_name, phone, location, tank_size, service_type, preferred_date, message)
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmt->bind_param('sssssss', $name, $phone, $location, $tank, $service, $date, $message);

            if ($stmt->execute()) {
                $stmt->close();
                // Regenerate CSRF after successful submission
                $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
                $safe_name  = htmlspecialchars($name,  ENT_QUOTES, 'UTF-8');
                $safe_phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');
                $success    = "Thank you, {$safe_name}! Your booking request has been received. We will contact you shortly on {$safe_phone}.";
            } else {
                $stmt->close();
                $error = "Something went wrong. Please call us directly on 0114676477.";
            }
        } elseif (!$error) {
            $error = "Please fill in all required fields.";
        }
    }
}

// Re-read (possibly regenerated) token for the form
$csrf_token = $_SESSION['csrf_token'];
?>
<?php include 'includes/header.php'; ?>

<!-- Booking Hero -->
<div class="booking-hero">
    <div class="container">
        <span class="section-label" style="color:var(--cyan);">Schedule a Visit</span>
        <h1 class="section-title" style="color:white; margin-bottom:12px;">Book Your Tank Cleaning</h1>
        <p class="section-subtitle" style="color:rgba(255,255,255,0.75);">
            Fill out the form below and we'll contact you within a few hours to confirm your appointment.
        </p>
    </div>
</div>

<section class="booking-body">
    <div class="container">
        <div class="booking-grid">
            <!-- Info Panel -->
            <div class="booking-info-card">
                <h3><i class="fas fa-info-circle" style="color:var(--cyan); margin-right:8px;"></i> Booking Info</h3>
                <div class="booking-info-item">
                    <i class="fas fa-clock"></i>
                    <div><strong>Response Time</strong><p>We confirm bookings within 2–4 hours</p></div>
                </div>
                <div class="booking-info-item">
                    <i class="fas fa-calendar-check"></i>
                    <div><strong>Availability</strong><p>Monday – Saturday, 7:00 AM – 6:00 PM</p></div>
                </div>
                <div class="booking-info-item">
                    <i class="fas fa-bolt"></i>
                    <div><strong>Emergency Service</strong><p>Same-day available — call us directly</p></div>
                </div>
                <hr class="booking-divider">
                <div class="booking-info-item">
                    <i class="fas fa-phone-alt"></i>
                    <div><strong>Call Directly</strong>
                        <p><a href="tel:+254114676477" style="color:var(--cyan);">0114 676 477</a></p>
                        <p><a href="tel:+254104852047" style="color:var(--cyan);">0104 852 047</a></p>
                    </div>
                </div>
                <div class="booking-info-item">
                    <i class="fab fa-whatsapp"></i>
                    <div><strong>WhatsApp</strong>
                        <p><a href="https://wa.me/254114676477" target="_blank" rel="noopener noreferrer" style="color:#25D366;">Chat with us now</a></p>
                    </div>
                </div>
                <hr class="booking-divider">
                <p style="font-size:0.8rem; color:rgba(255,255,255,0.4); line-height:1.6;">
                    <i class="fas fa-shield-alt" style="color:var(--cyan);"></i>
                    Your information is kept private and only used to process your booking.
                </p>
            </div>

            <!-- Booking Form -->
            <div class="form-card fade-in">
                <h3 style="font-family:var(--font-display); color:var(--navy); margin-bottom:24px; font-size:1.4rem;">
                    Request a Service
                </h3>

                <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
                <?php endif; ?>
                <?php if ($error): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <?php endif; ?>

                <form method="POST" class="validate-form" novalidate id="bookingForm">
                    <!-- CSRF token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="form-alert" id="formAlert" style="display:none;" role="alert"></div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name <span aria-hidden="true">*</span></label>
                            <input type="text" id="full_name" name="full_name"
                                   placeholder="e.g. John Kamau"
                                   required minlength="2" maxlength="100"
                                   autocomplete="name"
                                   pattern="[A-Za-z\s\-\.']+"
                                   title="Name should contain letters only"
                                   aria-required="true">
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number <span aria-hidden="true">*</span></label>
                            <input type="tel" id="phone" name="phone"
                                   placeholder="e.g. 0712 345 678"
                                   required
                                   pattern="[0-9\s\+\-]{7,20}"
                                   title="Enter a valid Kenyan phone number"
                                   autocomplete="tel"
                                   aria-required="true">
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="location">Your Location / Estate <span aria-hidden="true">*</span></label>
                        <input type="text" id="location" name="location"
                               placeholder="e.g. Roysambu, Nairobi"
                               required minlength="3" maxlength="150"
                               autocomplete="address-level2"
                               aria-required="true">
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tank_size">Tank Size <span aria-hidden="true">*</span></label>
                            <select id="tank_size" name="tank_size" required aria-required="true">
                                <option value="" disabled <?php echo !$prefill_size ? 'selected' : ''; ?>>Select tank size</option>
                                <?php foreach ($allowed_tanks as $t): ?>
                                <option value="<?php echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); ?>"
                                    <?php echo $prefill_size === $t ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($t, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-error" aria-live="polite"></span>
                        </div>
                        <div class="form-group">
                            <label for="service_type">Service Type</label>
                            <select id="service_type" name="service_type">
                                <option value="Cleaning">Tank Cleaning</option>
                                <option value="Disinfection">Disinfection Only</option>
                                <option value="Cleaning & Disinfection">Cleaning + Disinfection</option>
                                <option value="Maintenance">Maintenance Inspection</option>
                                <option value="Emergency">Emergency Service</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="preferred_date">Preferred Date</label>
                        <input type="date" id="preferred_date" name="preferred_date"
                               min="<?php echo date('Y-m-d'); ?>">
                        <span class="field-error" aria-live="polite"></span>
                    </div>

                    <div class="form-group">
                        <label for="message">Additional Notes</label>
                        <textarea id="message" name="message" rows="4"
                                  maxlength="1000"
                                  placeholder="Any details about your tank, access, or special requirements..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg" style="width:100%; justify-content:center;">
                        <i class="fas fa-paper-plane"></i> Submit Booking Request
                    </button>
                    <p style="text-align:center; margin-top:14px; font-size:0.82rem; color:var(--mid-gray);">
                        Or call us directly: <a href="tel:+254114676477" style="color:var(--blue); font-weight:600;">0114 676 477</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
/* ── Enhanced client-side validation ── */
(function () {
    const form  = document.getElementById('bookingForm');
    const alert = document.getElementById('formAlert');

    function showFieldError(input, msg) {
        const span = input.parentElement.querySelector('.field-error');
        if (span) { span.textContent = msg; span.style.cssText = 'display:block;color:#dc2626;font-size:.78rem;margin-top:4px;'; }
        input.style.borderColor = '#dc2626';
    }
    function clearFieldError(input) {
        const span = input.parentElement.querySelector('.field-error');
        if (span) span.textContent = '';
        input.style.borderColor = '';
    }

    // Live validation on blur
    form.querySelectorAll('input, select, textarea').forEach(function (el) {
        el.addEventListener('blur', function () { validateField(el); });
        el.addEventListener('input', function () { clearFieldError(el); });
    });

    function validateField(el) {
        clearFieldError(el);
        if (el.required && !el.value.trim()) {
            showFieldError(el, 'This field is required.'); return false;
        }
        if (el.id === 'phone' && el.value && !/^[0-9\s\+\-]{7,20}$/.test(el.value)) {
            showFieldError(el, 'Enter a valid phone number (e.g. 0712 345 678).'); return false;
        }
        if (el.id === 'full_name' && el.value && !/^[A-Za-z\s\-\.']+$/.test(el.value)) {
            showFieldError(el, 'Name should contain letters only.'); return false;
        }
        if (el.id === 'preferred_date' && el.value) {
            const today = new Date(); today.setHours(0,0,0,0);
            if (new Date(el.value) < today) {
                showFieldError(el, 'Please select a future date.'); return false;
            }
        }
        return true;
    }

    form.addEventListener('submit', function (e) {
        let valid = true;
        ['full_name','phone','location','tank_size'].forEach(function (id) {
            const el = document.getElementById(id);
            if (el && !validateField(el)) valid = false;
        });
        if (!valid) {
            e.preventDefault();
            alert.style.cssText = 'display:flex;align-items:center;gap:8px;background:#fff5f5;border:1px solid #fecaca;color:#dc2626;padding:12px 14px;border-radius:8px;font-size:.875rem;margin-bottom:16px;';
            alert.innerHTML = '<i class="fas fa-exclamation-circle"></i> Please fix the errors above before submitting.';
            form.querySelector('[required]:invalid')?.focus();
        }
    });
})();
</script>

<?php include 'includes/footer.php'; ?>
