
<?php

include("./includes/header.php");
include("./includes/function.php");
include("./includes/db_conn.php");

?>
<head>
     <title>Prime Ledger</title>
</head>
   
  <style>
    /* Root color tokens */
    :root{
      --bg-top: #d9fbddff;    /* soft top gradient */
      --bg-bottom: #cfeafe; /* soft bottom gradient */
      --primary: #009d3cff;   /* teal-ish accent used in image */
      --cta: #000b87ff;       /* CTA color */
      --muted: #77838f;
      --panel-white: #ffffff;
      --rounded: 28px;
      --max-width: 1200px;
    }

    /* Page background gradient */
    body{
      font-family: "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background: linear-gradient(180deg,var(--bg-top), var(--bg-bottom));
      min-height: 100vh;
      margin: 0;
      color: #0f1720;
    }

    /* Center the main rounded panel */
    .page-wrap{
      display: flex;
      justify-content: center;
      padding: 48px 20px;
    }

    .panel {
      width: 100%;
      max-width: var(--max-width);
      background: var(--panel-white);
      border-radius: var(--rounded);
      box-shadow: 0 18px 36px rgba(16,64,104,0.12);
      padding: 36px;
      overflow: visible;
      position: relative;
    }

    /* subtle top-left logo position */
    .site-nav{
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap: 16px;
      margin-bottom: 18px;
    }
    .site-brand{
      display:flex;
      align-items:center;
      gap:12px;
    }
    .brand-mark{
      width:48px;
      height:48px;
      border-radius:10px;
      background: linear-gradient(135deg,var(--primary), #2f5800ff);
      display:flex;
      align-items:center;
      justify-content:center;
      color:white;
      font-weight:700;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .nav-links{
      display:flex;
      gap:22px;
      align-items:center;
      justify-content:center;
      flex:1;
    }
    .nav-links a{
      color: #7a8a92;
      text-decoration:none;
      font-weight:600;
      font-size:0.95rem;
    }

    /* Buttons right */
    .auth-actions{
      display:flex;
      gap:10px;
      align-items:center;
    }
    .btn-signup{
      border-radius: 999px;
      padding: .45rem .9rem;
      border: 2px solid var(--primary);
      background: transparent;
      color: var(--primary);
      font-weight:600;
      text-decoration:none;
    }
    .btn-signin{
      border-radius: 999px;
      padding: .55rem 1rem;
      background: var(--primary);
      color:white;
      font-weight:700;
      text-decoration:none;
      box-shadow: 0 8px 18px rgba(0,168,216,0.14);
    }

    /* Hero grid */
    .hero-row{
      display:grid;
      grid-template-columns: 1fr 520px;
      gap: 28px;
      align-items:center;
      padding: 10px 4px 18px;
    }

    /* Left column */
    .hero-title{
      font-size: clamp(1.9rem, 3.6vw, 3.25rem);
      line-height:1.02;
      font-weight:800;
      color: #007a9e; /* bold teal similar to image */
      margin: 0 0 14px 0;
    }
    .hero-sub{
      font-size: 1.05rem;
      color: #4b5563;
      margin-bottom: 22px;
      max-width: 62ch;
    }

    .cta-row{
      display:flex;
      gap:12px;
      align-items:center;
      margin-top: 10px;
    }

    .btn-primary-cta{
      background: linear-gradient(90deg,var(--cta), #00a3c6);
      color:white;
      border: none;
      padding: .7rem 1.4rem;
      border-radius: 999px;
      font-weight:700;
      text-decoration:none;
      display:inline-block;
    }
    .btn-secondary-ghost{
      background:transparent;
      border:2px solid #cfeff7;
      color:var(--muted);
      padding:.6rem 1.2rem;
      border-radius: 999px;
      text-decoration:none;
      font-weight:600;
    }

    /* Right illustration container (to match image proportions) */
    .hero-illustration{
      display:flex;
      align-items:center;
      justify-content:center;
      position:relative;
      padding: 10px;
    }
    .hero-illustration img{
      width:100%;
      height:auto;
      max-width:520px;
      border-radius: 12px;
      display:block;
    }

    /* Small decorative bullets under hero (like the small dots) */
    .hero-indicators{
      position:absolute;
      left:50%;
      transform:translateX(-50%);
      bottom:-26px;
      display:flex;
      gap:12px;
      align-items:center;
    }
    .dot{
      width:14px;
      height:14px;
      border-radius:50%;
      background: linear-gradient(90deg,#cfeff7, #e6fbff);
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
      display:inline-block;
    }
    .dot.active{
      width:36px;
      height:14px;
      border-radius:20px;
      background: linear-gradient(90deg,var(--primary), #00c0e6);
    }

    /* Features grid below */
    .features{
      margin-top: 46px;
    }
    .feature-card{
      display:flex;
      gap:14px;
      align-items:flex-start;
      background: #fafbfc;
      border-radius: 12px;
      padding: 18px;
    }
    .feature-icon{
      width:56px;
      height:56px;
      border-radius:10px;
      background: linear-gradient(135deg,#e6f7fb,#eaf8ff);
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:1.25rem;
      color: var(--primary);
      font-weight:700;
    }
    .feature-title{
      font-weight:700;
      margin:0 0 6px 0;
    }
    .feature-desc{
      margin:0;
      color: #58656f;
      font-size:0.95rem;
    }

    /* Footer */
    footer{
      margin-top: 40px;
      text-align:center;
      color: #6b7780;
      font-size: 0.9rem;
    }

    /* Responsive tweaks */
    @media (max-width: 1000px){
      .hero-row{
        grid-template-columns: 1fr 420px;
      }
      .panel{
        padding: 28px;
      }
    }
    @media (max-width: 820px){
      .hero-row{
        grid-template-columns: 1fr;
        gap: 18px;
      }
      .hero-indicators{ bottom:-18px; }
      .panel{ padding:20px; border-radius:20px; }
    }
    @media (max-width: 420px){
      .nav-links{ display:none; } /* hide center nav on small screens */
      .brand-mark{ width:40px; height:40px; font-size:0.9rem; }
      .hero-title{ font-size:1.6rem; }
      .hero-sub{ font-size:0.98rem; }
    }
  </style>







  <!-- Outer centered panel that imitates the rounded white card in your image -->
  <div class="page-wrap">
    <div class="panel" role="main" aria-label="Landing panel">

      <!-- Navigation -->
      <header class="site-nav" aria-hidden="false">
        <div class="site-brand">
          <div class="brand-mark" aria-hidden="true">HR</div>
          <div>
              <div style="font-size:.82rem; color:var(--muted); margin-top:2px;">Hello</div>
            <div style="font-weight:800; font-size:1.05rem;">Hansh</div>
          </div>
        </div>


        <div class="auth-actions">
          <a class="btn-signup" href="register_user.php">Sign up</a>
          <a class="btn-signin" href="login.php">Sign in</a>
        </div>
      </header>

      <!-- Hero -->
      <section class="hero-row" aria-label="Hero section">
        <!-- Left column: text -->
        <div>

        <div style="padding: 0%; margin-top: 0;">
<p class="hero-title" style="font-size: 100px; font-weight: 900; color:black;margin: 0;">  Prime
              <p style="font-size: 60px; font-weight: 900; color:green;"> Legder</p></p>
        </div>
          <p class="hero-sub">
            Powerful, private and simple tools to manage your money — track transactions,
            store receipts, and view clear reports. Start with a free account and stay in control.
          </p>

          <div class="cta-row">
            <a class="btn-primary-cta" href="register.php" role="button">LEARN MORE →</a>
            <a class="btn-secondary-ghost" href="login.php">Sign in</a>
          </div>
        </div>

        <!-- Right column: illustration -->
        <div class="hero-illustration" aria-hidden="true">
          <!-- Replace the image path below with your actual image path -->
          <img src="./images/landingImage.png " alt="Finance illustration" />
        </div>

        <!-- Decorative indicators centered under hero -->
        <div class="hero-indicators" aria-hidden="true">
          <span class="dot"></span>
          <span class="dot active"></span>
          <span class="dot"></span>
        </div>
      </section>

      <!-- Footer -->
      <footer class="mt-4">
        <small>© Tracker</small>
      </footer>

    </div>
  </div>


