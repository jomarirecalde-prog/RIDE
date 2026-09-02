<?php

$pageTitle = 'Sign In | RIDE';
$highlights = isset($highlights) && is_array($highlights) ? $highlights : [];
$rideVision = (string) ($rideVision ?? 'A leading university research and extension ecosystem that drives innovation, community development, and evidence-based solutions for Western Philippines and beyond.');
$rideMission = (string) ($rideMission ?? 'To monitor, support, and streamline research and extension workflows across Western Philippines University—empowering faculty and partners through transparent approval processes and accountable reporting.');

?>

<style>
  :root {
    --login-primary: #0f2b3d;
    --login-primary-dark: #0a1e2c;
    --login-accent: #2dd4bf;
    --login-accent-soft: #5eead4;
    --login-secondary: #163d52;
    --login-text: #1e293b;
    --login-muted: #64748b;
    --login-border: #e2e8f0;
    --login-danger: #dc2626;
  }

  body.auth-standalone {
    margin: 0;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    min-height: 100vh;
    color: var(--login-text);
    line-height: 1.6;
    font-size: 14px;
    color-scheme: light;
    background: linear-gradient(160deg, var(--login-primary) 0%, var(--login-primary-dark) 45%, #134e4a 100%);
    position: relative;
    overflow-x: hidden;
  }

  body.auth-standalone * {
    box-sizing: border-box;
  }

  .login-bg {
    position: fixed;
    inset: 0;
    z-index: 0;
    pointer-events: none;
    overflow: hidden;
  }

  .login-bg__mesh {
    position: absolute;
    inset: -20%;
    background:
      radial-gradient(ellipse 50% 45% at 15% 25%, rgba(45, 212, 191, 0.28), transparent 55%),
      radial-gradient(ellipse 45% 40% at 85% 20%, rgba(56, 189, 248, 0.22), transparent 50%),
      radial-gradient(ellipse 55% 50% at 70% 80%, rgba(20, 184, 166, 0.2), transparent 55%),
      radial-gradient(ellipse 40% 35% at 20% 85%, rgba(14, 116, 144, 0.3), transparent 50%);
    animation: login-mesh-drift 22s ease-in-out infinite alternate;
  }

  .login-bg__orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(48px);
    opacity: 0.55;
    will-change: transform;
  }

  .login-bg__orb--1 {
    width: min(42vw, 420px);
    height: min(42vw, 420px);
    top: -8%;
    left: -6%;
    background: radial-gradient(circle, rgba(45, 212, 191, 0.55) 0%, transparent 70%);
    animation: login-orb-a 18s ease-in-out infinite;
  }

  .login-bg__orb--2 {
    width: min(36vw, 360px);
    height: min(36vw, 360px);
    top: 35%;
    right: -8%;
    background: radial-gradient(circle, rgba(56, 189, 248, 0.45) 0%, transparent 70%);
    animation: login-orb-b 24s ease-in-out infinite;
  }

  .login-bg__orb--3 {
    width: min(48vw, 480px);
    height: min(48vw, 480px);
    bottom: -12%;
    left: 28%;
    background: radial-gradient(circle, rgba(15, 118, 110, 0.5) 0%, transparent 70%);
    animation: login-orb-c 20s ease-in-out infinite;
  }

  .login-bg__shimmer {
    position: absolute;
    inset: 0;
    background: linear-gradient(
      115deg,
      transparent 0%,
      transparent 40%,
      rgba(255, 255, 255, 0.04) 50%,
      transparent 60%,
      transparent 100%
    );
    background-size: 220% 100%;
    animation: login-shimmer 14s ease-in-out infinite;
  }

  @keyframes login-mesh-drift {
    0% { transform: translate(0, 0) scale(1); }
    100% { transform: translate(-3%, 2%) scale(1.06); }
  }

  @keyframes login-orb-a {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(12%, 18%) scale(1.12); }
  }

  @keyframes login-orb-b {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(-16%, -10%) scale(1.08); }
  }

  @keyframes login-orb-c {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(-8%, -14%) scale(1.1); }
  }

  @keyframes login-shimmer {
    0% { background-position: 120% 0; }
    100% { background-position: -120% 0; }
  }

  @media (prefers-reduced-motion: reduce) {
    .login-bg__mesh,
    .login-bg__orb,
    .login-bg__shimmer {
      animation: none;
    }
  }

  .login-landing {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: 1fr 380px;
    min-height: 100vh;
    gap: 0;
  }

  .login-hero {
    padding: 2rem 2.5rem 2.5rem;
    overflow-y: auto;
    color: #fff;
  }

  .login-hero-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
  }

  .login-hero-header .logo-img {
    width: 88px;
    height: 88px;
    margin: 0;
    flex-shrink: 0;
    border-radius: 50%;
    background: #fff;
    padding: 2px;
    object-fit: contain;
  }

  .login-hero-titles h1 {
    font-size: clamp(1.35rem, 2.5vw, 1.75rem);
    font-weight: 700;
    line-height: 1.25;
    margin: 0 0 0.25rem;
  }

  .login-hero-titles p {
    margin: 0;
    opacity: 0.8;
    font-size: 0.9rem;
  }

  .vm-section {
    margin-bottom: 2rem;
  }

  .vm-divider {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.25rem;
    opacity: 0.85;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
  }

  .vm-divider::before,
  .vm-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: rgba(255, 255, 255, 0.25);
  }

  .vm-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.25rem;
  }

  .vm-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 14px;
    padding: 1.5rem;
    transition: background 0.2s;
  }

  .vm-card:hover {
    background: rgba(255, 255, 255, 0.14);
  }

  .vm-card-label {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    margin-bottom: 0.85rem;
  }

  .vm-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: var(--login-accent);
    color: var(--login-primary-dark);
    border-radius: 8px;
    font-size: 0.85rem;
  }

  .vm-card h2 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.02em;
  }

  .vm-text {
    margin: 0;
    font-size: 0.9rem;
    line-height: 1.7;
    opacity: 0.92;
  }

  .vm-vision {
    border-top: 3px solid var(--login-accent);
  }

  .vm-mission {
    border-top: 3px solid #38bdf8;
  }

  .carousel-section {
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 16px;
    padding: 1.5rem;
  }

  .carousel-section-header {
    margin-bottom: 1.25rem;
  }

  .carousel-section-header h2 {
    margin: 0 0 0.2rem;
    font-size: 1.1rem;
    font-weight: 700;
  }

  .carousel-section-header p {
    margin: 0;
    font-size: 0.82rem;
    opacity: 0.75;
  }

  .carousel-wrapper {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
  }

  .carousel-track-outer {
    overflow: hidden;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.3);
  }

  .carousel-track {
    display: flex;
    will-change: transform;
  }

  .carousel-slide {
    flex: 0 0 100%;
    margin: 0;
    position: relative;
    aspect-ratio: 16 / 7;
    min-height: 180px;
  }

  .carousel-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .carousel-slide-info {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2.5rem 1.25rem 1rem;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.75));
    color: #fff;
  }

  .carousel-slide-title {
    margin: 0 0 0.15rem;
    font-size: 1rem;
    font-weight: 600;
  }

  .carousel-slide-caption {
    margin: 0;
    font-size: 0.82rem;
    opacity: 0.9;
  }

  .carousel-empty {
    flex: 0 0 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding: 3rem 1.5rem;
    color: rgba(255, 255, 255, 0.6);
    text-align: center;
    min-height: 180px;
  }

  .carousel-empty-icon {
    font-size: 1.75rem;
    opacity: 0.5;
  }

  .carousel-empty p {
    margin: 0;
  }

  .carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    color: var(--login-primary);
    font-size: 1.4rem;
    line-height: 1;
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    transition: background 0.2s, transform 0.2s;
    z-index: 2;
  }

  .carousel-btn:hover {
    background: #fff;
    transform: translateY(-50%) scale(1.05);
  }

  .carousel-prev { left: 0.75rem; }
  .carousel-next { right: 0.75rem; }

  .carousel-dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    padding: 0.85rem 0 0.25rem;
  }

  .carousel-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    border: none;
    background: rgba(255, 255, 255, 0.35);
    cursor: pointer;
    padding: 0;
    transition: background 0.2s, transform 0.2s;
  }

  .carousel-dot.active {
    background: #fff;
    transform: scale(1.25);
  }

  .login-panel {
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.5rem;
    box-shadow: -8px 0 30px rgba(0, 0, 0, 0.15);
  }

  .login-card {
    width: 100%;
    max-width: 340px;
  }

  .login-header {
    text-align: center;
    margin-bottom: 1.75rem;
  }

  .login-header h2 {
    margin: 0 0 0.25rem;
    font-size: 1.35rem;
    color: var(--login-primary);
    font-weight: 700;
  }

  .login-header p {
    margin: 0;
    color: var(--login-muted);
    font-size: 0.85rem;
  }

  .login-form .form-group {
    margin-bottom: 1rem;
  }

  .login-form label {
    display: block;
    margin-bottom: 0.35rem;
    font-weight: 500;
    font-size: 0.85rem;
    color: var(--login-text);
  }

  .login-form input {
    width: 100%;
    padding: 0.65rem 0.85rem;
    border: 1px solid var(--login-border);
    border-radius: 8px;
    font-size: 0.875rem;
    font-family: inherit;
    transition: border-color 0.2s, box-shadow 0.2s;
    background: #fff;
    color: var(--login-text);
  }

  .login-form input:focus {
    outline: none;
    border-color: var(--login-accent);
    box-shadow: 0 0 0 3px rgba(45, 212, 191, 0.25);
  }

  .login-form input.error {
    border-color: var(--login-danger);
    background-color: #fff5f5;
  }

  .error-message {
    font-size: 0.75rem;
    color: var(--login-danger);
    margin-top: 0.35rem;
    font-weight: 500;
    display: none;
    align-items: center;
    gap: 4px;
  }

  .form-error {
    background: #fee2e2;
    color: #991b1b;
    padding: 0.65rem 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
    font-size: 0.85rem;
  }

  .btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.65rem 1.1rem;
    border-radius: 8px;
    font-size: 0.875rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    font-family: inherit;
  }

  .btn-primary {
    background: var(--login-primary);
    color: #fff;
  }

  .btn-primary:hover:not(:disabled) {
    background: var(--login-primary-dark);
  }

  .btn-primary:disabled {
    opacity: 0.85;
    cursor: wait;
  }

  .btn-block {
    width: 100%;
    justify-content: center;
  }

  .login-footer {
    text-align: center;
    margin-top: 1.5rem;
    color: var(--login-muted);
  }

  .login-footer small {
    display: block;
    margin-bottom: 0.85rem;
  }

  .register-link {
    color: var(--login-secondary);
    font-weight: 600;
    text-decoration: none;
    font-size: 0.85rem;
  }

  .register-link:hover {
    color: var(--login-primary);
    text-decoration: underline;
  }

  .spinner {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .toast-message {
    position: fixed;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%) scale(0.9);
    background: #1f2937ee;
    backdrop-filter: blur(12px);
    color: white;
    padding: 0.7rem 1.5rem;
    border-radius: 40px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    opacity: 0;
    transition: opacity 0.2s, transform 0.2s;
    pointer-events: none;
    z-index: 1000;
  }

  .toast-message.show {
    opacity: 1;
    transform: translateX(-50%) scale(1);
  }

  button:focus-visible,
  input:focus-visible,
  .register-link:focus-visible,
  .carousel-btn:focus-visible,
  .carousel-dot:focus-visible {
    outline: 2px solid var(--login-accent);
    outline-offset: 2px;
  }

  @media (max-width: 1024px) {
    .login-landing {
      grid-template-columns: 1fr;
    }

    .login-panel {
      box-shadow: none;
      border-top: 1px solid rgba(255, 255, 255, 0.15);
    }

    .login-hero {
      padding: 1.5rem 1.25rem;
    }
  }

  @media (max-width: 640px) {
    .vm-grid {
      grid-template-columns: 1fr;
    }

    .login-hero-header {
      justify-content: center;
      text-align: center;
    }

    .carousel-slide {
      aspect-ratio: 4 / 3;
    }
  }
</style>

<div class="login-bg" aria-hidden="true">
  <div class="login-bg__mesh"></div>
  <div class="login-bg__orb login-bg__orb--1"></div>
  <div class="login-bg__orb login-bg__orb--2"></div>
  <div class="login-bg__orb login-bg__orb--3"></div>
  <div class="login-bg__shimmer"></div>
</div>

<div class="login-landing">
  <section class="login-hero" aria-label="RIDE vision, mission, and highlights">
    <header class="login-hero-header">
      <img src="<?= base_url('assets/images/ride-logo.png') ?>" alt="RIDE — Research, Innovation, Development and Extension" class="logo-img" width="88" height="88">
      <div class="login-hero-titles">
        <h1>Research and Extension Portal</h1>
        <p>Western Philippines University &bull; RIDE Office</p>
      </div>
    </header>

    <div class="vm-section">
      <div class="vm-divider">
        <span>Our Commitment</span>
      </div>
      <div class="vm-grid">
        <article class="vm-card vm-vision">
          <div class="vm-card-label">
            <span class="vm-icon" aria-hidden="true">&#9670;</span>
            <h2>Vision</h2>
          </div>
          <p class="vm-text"><?= htmlspecialchars($rideVision) ?></p>
        </article>
        <article class="vm-card vm-mission">
          <div class="vm-card-label">
            <span class="vm-icon" aria-hidden="true">&#9733;</span>
            <h2>Mission</h2>
          </div>
          <p class="vm-text"><?= htmlspecialchars($rideMission) ?></p>
        </article>
      </div>
    </div>

    <div class="carousel-section">
      <div class="carousel-section-header">
        <h2>RIDE Highlights</h2>
        <p>Programs, activities, and announcements from the WPU RIDE Office</p>
      </div>
      <div class="carousel-wrapper" id="carouselWrapper">
        <div class="carousel-track-outer">
          <div class="carousel-track" id="carouselTrack" aria-live="polite">
            <?php if (empty($highlights)): ?>
              <div class="carousel-empty" id="carouselEmpty">
                <span class="carousel-empty-icon" aria-hidden="true"><i class="fas fa-camera"></i></span>
                <p>No carousel images yet. Check back soon for RIDE updates.</p>
              </div>
            <?php else: ?>
              <?php foreach ($highlights as $slide): ?>
                <figure class="carousel-slide">
                  <img
                    src="<?= htmlspecialchars((string) ($slide['image_url'] ?? '')) ?>"
                    alt="<?= htmlspecialchars((string) ($slide['title'] ?: 'RIDE activity')) ?>"
                    loading="lazy"
                  >
                  <?php if (!empty($slide['title']) || !empty($slide['caption'])): ?>
                    <figcaption class="carousel-slide-info">
                      <?php if (!empty($slide['title'])): ?>
                        <h3 class="carousel-slide-title"><?= htmlspecialchars((string) $slide['title']) ?></h3>
                      <?php endif; ?>
                      <?php if (!empty($slide['caption'])): ?>
                        <p class="carousel-slide-caption"><?= htmlspecialchars((string) $slide['caption']) ?></p>
                      <?php endif; ?>
                    </figcaption>
                  <?php endif; ?>
                </figure>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
        </div>
        <button type="button" class="carousel-btn carousel-prev" id="carouselPrev" aria-label="Previous slide" hidden>&lsaquo;</button>
        <button type="button" class="carousel-btn carousel-next" id="carouselNext" aria-label="Next slide" hidden>&rsaquo;</button>
        <div class="carousel-dots" id="carouselDots" hidden></div>
      </div>
    </div>
  </section>

  <aside class="login-panel" aria-label="Sign in">
    <div class="login-card">
      <div class="login-header">
        <h2>Sign In</h2>
        <p>Access the R&amp;E Monitoring System</p>
      </div>

      <form id="signinForm" class="login-form" method="post" action="<?= base_url('login') ?>" novalidate>
        <?= csrf_field() ?>

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" id="email" name="email" required autocomplete="email" placeholder="faculty@university.edu" value="<?= old('email') ?>">
          <div class="error-message" id="emailError"></div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="Enter your password">
          <div class="error-message" id="passwordError"></div>
        </div>

        <div class="form-error" id="loginError" hidden></div>

        <button type="submit" class="btn btn-primary btn-block" id="signInButton">
          <span>Sign In</span>
        </button>
      </form>

      <div class="login-footer">
        <small>Authorized personnel only &bull; WPU RIDE Office</small>
        <a href="<?= base_url('register') ?>" class="register-link">Register as Faculty</a>
      </div>
    </div>
  </aside>
</div>

<div id="toastMsg" class="toast-message">
  <i class="fas fa-circle-info"></i> <span id="toastText">Message</span>
</div>

<script>
  (function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailErrorDiv = document.getElementById('emailError');
    const passwordErrorDiv = document.getElementById('passwordError');
    const signinForm = document.getElementById('signinForm');
    const loginError = document.getElementById('loginError');
    const toast = document.getElementById('toastMsg');
    const toastTextSpan = document.getElementById('toastText');
    const signBtn = document.getElementById('signInButton');
    const slideCount = <?= (int) count($highlights) ?>;

    function showFieldError(fieldId, message) {
      const input = fieldId === 'email' ? emailInput : passwordInput;
      const err = fieldId === 'email' ? emailErrorDiv : passwordErrorDiv;
      err.textContent = message || '';
      err.style.display = message ? 'flex' : 'none';
      input.classList.toggle('error', !!message);
    }

    function clearAllErrors() {
      showFieldError('email', '');
      showFieldError('password', '');
      loginError.hidden = true;
    }

    function showToast(message, isSuccess) {
      toastTextSpan.textContent = message;
      toast.classList.add('show');
      const iconEl = toast.querySelector('i');
      iconEl.className = isSuccess ? 'fas fa-check-circle' : 'fas fa-exclamation-triangle';
      setTimeout(function() {
        toast.classList.remove('show');
      }, 2800);
    }

    function isValidEmail(email) {
      return /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/.test(email);
    }

    function validateForm() {
      clearAllErrors();
      let isValid = true;
      const emailVal = emailInput.value.trim();
      const passwordVal = passwordInput.value;

      if (!emailVal) {
        showFieldError('email', 'Email address is required');
        isValid = false;
      } else if (!isValidEmail(emailVal)) {
        showFieldError('email', 'Please enter a valid email address');
        isValid = false;
      }

      if (!passwordVal) {
        showFieldError('password', 'Password is required');
        isValid = false;
      }

      return isValid;
    }

    signinForm.addEventListener('submit', function(event) {
      if (!validateForm()) {
        event.preventDefault();
        loginError.textContent = 'Please fix the errors above before signing in.';
        loginError.hidden = false;
        if (emailInput.classList.contains('error')) {
          emailInput.focus();
        } else if (passwordInput.classList.contains('error')) {
          passwordInput.focus();
        }
        return;
      }

      signBtn.disabled = true;
      signBtn.innerHTML = '<span class="spinner"></span> Signing in...';
    });

    emailInput.addEventListener('input', function() {
      if (!emailInput.classList.contains('error')) return;
      const currentEmail = emailInput.value.trim();
      if (currentEmail === '') {
        showFieldError('email', 'Email address is required');
      } else if (!isValidEmail(currentEmail)) {
        showFieldError('email', 'Please enter a valid email address');
      } else {
        showFieldError('email', '');
      }
    });

    passwordInput.addEventListener('input', function() {
      if (!passwordInput.classList.contains('error')) return;
      showFieldError('password', passwordInput.value === '' ? 'Password is required' : '');
    });

    // Announcement carousel (same interaction pattern as WPU-GAD)
    let currentIndex = 0;
    let autoTimer = null;
    let direction = 1;
    const AUTO_INTERVAL = 5000;
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('carouselPrev');
    const nextBtn = document.getElementById('carouselNext');
    const dots = document.getElementById('carouselDots');
    const wrapper = document.getElementById('carouselWrapper');

    function updateCarouselPosition(animate) {
      track.style.transition = animate ? 'transform 0.6s cubic-bezier(.4,0,.2,1)' : 'none';
      track.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
      dots.querySelectorAll('.carousel-dot').forEach(function(dot, i) {
        dot.classList.toggle('active', i === currentIndex);
      });
    }

    function goToSlide(index) {
      if (slideCount < 1) return;
      currentIndex = ((index % slideCount) + slideCount) % slideCount;
      updateCarouselPosition(true);
      resetAutoPlay();
    }

    function startAutoPlay() {
      stopAutoPlay();
      autoTimer = setInterval(function() {
        if (currentIndex >= slideCount - 1) direction = -1;
        else if (currentIndex <= 0) direction = 1;
        goToSlide(currentIndex + direction);
      }, AUTO_INTERVAL);
    }

    function stopAutoPlay() {
      if (autoTimer) {
        clearInterval(autoTimer);
        autoTimer = null;
      }
    }

    function resetAutoPlay() {
      if (slideCount > 1) startAutoPlay();
    }

    if (slideCount > 1) {
      prevBtn.hidden = false;
      nextBtn.hidden = false;
      dots.hidden = false;
      dots.innerHTML = Array.from({ length: slideCount }, function(_, i) {
        return '<button type="button" class="carousel-dot' + (i === 0 ? ' active' : '') + '" data-index="' + i + '" aria-label="Go to slide ' + (i + 1) + '"></button>';
      }).join('');
      dots.querySelectorAll('.carousel-dot').forEach(function(dot) {
        dot.onclick = function() {
          goToSlide(parseInt(dot.dataset.index, 10));
        };
      });
      prevBtn.onclick = function() { goToSlide(currentIndex - 1); };
      nextBtn.onclick = function() { goToSlide(currentIndex + 1); };
      updateCarouselPosition(false);
      startAutoPlay();
      if (wrapper) {
        wrapper.addEventListener('mouseenter', stopAutoPlay);
        wrapper.addEventListener('mouseleave', resetAutoPlay);
      }
    }

    <?php if (!empty($authFlashError)): ?>
    showToast(<?= json_encode($authFlashError, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>, false);
    <?php elseif (!empty($authFlashSuccess)): ?>
    showToast(<?= json_encode($authFlashSuccess, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>, true);
    <?php endif; ?>
  })();
</script>
