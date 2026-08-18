<?php
require_once 'includes/db.php';
$page_title = 'Home';

// Fetch testimonials from DB
$testimonials = [];
$result = $conn->query("SELECT * FROM testimonials WHERE is_active = 1 ORDER BY id LIMIT 5");
if ($result) {
    while ($row = $result->fetch_assoc()) $testimonials[] = $row;
}

// Fetch pricing — GROUP BY size_range to deduplicate if SQL was imported twice
$pricing_rows = [];
$result2 = $conn->query("SELECT size_range, MIN(starting_price) as starting_price, MAX(is_quote) as is_quote FROM pricing WHERE is_active = 1 GROUP BY size_range ORDER BY MIN(id)");
if ($result2) {
    while ($row = $result2->fetch_assoc()) $pricing_rows[] = $row;
}
?>
<?php include 'includes/header.php'; ?>

<!-- ========= HERO ========= -->
<section class="hero" style="position:relative;">
    <!-- Hero background image -->
    <div style="position:absolute;inset:0;z-index:0;overflow:hidden;">
        <img src="https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=1600&q=80"
             alt="Professional water tank cleaning"
             style="width:100%;height:100%;object-fit:cover;opacity:0.18;">
    </div>
    <div class="hero-bg" style="position:absolute;inset:0;z-index:1;">
        <div class="hero-ripple"></div>
        <div class="hero-ripple"></div>
        <div class="hero-ripple"></div>
    </div>
    <div class="container" style="position:relative;z-index:2;">
        <div class="hero-content fade-in">
            <div class="hero-badge">
                <i class="fas fa-shield-alt"></i>
                Kenya's #1 Trusted Tank Cleaning Specialists
            </div>
            <h1 class="hero-title">
                Is Your Water Tank<br>
                <span class="highlight">Safe to Drink From?</span>
            </h1>
            <p class="hero-sub">
                Most tanks carry hidden sludge, algae, and bacteria — even when the water looks clean.
                Longguard removes every trace, leaving your family with water that's truly safe.
                Homes, schools, hospitals &amp; businesses across Kenya trust us.
            </p>
            <div class="hero-actions">
                <a href="booking.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-calendar-check"></i> Book a Cleaning
                </a>
                <a href="https://wa.me/254114676477?text=Hello%20Longguard%2C%20I%20need%20a%20tank%20cleaning%20service." 
                   target="_blank" class="btn btn-wa btn-lg">
                    <i class="fab fa-whatsapp"></i> WhatsApp Us
                </a>
                <a href="tel:+254114676477" class="btn btn-outline btn-lg">
                    <i class="fas fa-phone-alt"></i> Call 0114 676 477
                </a>
            </div>
            <div class="hero-stats">
                <div class="hero-stat">
                    <div class="hero-stat-num" data-target="500" data-suffix="+">0+</div>
                    <div class="hero-stat-label">Tanks Cleaned</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num" data-target="8" data-suffix="+">0+</div>
                    <div class="hero-stat-label">Counties Served</div>
                </div>
                <div class="hero-stat">
                    <div class="hero-stat-num" data-target="100" data-suffix="%">0%</div>
                    <div class="hero-stat-label">Client Satisfaction</div>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-scroll">
        <span>Scroll Down</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</section>

<!-- Wave divider -->
<div class="divider-wave" style="background:var(--navy)">
    <svg viewBox="0 0 1440 70" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,35 C360,70 1080,0 1440,35 L1440,70 L0,70 Z" fill="white"/>
    </svg>
</div>

<!-- ========= ABOUT ========= -->
<section class="section" id="about">
    <div class="container">
        <div class="about-grid">
            <div class="about-image-wrap fade-in">
                <div class="about-image-main" style="padding:0; overflow:hidden; border-radius:var(--radius-lg);">
                    <img src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?w=700&q=85"
                         alt="Longguard professional water tank cleaning team"
                         style="width:100%; height:100%; object-fit:cover; display:block; border-radius:var(--radius-lg);">
                </div>
                <div class="about-badge">
                    <div class="about-badge-num">✓</div>
                    <div class="about-badge-label">Certified & Insured</div>
                </div>
            </div>
            <div class="fade-in">
                <span class="section-label">Who We Are</span>
                <h2 class="section-title">Dedicated to Safe, Clean Water Storage</h2>
                <p style="color:var(--mid-gray); margin-bottom:18px; line-height:1.75;">
                    Longguard Tankcare Solutions Ltd is a professional water tank cleaning and maintenance company 
                    dedicated to ensuring safe, clean, and hygienic water storage solutions for homes and businesses 
                    throughout Kenya.
                </p>
                <p style="color:var(--mid-gray); margin-bottom:24px; line-height:1.75;">
                    Our trained team uses modern cleaning methods and quality equipment to remove dirt, sludge, algae, 
                    and contaminants from water storage tanks — leaving your water supply safe for drinking, cooking, 
                    and daily use.
                </p>
                <ul class="about-list">
                    <li><i class="fas fa-check-circle"></i> Trained and professional technicians on every job</li>
                    <li><i class="fas fa-check-circle"></i> Eco-friendly, government-approved disinfectants</li>
                    <li><i class="fas fa-check-circle"></i> Same-day service available in select areas</li>
                    <li><i class="fas fa-check-circle"></i> Full cleaning report after every service</li>
                    <li><i class="fas fa-check-circle"></i> Annual maintenance plans available</li>
                </ul>
                <a href="booking.php" class="btn btn-primary" style="margin-top:8px;">
                    <i class="fas fa-calendar-check"></i> Book a Service Today
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ========= HOW IT WORKS ========= -->
<section class="section section--alt" id="process">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-label">Simple Process</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Getting your tank cleaned is easy. We handle everything from booking to final inspection.</p>
        </div>
        <div class="process-steps">
            <div class="process-step fade-in">
                <div class="step-num">01</div>
                <h4>Book Online or Call</h4>
                <p>Fill out our quick booking form or call/WhatsApp us directly to schedule your service.</p>
            </div>
            <div class="process-step fade-in">
                <div class="step-num">02</div>
                <h4>We Visit & Assess</h4>
                <p>Our team arrives at your location, assesses the tank, and begins the cleaning process.</p>
            </div>
            <div class="process-step fade-in">
                <div class="step-num">03</div>
                <h4>Deep Clean & Disinfect</h4>
                <p>We scrub, vacuum, and disinfect the tank thoroughly using approved methods and equipment.</p>
            </div>
            <div class="process-step fade-in">
                <div class="step-num">04</div>
                <h4>Final Inspection</h4>
                <p>We perform a final inspection and provide a written report confirming your water is safe.</p>
            </div>
        </div>
    </div>
</section>

<!-- ========= SERVICES ========= -->
<section class="section" id="services">
    <div class="container">
        <div class="section-header">
            <span class="section-label">What We Do</span>
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Comprehensive water tank solutions for every type of property and tank size.</p>
        </div>
        <div class="services-grid">
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-home"></i></div>
                <h3>Domestic Tank Cleaning</h3>
                <p>Complete cleaning service for household water tanks ensuring your family's water is safe and healthy.</p>
                <ul>
                    <li>All tank sizes (500L – 10,000L)</li>
                    <li>Apartment & maisonette tanks</li>
                    <li>Overhead & underground tanks</li>
                    <li>Borehole storage tanks</li>
                </ul>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-building"></i></div>
                <h3>Commercial Tank Cleaning</h3>
                <p>Professional-grade cleaning for businesses, schools, hospitals, hotels, and commercial properties.</p>
                <ul>
                    <li>High-capacity industrial tanks</li>
                    <li>Hotels & hospitality facilities</li>
                    <li>Schools & hospitals</li>
                    <li>Commercial complexes</li>
                </ul>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-bacteria"></i></div>
                <h3>Tank Disinfection</h3>
                <p>Eliminate harmful bacteria, algae, and waterborne pathogens to improve your water quality.</p>
                <ul>
                    <li>Bacteria & pathogen removal</li>
                    <li>Algae treatment</li>
                    <li>WHO-approved chemicals</li>
                    <li>Water quality testing</li>
                </ul>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-tools"></i></div>
                <h3>Tank Maintenance</h3>
                <p>Preventative maintenance plans to keep your tank in top condition year-round.</p>
                <ul>
                    <li>Leak detection & inspection</li>
                    <li>Structural assessment</li>
                    <li>Minor repairs</li>
                    <li>Annual maintenance plans</li>
                </ul>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-hard-hat"></i></div>
                <h3>Underground Tank Cleaning</h3>
                <p>Specialist equipment and expertise to safely clean underground and semi-buried tanks.</p>
                <ul>
                    <li>Deep underground tanks</li>
                    <li>Sump pit cleaning</li>
                    <li>Sediment removal</li>
                    <li>Structural check included</li>
                </ul>
            </div>
            <div class="service-card fade-in">
                <div class="service-icon"><i class="fas fa-clipboard-list"></i></div>
                <h3>Tank Installation Support</h3>
                <p>Site assessment and ongoing maintenance planning for new tank installations.</p>
                <ul>
                    <li>Site assessment</li>
                    <li>Placement recommendations</li>
                    <li>Ongoing maintenance plans</li>
                    <li>Post-installation cleaning</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- ========= PRICING ========= -->
<section class="section section--alt" id="pricing">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-label">Transparent Pricing</span>
            <h2 class="section-title">Tank Sizes & Starting Prices</h2>
            <p class="section-subtitle">Affordable pricing for every home and business. Final price depends on tank condition and location.</p>
        </div>

        <!-- Pricing Cards -->
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-bottom:24px;">

            <?php
            $display_pricing = !empty($pricing_rows) ? $pricing_rows : [
                ['size_range'=>'500L – 1,000L',    'starting_price'=>1500, 'is_quote'=>0],
                ['size_range'=>'1,500L – 3,000L',  'starting_price'=>2500, 'is_quote'=>0],
                ['size_range'=>'5,000L – 10,000L', 'starting_price'=>4500, 'is_quote'=>0],
                ['size_range'=>'15,000L – 24,000L','starting_price'=>8000, 'is_quote'=>0],
                ['size_range'=>'25,000L+',         'starting_price'=>null, 'is_quote'=>1],
            ];
            $icons = ['fas fa-home','fas fa-building','fas fa-industry','fas fa-warehouse','fas fa-city'];
            $labels = ['Home / Flat','Apartment','Commercial','Large Commercial','Industrial'];
            foreach ($display_pricing as $i => $row):
                $is_popular = ($i === 1); // highlight 1,500–3,000L as most popular
            ?>
            <div style="background:white; border-radius:16px; padding:28px 20px 24px; text-align:center;
                        box-shadow:0 4px 20px rgba(0,0,0,0.07);
                        border:2px solid <?php echo $is_popular ? 'var(--cyan)' : 'transparent'; ?>;
                        position:relative; transition:transform .2s, box-shadow .2s;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 30px rgba(0,0,0,0.12)'"
                 onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.07)'">

                <?php if ($is_popular): ?>
                <div style="position:absolute;top:-13px;left:50%;transform:translateX(-50%);
                            background:var(--cyan);color:white;font-size:0.72rem;font-weight:700;
                            padding:4px 14px;border-radius:20px;letter-spacing:0.05em;white-space:nowrap;">
                    MOST POPULAR
                </div>
                <?php endif; ?>

                <div style="width:52px;height:52px;border-radius:50%;
                            background:<?php echo $is_popular ? 'var(--cyan)' : 'var(--navy)'; ?>;
                            display:flex;align-items:center;justify-content:center;
                            margin:0 auto 14px;">
                    <i class="<?php echo $icons[$i] ?? 'fas fa-tint'; ?>" style="color:white;font-size:1.2rem;"></i>
                </div>

                <div style="font-size:0.78rem;color:var(--mid-gray);font-weight:600;text-transform:uppercase;
                            letter-spacing:0.06em;margin-bottom:4px;">
                    <?php echo $labels[$i] ?? ''; ?>
                </div>

                <div style="font-size:1rem;font-weight:700;color:var(--navy);margin-bottom:12px;">
                    <?php echo htmlspecialchars($row['size_range']); ?>
                </div>

                <div style="font-size:<?php echo $row['is_quote'] ? '1.1rem' : '1.8rem'; ?>;
                            font-weight:800;color:<?php echo $is_popular ? 'var(--cyan)' : 'var(--blue)'; ?>;
                            margin-bottom:14px;line-height:1.1;">
                    <?php if ($row['is_quote']): ?>
                        <span style="font-style:italic;font-weight:600;color:var(--mid-gray);">Custom Quote</span>
                    <?php else: ?>
                        KES <?php echo number_format($row['starting_price']); ?>
                    <?php endif; ?>
                </div>

                <ul style="list-style:none;padding:0;margin:0 0 18px;font-size:0.82rem;color:var(--mid-gray);text-align:left;">
                    <li style="padding:4px 0;border-bottom:1px solid #f0f0f0;">
                        <i class="fas fa-check" style="color:var(--cyan);margin-right:7px;"></i>Deep Cleaning
                    </li>
                    <li style="padding:4px 0;border-bottom:1px solid #f0f0f0;">
                        <i class="fas fa-check" style="color:var(--cyan);margin-right:7px;"></i>Disinfection
                    </li>
                    <li style="padding:4px 0;">
                        <i class="fas fa-check" style="color:var(--cyan);margin-right:7px;"></i>Cleaning Report
                    </li>
                </ul>

                <a href="booking.php?size=<?php echo urlencode($row['size_range']); ?>"
                   style="display:block;padding:10px;border-radius:8px;font-size:0.88rem;font-weight:700;
                          text-decoration:none;text-align:center;transition:opacity .2s;
                          background:<?php echo $is_popular ? 'var(--cyan)' : 'var(--navy)'; ?>;
                          color:white;">
                    <i class="fas fa-calendar-plus" style="margin-right:6px;"></i>
                    <?php echo $row['is_quote'] ? 'Get a Quote' : 'Book Now'; ?>
                </a>
            </div>
            <?php endforeach; ?>

        </div>

        <p class="pricing-note fade-in">
            <i class="fas fa-info-circle"></i>
            <strong>Note:</strong> Prices depend on accessibility, tank condition, location, and level of contamination. Contact us for an accurate quote.
        </p>
    </div>
</section>

<!-- ========= WHY CHOOSE US ========= -->
<section class="section section--dark" id="why">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-label">Why Longguard</span>
            <h2 class="section-title">Why Choose Us?</h2>
            <p class="section-subtitle" style="color:rgba(255,255,255,0.6);">We combine technical expertise with a genuine commitment to your health and satisfaction.</p>
        </div>
        <div class="why-grid">
            <div class="why-card fade-in">
                <div class="why-icon"><i class="fas fa-user-tie"></i></div>
                <h3>Professional Team</h3>
                <p>Trained, uniformed, and experienced technicians on every job. Background-checked for your peace of mind.</p>
            </div>
            <div class="why-card fade-in">
                <div class="why-icon"><i class="fas fa-tag"></i></div>
                <h3>Affordable Pricing</h3>
                <p>Competitive rates with no hidden fees. We believe safe water shouldn't be a luxury.</p>
            </div>
            <div class="why-card fade-in">
                <div class="why-icon"><i class="fas fa-bolt"></i></div>
                <h3>Fast Response</h3>
                <p>Same-day and next-day bookings available. We understand that safe water can't wait.</p>
            </div>
            <div class="why-card fade-in">
                <div class="why-icon"><i class="fas fa-cog"></i></div>
                <h3>Modern Equipment</h3>
                <p>High-powered pumps, specialized brushes, and vacuum systems for a thorough clean every time.</p>
            </div>
            <div class="why-card fade-in">
                <div class="why-icon"><i class="fas fa-certificate"></i></div>
                <h3>Reliable Service</h3>
                <p>We show up when we say we will and finish the job properly. Thousands of satisfied clients since we launched.</p>
            </div>
            <div class="why-card fade-in">
                <div class="why-icon"><i class="fas fa-heart"></i></div>
                <h3>Client Satisfaction</h3>
                <p>Every job comes with a satisfaction guarantee. We won't leave until you're completely happy with the result.</p>
            </div>
        </div>
    </div>
</section>

<!-- Wave divider -->
<div class="divider-wave" style="background:var(--navy);">
    <svg viewBox="0 0 1440 70" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0,35 C360,0 1080,70 1440,35 L1440,70 L0,70 Z" fill="var(--offwhite)"/>
    </svg>
</div>

<!-- ========= GALLERY ========= -->
<section class="section section--alt" id="gallery">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-label">Our Work</span>
            <h2 class="section-title">Before & After Gallery</h2>
            <p class="section-subtitle">See the transformation. Our thorough cleaning process delivers visible, measurable results.</p>
        </div>
        <!-- Simple 2-column before/after layout -->
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px; max-width:900px; margin:0 auto 16px;">

            <!-- BEFORE -->
            <div style="border-radius:16px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,0.12); position:relative;">
                <img src="assets/images/tank-before.png"
                     alt="Dirty water tank before Longguard cleaning"
                     style="width:100%; height:320px; object-fit:cover; display:block;">
                <div style="position:absolute; top:14px; left:14px; background:#ef4444; color:white;
                            font-size:0.78rem; font-weight:700; padding:5px 14px; border-radius:20px;
                            letter-spacing:0.05em; text-transform:uppercase;">
                    ⚠ Before
                </div>
                <div style="background:linear-gradient(0deg, rgba(0,0,0,0.75) 0%, transparent 100%);
                            position:absolute; bottom:0; left:0; right:0; padding:20px 18px 16px;">
                    <p style="color:white; margin:0; font-size:0.92rem; font-weight:600;">Dirty Tank</p>
                    <p style="color:rgba(255,255,255,0.75); margin:4px 0 0; font-size:0.8rem;">
                        Milky water, residue &amp; buildup — unsafe to drink
                    </p>
                </div>
            </div>

            <!-- AFTER -->
            <div style="border-radius:16px; overflow:hidden; box-shadow:0 6px 24px rgba(0,0,0,0.12); position:relative;">
                <img src="assets/images/tank-after.png"
                     alt="Clean water tank after Longguard professional cleaning"
                     style="width:100%; height:320px; object-fit:cover; display:block;">
                <div style="position:absolute; top:14px; left:14px; background:#16a34a; color:white;
                            font-size:0.78rem; font-weight:700; padding:5px 14px; border-radius:20px;
                            letter-spacing:0.05em; text-transform:uppercase;">
                    ✓ After
                </div>
                <div style="background:linear-gradient(0deg, rgba(0,0,0,0.75) 0%, transparent 100%);
                            position:absolute; bottom:0; left:0; right:0; padding:20px 18px 16px;">
                    <p style="color:white; margin:0; font-size:0.92rem; font-weight:600;">Clean Tank</p>
                    <p style="color:rgba(255,255,255,0.75); margin:4px 0 0; font-size:0.8rem;">
                        Crystal clear water — fully disinfected &amp; safe
                    </p>
                </div>
            </div>

        </div>
        <p style="text-align:center; color:var(--mid-gray); font-size:0.88rem; margin-top:10px;">
            <i class="fas fa-camera" style="color:var(--cyan); margin-right:6px;"></i>
            Real photos from our clients' tanks — before and after Longguard cleaning
        </p>
    </div>
</section>

<!-- ========= TESTIMONIALS ========= -->
<section class="section" id="testimonials">
    <div class="container">
        <div class="section-header section-header--center">
            <span class="section-label">Client Reviews</span>
            <h2 class="section-title">What Our Clients Say</h2>
            <p class="section-subtitle">Real feedback from real people across Kenya who trust Longguard with their water.</p>
        </div>
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $t): ?>
            <div class="testimonial-card fade-in">
                <div class="testimonial-quote">"</div>
                <p class="testimonial-text"><?php echo htmlspecialchars($t['message']); ?></p>
                <div class="testimonial-author">
                    <div class="author-avatar"><?php echo strtoupper(substr($t['client_name'], 0, 1)); ?></div>
                    <div>
                        <div class="author-name"><?php echo htmlspecialchars($t['client_name']); ?></div>
                        <div class="author-role"><?php echo htmlspecialchars($t['client_role']); ?> &mdash; <?php echo htmlspecialchars($t['location']); ?></div>
                        <div class="stars">
                            <?php for ($i = 0; $i < $t['rating']; $i++) echo '★'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ========= AREAS WE SERVE ========= -->
<section class="section section--alt" id="areas">
    <div class="container">
        <div class="areas-grid">
            <div class="fade-in">
                <span class="section-label">Coverage</span>
                <h2 class="section-title">Areas We Serve</h2>
                <p style="color:var(--mid-gray); margin-bottom:26px; line-height:1.75;">
                    We operate across the greater Nairobi region and are expanding our reach across Kenya. 
                    Wherever you are, we can help.
                </p>
                <div class="area-tags">
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Nairobi</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Thika</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Juja</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Ruiru</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Kiambu</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Machakos</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Kikuyu</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Limuru</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Ngong</span>
                    <span class="area-tag"><i class="fas fa-map-marker-alt"></i> Syokimau</span>
                </div>
                <div class="areas-note">
                    <i class="fas fa-globe-africa"></i>
                    <p><strong>Available for projects across Kenya.</strong> Distance surcharge may apply for locations beyond 50km from Nairobi CBD. Contact us to confirm availability in your area.</p>
                </div>
                <a href="booking.php" class="btn btn-primary" style="margin-top:24px;">
                    <i class="fas fa-map-marker-alt"></i> Book in Your Area
                </a>
            </div>
            <div class="about-image-wrap fade-in">
                <div class="areas-map">
                    <!-- Kenya SVG Map (simplified) -->
                    <svg viewBox="0 0 300 350" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <radialGradient id="kenyaGrad" cx="50%" cy="50%" r="50%">
                                <stop offset="0%" style="stop-color:var(--blue);stop-opacity:0.3"/>
                                <stop offset="100%" style="stop-color:var(--navy);stop-opacity:0.1"/>
                            </radialGradient>
                        </defs>
                        <!-- Kenya outline (simplified) -->
                        <path d="M100,20 L160,15 L200,30 L240,50 L260,90 L270,130 L260,170 L250,200 L230,230 L200,260 L180,290 L160,310 L140,330 L120,320 L100,300 L80,270 L60,240 L50,200 L40,160 L45,120 L60,80 L80,50 Z" 
                              fill="url(#kenyaGrad)" stroke="var(--blue)" stroke-width="2" stroke-linejoin="round"/>
                        <!-- Nairobi region highlight -->
                        <circle cx="130" cy="210" r="25" fill="rgba(0,180,216,0.25)" stroke="var(--cyan)" stroke-width="1.5" stroke-dasharray="4,3"/>
                        <!-- City dots -->
                        <circle cx="130" cy="210" r="7" fill="var(--cyan)" opacity="0.9"/>
                        <circle cx="145" cy="185" r="5" fill="var(--blue)" opacity="0.8"/>
                        <circle cx="155" cy="195" r="4" fill="var(--blue)" opacity="0.7"/>
                        <circle cx="140" cy="225" r="4" fill="var(--blue)" opacity="0.7"/>
                        <circle cx="118" cy="220" r="4" fill="var(--blue)" opacity="0.7"/>
                        <circle cx="160" cy="215" r="4" fill="var(--blue)" opacity="0.7"/>
                        <!-- Labels -->
                        <text x="138" y="213" font-size="8" fill="white" font-weight="bold" font-family="Inter, sans-serif">NBI</text>
                        <text x="148" y="183" font-size="7" fill="var(--navy)" font-family="Inter, sans-serif">Thika</text>
                        <text x="157" y="192" font-size="6" fill="var(--navy)" font-family="Inter, sans-serif">Juja</text>
                        <text x="120" y="239" font-size="6" fill="var(--navy)" font-family="Inter, sans-serif">Ngong</text>
                        <text x="162" y="213" font-size="6" fill="var(--navy)" font-family="Inter, sans-serif">Ruiru</text>
                        <!-- Pulse animation on Nairobi -->
                        <circle cx="130" cy="210" r="12" fill="none" stroke="var(--cyan)" stroke-width="1" opacity="0.5">
                            <animate attributeName="r" values="7;20;7" dur="3s" repeatCount="indefinite"/>
                            <animate attributeName="opacity" values="0.7;0;0.7" dur="3s" repeatCount="indefinite"/>
                        </circle>
                        <!-- Kenya label -->
                        <text x="150" y="80" font-size="12" fill="var(--blue)" font-weight="bold" font-family="Inter, sans-serif" opacity="0.5">KENYA</text>
                    </svg>
                    <p style="text-align:center; font-size:0.75rem; color:var(--mid-gray); margin-top:8px;">
                        <i class="fas fa-map-marker-alt" style="color:var(--cyan);"></i> Primary service area highlighted
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========= CTA BANNER ========= -->
<section style="background: linear-gradient(135deg, var(--blue), var(--cyan)); padding: 70px 0; text-align: center;">
    <div class="container">
        <h2 class="fade-in" style="font-family:var(--font-display); font-size:clamp(1.6rem,4vw,2.5rem); color:white; margin-bottom:14px;">
            Ready for Cleaner, Safer Water?
        </h2>
        <p class="fade-in" style="color:rgba(255,255,255,0.85); font-size:1.05rem; margin-bottom:34px; max-width:500px; margin-left:auto; margin-right:auto;">
            Don't wait until contaminated water affects your family's health. Book your tank cleaning today.
        </p>
        <div class="fade-in" style="display:flex; gap:16px; justify-content:center; flex-wrap:wrap;">
            <a href="booking.php" class="btn btn-outline btn-lg">
                <i class="fas fa-calendar-check"></i> Book Now
            </a>
            <a href="https://wa.me/254114676477" target="_blank" class="btn btn-lg" style="background:white; color:var(--blue);">
                <i class="fab fa-whatsapp" style="color:#25D366;"></i> WhatsApp Us
            </a>
        </div>
    </div>
</section>

<!-- ========= CONTACT PREVIEW ========= -->
<section class="section" id="contact-preview">
    <div class="container" style="text-align:center;">
        <span class="section-label">Get In Touch</span>
        <h2 class="section-title">Have Questions?</h2>
        <p class="section-subtitle" style="margin:0 auto 40px;">Reach us by phone, WhatsApp, or email. We respond fast.</p>
        <div style="display:flex; flex-wrap:wrap; justify-content:center; gap:20px; margin-bottom:36px;">
            <a href="tel:+254114676477" class="contact-item" style="max-width:220px; text-decoration:none;">
                <div class="contact-item-icon"><i class="fas fa-phone-alt"></i></div>
                <div>
                    <div class="contact-item-label">Call Us</div>
                    <div class="contact-item-value">0114 676 477</div>
                    <div class="contact-item-value">0104 852 047</div>
                </div>
            </a>
            <a href="https://wa.me/254114676477" target="_blank" class="contact-item" style="max-width:220px; text-decoration:none;">
                <div class="contact-item-icon" style="background:#25D366;"><i class="fab fa-whatsapp"></i></div>
                <div>
                    <div class="contact-item-label">WhatsApp</div>
                    <div class="contact-item-value">Click to Chat</div>
                </div>
            </a>
            <a href="mailto:info@longguardtankcare.co.ke" class="contact-item" style="max-width:260px; text-decoration:none;">
                <div class="contact-item-icon"><i class="fas fa-envelope"></i></div>
                <div>
                    <div class="contact-item-label">Email Us</div>
                    <div class="contact-item-value">info@longguardtankcare.co.ke</div>
                </div>
            </a>
        </div>
        <a href="contact.php" class="btn btn-ghost btn-lg">
            <i class="fas fa-paper-plane"></i> Send Us a Message
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
