<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>JustiNet – Where Justice Meets Technology</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    :root {
      --primary: #0a0e27;
      --accent: #00d4ff;
      --gold: #ffd700;
      --white: #ffffff;
      --glass: rgba(255, 255, 255, 0.1);
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
      height: 100%;
      font-family: 'Segoe UI', sans-serif;
      background: radial-gradient(circle at 20% 80%, #120458 0%, #000000 50%, #0f0f23 100%);
      overflow: hidden;
      position: relative;
    }

    /* Animated Background Particles */
    .particles {
      position: fixed;
      width: 100%;
      height: 100%;
      z-index: -2;
    }
    .particle {
      position: absolute;
      width: 2px;
      height: 2px;
      background: var(--accent);
      border-radius: 50%;
      animation: float 6s ease-in-out infinite;
      opacity: 0.7;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
      50% { transform: translateY(-20px) rotate(180deg); opacity: 1; }
    }

    /* Video Background */
    .video-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      z-index: -1;
      overflow: hidden;
    }
    #bg-video {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: brightness(0.3) contrast(1.2) blur(1px);
      animation: videoShift 20s ease-in-out infinite;
    }
    @keyframes videoShift {
      0%, 100% { transform: scale(1.05); }
      50% { transform: scale(1.1); }
    }

    /* Splash Screen */
    #splash {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: linear-gradient(135deg, var(--primary) 0%, #1a1a2e 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      flex-direction: column;
      animation: splashOut 3s ease-in-out forwards;
    }
    #splash h1 {
      font-size: 4em;
      background: linear-gradient(45deg, var(--accent), var(--gold));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      animation: logoGlow 2s ease-in-out infinite alternate;
      text-shadow: 0 0 30px rgba(0, 212, 255, 0.5);
    }
    @keyframes logoGlow {
      0% { filter: brightness(1) drop-shadow(0 0 10px rgba(0, 212, 255, 0.3)); }
      100% { filter: brightness(1.3) drop-shadow(0 0 20px rgba(0, 212, 255, 0.8)); }
    }
    @keyframes splashOut {
      0% { opacity: 1; transform: scale(1); }
      90% { opacity: 1; transform: scale(1); }
      100% { opacity: 0; transform: scale(1.1); visibility: hidden; }
    }

    /* Header */
    header {
      text-align: center;
      color: var(--white);
      margin-top: 60px;
      animation: slideDown 1.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
      opacity: 0;
    }
    header h1 {
      font-size: 3.5em;
      font-weight: 700;
      background: linear-gradient(135deg, var(--white) 0%, var(--accent) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 0 30px rgba(0, 212, 255, 0.3);
      animation: headerPulse 4s ease-in-out infinite;
    }
    header p {
      font-size: 1.3em;
      color: #b8c6db;
      margin-top: 10px;
      font-weight: 300;
      letter-spacing: 1px;
    }
    @keyframes slideDown {
      0% { opacity: 0; transform: translateY(-50px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    @keyframes headerPulse {
      0%, 100% { filter: brightness(1); }
      50% { filter: brightness(1.2); }
    }

    /* Main Content */
    main {
      display: flex;
      align-items: center;
      justify-content: center;
      height: calc(100vh - 220px);
      padding: 20px;
    }

    .card {
      background: linear-gradient(135deg, var(--glass) 0%, rgba(255, 255, 255, 0.05) 100%);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      padding: 50px;
      max-width: 550px;
      width: 100%;
      color: #fff;
      backdrop-filter: blur(20px);
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 80px rgba(0, 212, 255, 0.1);
      text-align: center;
      animation: cardFloat 2s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
      opacity: 0;
      position: relative;
      overflow: hidden;
    }
    .card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
      animation: shimmer 3s ease-in-out infinite;
    }
    @keyframes shimmer {
      0% { left: -100%; }
      100% { left: 100%; }
    }
    @keyframes cardFloat {
      0% { opacity: 0; transform: translateY(60px) scale(0.9); }
      100% { opacity: 1; transform: translateY(0) scale(1); }
    }

    .card h2 {
      font-size: 1.8em;
      margin-bottom: 30px;
      font-weight: 600;
      color: var(--white);
    }

    .btn {
      display: inline-block;
      padding: 15px 35px;
      margin: 12px 10px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--gold) 100%);
      color: var(--primary);
      border: none;
      border-radius: 12px;
      font-weight: 700;
      font-size: 1.1em;
      cursor: pointer;
      text-decoration: none;
      opacity: 0;
      transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
      animation: btnSlideIn 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
      box-shadow: 0 8px 25px rgba(0, 212, 255, 0.3);
      position: relative;
      overflow: hidden;
    }
    .btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
      transition: left 0.5s;
    }
    .btn:hover::before { left: 100%; }
    .btn:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 15px 35px rgba(0, 212, 255, 0.4);
    }
    .btn:nth-child(1) { animation-delay: 1.5s; }
    .btn:nth-child(2) { animation-delay: 1.8s; }
    @keyframes btnSlideIn {
      0% { opacity: 0; transform: translateX(-30px); }
      100% { opacity: 1; transform: translateX(0); }
    }

    /* Footer */
    footer {
      text-align: center;
      color: #8892b0;
      font-size: 0.95em;
      padding: 20px;
      background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
      backdrop-filter: blur(15px);
      position: fixed;
      bottom: 0;
      width: 100%;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      animation: fadeInUp 2s ease forwards;
      opacity: 0;
    }
    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }

    /* Responsive */
    @media (max-width: 600px) {
      header h1 { font-size: 2.5em; }
      .card { padding: 35px 25px; margin: 0 15px; }
      .btn { width: 100%; margin: 10px 0; }
    }
  </style>
</head>
<body>

  <!-- Particles Background -->
  <div class="particles"></div>

  <!-- Splash Screen -->
  <div id="splash">
    <h1>⚖️ JustiNet</h1>
  </div>

  <!-- Video Background -->
  <div class="video-bg">
    <video autoplay muted loop playsinline preload="auto" id="bg-video">
      <source src="https://cdn.coverr.co/videos/coverr-law-firm-office-8025.mp4" type="video/mp4">
      <source src="https://cdn.coverr.co/videos/coverr-legal-discussion-1280x720.mp4" type="video/mp4">
    </video>
  </div>

  <!-- Header -->
  <header>
    <h1>⚖️ JustiNet</h1>
    <p>Where Justice Meets Technology</p>
  </header>

  <!-- Main Section -->
  <main>
    <div class="card">
      <?php if ($logged_in): ?>
        <h2>Welcome back, <?= htmlspecialchars($_SESSION['name']) ?>!</h2>
        <a class="btn" href="dashboard/<?= $role ?>_dashboard.php">Go to Dashboard</a>
        <a class="btn" href="auth/logout.php">Logout</a>
      <?php else: ?>
        <h2>Access Legal Help in Seconds</h2>
        <a class="btn" href="auth/login.php">Login</a>
        <a class="btn" href="auth/register.php">Register</a>
      <?php endif; ?>
    </div>
  </main>

  <!-- Footer -->
  <footer>
    &copy; <?= date('Y') ?> JustiNet. Empowering Legal Access Through Technology.
  </footer>

  <script>
    // Create animated particles
    function createParticles() {
      const particles = document.querySelector('.particles');
      for (let i = 0; i < 50; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        particle.style.left = Math.random() * 100 + '%';
        particle.style.top = Math.random() * 100 + '%';
        particle.style.animationDelay = Math.random() * 6 + 's';
        particle.style.animationDuration = (Math.random() * 3 + 4) + 's';
        particles.appendChild(particle);
      }
    }

    window.addEventListener('load', () => {
      createParticles();
      setTimeout(() => {
        document.getElementById('splash').style.display = 'none';
      }, 3000);
    });
  </script>
  <!-- Botpress Webchat v3.0 Scripts -->
<script src="https://cdn.botpress.cloud/webchat/v3.0/inject.js" defer></script>
<script src="https://files.bpcontent.cloud/2025/06/20/13/20250620135221-9MSSHOB1.js" defer></script>

<!-- Chat Launch Button with Emoji Animation -->
<button onclick="startChat()" style="
  position: fixed;
  bottom: 30px;
  right: 30px;
  background-color: #1b1f3b;
  color: #ffffff;
  border: none;
  border-radius: 50px;
  padding: 14px 24px;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  transition: background 0.3s ease;
  z-index: 9999;
  display: flex;
  align-items: center;
  gap: 10px;
">
  <span style="
    display: inline-block;
    animation: bounce 1.2s infinite;
    font-size: 20px;
  ">💬</span>

</button>

<!-- Bounce Animation -->
<style>
@keyframes bounce {
  0%, 100% { transform: translateY(0); }
  50%      { transform: translateY(-6px); }
}
</style>

<!-- Chat Init Script -->
<script>
  function startChat() {
    window.botpressWebChat.sendEvent({ type: "show" });
  }
  window.addEventListener('load', function () {
    window.botpressWebChat.init({
      botId: '0c8b7bcd-1b41-4b93-bf8a-5f287fa2c2d7',
      hostUrl: 'https://cdn.botpress.cloud/webchat/v3.0',
      messagingUrl: 'https://messaging.botpress.cloud',
      clientId: '0c8b7bcd-1b41-4b93-bf8a-5f287fa2c2d7',
      webhookId: '9MSSHOB1',
      lazySocket: true,
      showPoweredBy: false,
      theme: 'prism',
      themeColor: '#f5c518',
      botName: 'Legal Assistant'
    });
  });
</script>

</body>
</html>