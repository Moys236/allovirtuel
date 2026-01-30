<?php
function getPreferredLanguage($availableLangs, $default = 'en') {
    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return $default;
    }

    $acceptedLangs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
    
    foreach ($acceptedLangs as $lang) {
        $langCode = strtolower(substr($lang, 0, 2)); // e.g., "fr"
        if (in_array($langCode, $availableLangs)) {
            return $langCode;
        }
    }
    return $default;
}

// Example usage:
$supportedLangs = ['en', 'fr'];
$lang = getPreferredLanguage($supportedLangs, 'en');

$jsonFile = 'allovirtuelContent_' . $lang . '.json';
$jsonData = file_get_contents($jsonFile);
$data = json_decode($jsonData, true);
$navigation = $data['navigation'];
$hero = $data['hero'];
$about = $data['about'];
$whyAllovirtuel = $data['whyAllovirtuel'];
$services = $data['services'];
$projects = $data['projects'];
$contact = $data['contact'];
$settings = $data['settings'];
$process = $data['process'];
$faq = $data['faq'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>AlloVirtuel - Full-Stack Web Developer</title>
    <meta name="description" content="AlloVirtuel - Full-Stack Web Developer">
    <meta name="keywords" content="AlloVirtuel, Full-Stack Web Developer">
    <meta name="author" content="AlloVirtuel">
    <link rel="icon" href="images/allovirtuel logo.jpg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        /* ---------- Base reset and variables ---------- */
        :root {
            --primary: #3b82f6;
            --primary-dark: #1d4ed8;
            --secondary: #8b5cf6;
            --accent: #25D366;
            --dark: #0a0a0a;
            --darker: #050505;
            --light: #f8fafc;
            --lighter: #ffffff;
            --gray: #cbd5e1;
            --dark-gray: #1f2937;
            --border: rgba(255,255,255,0.08);
            --glass: rgba(10,10,10,0.65);
            --glass-border: rgba(255,255,255,0.1);
            --shadow: 0 8px 32px rgba(0,0,0,0.36);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html,body { height: 100%; width: 100%; scroll-behavior: smooth; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--dark);
            color: var(--light);
            line-height: 1.6;
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(59,130,246,0.15) 0%, transparent 50%),
                radial-gradient(circle at 75% 75%, rgba(139,92,246,0.15) 0%, transparent 50%);
            background-attachment: fixed;
            height: 100%;
            width: 100%;
            overflow-x: hidden;
        }

        /* ---------- Floating whatsapp ---------- */
        .whatsapp-flotant-icon {
            display: flex;
            align-items: center;
            gap: 10px;
            background-color: #25D366;
            padding: 10px;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            overflow: hidden;
            transition: width 0.35s ease, border-radius 0.35s ease;
            text-decoration: none;
            color: white;
            position: fixed;
            left: 15px;
            bottom: 10px;
            z-index: 1000;
        }
        .whatsapp-flotant-icon:hover { width: 260px; border-radius: 40px; }
        .whatsapp-flotant-icon .fab { font-size: 36px; color: white; }
        .whatsapp-flotant-icon p { white-space: nowrap; margin: 0; font-weight: 600; }

        /* ---------- Navigation ---------- */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(10,10,10,0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #1f2937;
            z-index: 1000;
            transition: transform .25s ease, background .25s ease;
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display:flex;
            align-items:center;
            justify-content: space-between;
            gap: 1rem;
            
        }
        .logo { transition: transform .25s ease; display:flex; align-items:center; gap:.6rem; text-decoration:none; }
        .logo:hover { transform: rotate(4deg) scale(1.1); }
        .logo img { width:48px; height:48px; object-fit:cover; border-radius:8px; }
        .nav-links {
            display:flex;
            gap:1.25rem;
            list-style:none;
            align-items:center;
        }
        .nav-links a {
            color: #f8fafc;
            text-decoration:none;
            font-weight:500;
            position:relative;
            padding: .25rem .15rem;
        }
        .nav-links a:hover { color:#3b82f6; }
        .nav-links a::after {
            content:'';
            position:absolute; left:0; bottom:-6px; width:0; height:2px; background:#3b82f6; transition: width .25s ease;
        }
        .nav-links a:hover::after { width:100%; }

        .mobile-menu {
            display:none;
            flex-direction:column;
            gap:5px;
            cursor:pointer;
            width:32px;
            height:22px;
            justify-content:center;
        }
        .mobile-menu span {
            height:3px; width:100%; background:#f8fafc; display:block; border-radius:2px; transition: transform .25s ease;
        }

        /* ---------- Sections ---------- */
        .section {
            max-width: 1200px;
            margin: 6rem auto;
            padding: 2rem 2rem;
        }
        .section-titles { margin-bottom:2.5rem; text-align:center; }
        .section-title {
            font-size: clamp(1.6rem, 4vw, 2.6rem);
            font-weight:800;
            background: linear-gradient(135deg,#3b82f6,#8b5cf6);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .section-subtitle { margin-top:.5rem; color:#cbd5e1; font-weight:600; }

        /* ---------- Hero ---------- */
        .hero {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:2rem;
            padding-top: 3.5rem; /* account for fixed navbar */
        }
        .hero-content { flex:1 1 420px; min-width:0; }
        .hero h1 {
            font-size: clamp(1.8rem, 5vw, 3.5rem);
            line-height: 1.05;
            font-weight:900;
            margin-bottom:0.8rem;
            background: linear-gradient(135deg,#f8fafc,#cbd5e1);
            -webkit-background-clip:text; -webkit-text-fill-color:transparent;
        }
        .hero-subtitle { font-size: clamp(.9rem,2vw,1.25rem); color:#94a3b8; margin-bottom:1rem; }
        .hero-description { color:#cbd5e1; margin-bottom:1.5rem; font-size: clamp(.95rem,1.6vw,1.1rem); line-height:1.7; }

        .cta-button {
            display:inline-block;
            background:linear-gradient(135deg,#3b82f6,#1d4ed8);
            padding:.9rem 1.6rem;
            border-radius:40px;
            color:white;
            font-weight:700;
            text-decoration:none;
            box-shadow:0 10px 30px rgba(59,130,246,0.25);
            transition: transform 0.18s ease, box-shadow 0.18s ease;
            
        }
        .cta-button:hover { transform: translateY(-3px); box-shadow:0 18px 40px rgba(59,130,246,0.32); }

        .hero-image {
            flex:0 0 auto;
            width: 340px;
            max-width: 38%;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        .avatar {
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: linear-gradient(135deg,#1f2937,#374151);
            display:flex; align-items:center; justify-content:center;
            font-size: 8rem;
            border:4px solid #3b82f6;
            box-shadow:0 20px 60px rgba(59,130,246,0.16);
            overflow:hidden;
        }
        .avatar img { width:100%; height:100%; object-fit:cover; display:block; }

        /* ---------- About ---------- */
        .about-content {
            display:grid;
            grid-template-columns: 1fr 1fr;
            gap:3rem;
            align-items:center;
        }
        .about-text { 
            color:var(--gray); 
            font-size:1.05rem; 
            line-height:1.8;
        }
        .about-text p {
            margin-bottom: 1.2rem;
            position: relative;
            padding-left: 20px;
        }
        .about-text p::before {
            content: '';
            position: absolute;
            left: 0;
            top: 12px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
        }
        .tech-stack { 
            background: linear-gradient(135deg, var(--dark-gray), #111827); 
            padding:1.5rem; 
            border-radius:20px; 
            border:1px solid var(--border);
            box-shadow: var(--shadow);
            transition: all 0.5s ease;
            overflow: hidden;
            position: relative;
        }
        .tech-stack:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            border-color: var(--primary);
        }
        .tech-stack::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(59,130,246,0.05),
                transparent
            );
            z-index: 1;
        }
        .tech-stack img { 
            width:100%; 
            height:auto; 
            display:block; 
            border-radius:12px;
            position: relative;
            z-index: 2;
            transition: transform 0.5s ease;
        }
        .tech-stack:hover img {
            transform: scale(1.02);
        }

        .why-content {
            /* display:grid;
            grid-template-columns: 1fr 1fr;
            gap:2.5rem;
            align-items:center; */
        }
        .why-text { color:#cbd5e1; font-size:1rem; line-height:1.8; }

        .why-text p {
            margin-bottom: 1.2rem;
            position: relative;
            padding-left: 20px;
        }
        .why-text p::before {
            content: '';
            position: absolute;
            left: 0;
            top: 12px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
        }
        /* ---------- Process / Packs ---------- */
        .process-grid {
            display:flex;
            flex-wrap:wrap;
            gap:2rem;
            justify-content:center;
            align-items:stretch;
        }
        .process-card {
            /* width: 100%;
            max-width: 300px;
            padding:1.25rem;
            background: linear-gradient(135deg,#1f2937,#111827);
            border-radius:16px;
            border:1px solid #374151;
            display:flex; flex-direction:column; align-items:center; gap:.75rem;
            transition: transform .25s ease, box-shadow .25s ease; */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-around;
            gap: 15px;
            padding: 1.3rem;
            background: linear-gradient(135deg, #1f2937, #111827);
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #374151;
            cursor: pointer;
            width: 250px;
        }
        .process-card:hover { transform: translateY(-8px); box-shadow:0 18px 40px rgba(0,0,0,0.35); border-color:#3b82f6; }
        .step-number { width:50px; height:50px; border-radius:50%; display:flex; align-items:center; justify-content:center; background:#111827; color:#3b82f6; border:2px solid #3b82f6; font-weight:800; }
        .process-card .fas { color:#3b82f6; font-size:2rem; }
        /* ---------- Packs ---------- */
        .packs-grid {
            display:grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.25rem;
            padding: 0 8px;
        }
        .pack-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 30px 18px 18px;
            transition: transform .25s ease, box-shadow .25s ease;
            border:1px solid rgba(0,0,0,0.06);
            color:#111827;
        }
        .pack-card.popular { background:#0f172a; border:3px solid #25d366; color:#fff; transform: none; }
        .pack-icon { width:72px; height:72px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; margin:0 auto 18px; }
        .pack-title { text-align:center; font-weight:700; margin-bottom:10px; font-size:1.1rem; }
        .pack-description { text-align:center; color:#6b7280; margin-bottom:16px; }
        .pack-features { list-style:none; padding-left:0; margin-bottom:18px; }
        .pack-features li { padding:8px 0; position:relative; padding-left:28px; color:#4b5563; }
        .pack-features li::before { content:"✓"; position:absolute; left:0; color:#25d366; font-weight:700; }
        .pack-button { display:block; text-decoration:none; padding:12px 18px; border-radius:999px; font-weight:700; background:linear-gradient(135deg,#25D366,#1ebd5b); color:white; margin:0 auto; width: max-content;}

        /* ---------- Projects / Swipe ---------- */
        /* ===========================
        CONTAINER PRINCIPAL
        =========================== */
        .swipe-container { 
            position: relative; 
            width: 100%; 
            overflow: hidden; 
            padding: 20px 0;
            perspective: 1000px;
        }

        /* ===========================
        WRAPPER DES CARTES
        =========================== */
        .cards-wrapper {
            display: flex;
            gap: 18px;
            padding: 0 15px;
            width: max-content; /* Largeur basée sur le contenu */
            animation: scrollCards 30s linear infinite; /* Animation défilement */
        }

        /* ===========================
        CARTE INDIVIDUELLE
        =========================== */
        .project-card {
            position: relative;
            max-width: 420px;
            padding: 14px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.08);
            background: linear-gradient(135deg, #1f2937, #111827);
            overflow: hidden;
            flex-shrink: 0;
            scroll-snap-align: start;
            transform: perspective(1000px) rotateY(0deg);
            transition: transform 0.36s ease, box-shadow 0.36s ease;
        }

        .project-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(2, 2, 2, 0.6);
        }

        /* Effet de brillance au survol */
        .project-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(59,130,246,0.05),
                transparent
            );
            z-index: 1;
        }

        /* ===========================
        IMAGE DE LA CARTE
        =========================== */
        .project-image { 
            position: relative;
            height: 220px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            overflow: hidden;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .project-image::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                transparent,
                rgba(0,0,0,0.2)
            );
            z-index: 1;
        }

        .project-image img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            display: block;
            transition: transform 0.5s ease;
        }

        .project-card:hover .project-image img {
            transform: scale(1.05);
        }

        /* ===========================
        CONTENU DE LA CARTE
        =========================== */
        .project-content { 
            position: relative;
            z-index: 2;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .project-title { 
            position: relative;
            font-size: 1.2rem; 
            font-weight: 700; 
            margin-bottom: 0.8rem; 
            color: #f8fafc;
        }

        .project-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 40px;
            height: 2px;
            background: #3b82f6;
            border-radius: 2px;
        }

        .project-description { 
            flex-grow: 1;
            font-size: 1rem; 
            line-height: 1.6;
            color: #cbd5e1; 
            margin-bottom: 1.5rem; 
        }

        /* ===========================
        LIENS DE LA CARTE
        =========================== */
        .project-links { 
            display: flex; 
            flex-wrap: wrap;
            gap: 0.8rem;
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0.8rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            background: #1f2937;
            color: #f8fafc;
            transition: all 0.3s ease;
        }

        .project-link:hover {
            background: #3b82f6;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(59,130,246,0.3);
        }

        .project-link i {
            transition: transform 0.3s ease;
        }

        .project-link:hover i {
            transform: translateX(3px);
        }

        /* ===== ANIMATION KEYFRAMES ===== */
        @keyframes scrollCards {
        0% { 
            transform: translateX(0); /* Position de départ */
        }
        100% { 
            transform: translateX(-50%); /* Déplace de 50% vers la gauche */
        }
        }

        /* ===== PAUSE AU SURVOL ===== */
        .cards-wrapper:hover {
        animation-play-state: paused; /* Met en pause l'animation */
        }

        /* ===== STYLES DES CARTES INDIVIDUELLES ===== */
        .project-card {
        min-width: 320px; /* Largeur minimale de chaque carte */
        background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
        background-color: #0f172a;
        border-radius: 14px;
        padding: 14px;
        scroll-snap-align: start;
        border: 1px solid rgba(255,255,255,0.04);
        transition: transform .36s ease, box-shadow .36s ease;
        flex-shrink: 0; /* Empêche la compression */
        }

        .project-card:hover {
        transform: translateY(-10px); /* Effet au survol */
        box-shadow: 0 20px 50px rgba(2,2,2,0.6);
        }

        /* ===== VARIANTES D'ANIMATION ===== */

        /* Animation plus rapide (15 secondes) */
        .cards-wrapper.fast {
        animation: scrollCards 15s linear infinite;
        }

        /* Animation plus lente (45 secondes) */
        .cards-wrapper.slow {
        animation: scrollCards 45s linear infinite;
        }

        /* Animation avec rebond */
        @keyframes scrollCardsEase {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
        }

        .cards-wrapper.ease {
        animation: scrollCardsEase 30s ease-in-out infinite;
        }

        /* ===== CONTRÔLES JAVASCRIPT (OPTIONNEL) ===== */

        /* Classe pour arrêter l'animation */
        .cards-wrapper.paused {
        animation-play-state: paused;
        }

        /* Classe pour démarrer l'animation */
        .cards-wrapper.running {
        animation-play-state: running;
        }

        .nav-buttons {
            position:absolute;
            top:50%;
            transform:translateY(-50%);
            background: rgba(255,255,255,0.06);
            border:none;
            width:44px;
            height:44px;
            display:grid;
            place-items:center;
            border-radius:50%;
            color:white;
            cursor:pointer;
            z-index:20;
            transition: transform .15s ease, background .15s ease;
        }
        .nav-buttons:hover { transform: translateY(-50%) scale(1.06); background: rgba(255,255,255,0.12); }
        .nav-prev { left:10px; }
        .nav-next { right:10px; }

        /* ---------- Skills / FAQ / Contact ---------- */
        .skills-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr)); gap:1rem; }
        .skill-category { background:linear-gradient(135deg,#1f2937,#111827); padding:1rem; border-radius:12px; border:1px solid #374151; }
        .faq { max-width:700px; margin: 0 auto; }
        .faq-item { border-radius:8px; margin-bottom:10px; overflow:hidden; border:1px solid #374151; background:#0f172a; }
        .faq-question { padding:14px 18px; cursor:pointer; font-weight:700; background:#3b82f6; display:flex; justify-content:space-between; align-items:center; }
        .faq-answer { max-height:0; overflow:hidden; padding:0 18px; transition: max-height .3s ease, padding .3s ease; color:#cbd5e1; }
        .faq-item.active .faq-answer { max-height:300px; padding:12px 18px; }
        .arrow {transform:rotate(90deg); font-size:2rem;}
        .faq-item.active .arrow {transform:rotate(-90deg);}

        .contact-container { max-width:700px; margin:0 auto; padding:0 1rem; }
        .form-group { margin-bottom:1rem; }
        .form-group input, .form-group textarea { width:100%; padding:12px; border-radius:8px; border:1px solid #374151; background:#0f172a; color:#f8fafc; }

        .contact-info {
            padding: 2rem 0;
        }

        .contact-info h3 {
            color: #3b82f6;
            margin-bottom: 2rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .contact-links {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #cbd5e1;
            text-decoration: none;
            padding: 1rem;
            background: linear-gradient(135deg, #1f2937, #111827);
            border-radius: 15px;
            border: 1px solid #374151;
            transition: all 0.3s ease;
        }

        .contact-link:hover {
            background: #3b82f6;
            color: white;
            transform: translateX(10px);
        }

        .contact-icon {
            width: 24px;
            height: 24px;
            background: #3b82f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* ---------- Scrollbar ---------- */
        ::-webkit-scrollbar { width:8px; }
        ::-webkit-scrollbar-track { background:#1f2937; }
        ::-webkit-scrollbar-thumb { background:#3b82f6; border-radius:4px; }
        ::-webkit-scrollbar-thumb:hover { background:#2563eb; }

        /* ---------- Animations ---------- */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes shine {
            0% { transform: rotate(30deg) translateX(-100%); }
            100% { transform: rotate(30deg) translateX(100%); }
        }
        @keyframes fadeInUp { from{ opacity:0; transform: translateY(30px);} to{opacity:1; transform:translateY(0);} }
        .fade-in-up { animation: fadeInUp .6s ease both; }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }

        /* ---------- Responsive breakpoints ---------- */
        @media (max-width: 1024px) {
            .hero-image { max-width: 34%; width: 300px; }
            .avatar { width: 240px; height:240px; }
            .project-card { flex:0 0 min(88%, 360px); }
            .packs-grid { grid-template-columns: 1fr; padding:0; place-items: center; }
        }

        @media (max-width: 875px) {
            .nav-links { display:none; }
            .mobile-menu { display:flex; }

            /* show nav when active */
            .nav-links.active {
                display:flex;
                position:absolute;
                left:0; right:0;
                top:100%;
                background: rgba(10,10,10,0.98);
                flex-direction:column;
                gap:0;
                padding:1rem;
                border-top:1px solid #374151;
                z-index:999;
            }
            .nav-links a::after {
                content:'';
                position:absolute; left:0; bottom:5px; width:0; height:2px; background:#3b82f6; transition: width .25s ease;
            }
            .nav-links.active a { padding:.8rem 1rem; border-radius:6px; }

            .hero { flex-direction:column-reverse; text-align:center; gap:1.5rem; padding-top:4.5rem; }
            .hero-image { width:100%; max-width:320px; }
            .avatar { width: 220px; height:220px; font-size:5.5rem; }

            .about-content { grid-template-columns: 1fr; }
            .tech-stack img { max-width:100%; height:auto; }

            /* .process-card { max-width:100%; width:100%; } */
            .packs-grid { grid-template-columns: 1fr; padding:0; place-items: center; }

            .cards-wrapper { gap:16px; padding: 0 8px; }
            .project-card { flex:0 0 min(92%, 320px); max-width: 92%; }
            .nav-buttons { width:40px; height:40px; }

            .pack-card { max-width: 92%; }
        }

        @media (max-width: 480px) {
            .nav-container { padding: 0.8rem 1rem; }
            .hero h1 { font-size: 1.6rem; }
            .hero-subtitle { font-size: .95rem; }
            .avatar { width: 180px; height:180px; font-size:4rem; }
            .project-card { max-width: 100%; width:100%; margin: 0; }
            .packs-grid { gap: 12px; }
            #projects { margin: 0; padding: 0; }
            
        }

        .contact-info h3 {
            color: var(--primary);
            margin-bottom: 2rem;
            font-size: 1.5rem;
            text-align: center;
            position: relative;
            display: inline-block;
            width: 100%;
        }
        .contact-info h3::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 3px;
        }

        .contact-links {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .contact-link {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            color: var(--gray);
            text-decoration: none;
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--dark-gray), #111827);
            border-radius: 15px;
            border: 1px solid var(--border);
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        .contact-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59,130,246,0.1), transparent);
            transition: 0.5s;
        }
        .contact-link:hover {
            background: var(--dark-gray);
            color: white;
            transform: translateX(10px);
            border-color: var(--primary);
        }
        .contact-link:hover::before {
            left: 100%;
        }
        .contact-link:hover .contact-icon {
            background: var(--primary);
            transform: scale(1.1);
            color: var(--light);
        }
        .contact-link:hover strong {
            color: var(--primary);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: var(--dark-gray);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: var(--primary);
            transition: all 0.3s ease;
            flex-shrink: 0;
            border: 1px solid var(--border);
        }
        .contact-link-text strong {
            color: white;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: block;
            margin-bottom: 5px;
        }
        .contact-link-text span {
            font-size: 0.9rem;
            color: var(--gray);
        }

         /* ---------- Footer ---------- */
        .site-footer {
            background: linear-gradient(135deg, #111827, #0f172a);
            color: #f8fafc;
            position: relative;
            padding: 4rem 0 0;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 3rem;
        }

        .footer-brand {
            display: flex;
            flex-direction: column;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            text-decoration: none;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .footer-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .footer-logo:hover img {
            transform: rotate(10deg) scale(1.1);
        }

        .footer-tagline {
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-3px);
        }

        .footer-links {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }

        .links-column h3 {
            color: white;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }

        .links-column h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: #3b82f6;
            border-radius: 2px;
        }

        .links-list {
            list-style: none;
        }

        .links-list li {
            margin-bottom: 0.8rem;
        }

        .links-list a {
            color: #94a3b8;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            position: relative;
            padding-left: 15px;
        }

        .links-list a::before {
            content: '›';
            position: absolute;
            left: 0;
            color: #3b82f6;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .links-list a:hover {
            color: white;
            padding-left: 20px;
        }

        .links-list a:hover::before {
            opacity: 1;
            left: 5px;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 3rem auto 0;
            padding: 2rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-legal {
            margin-left: 400px;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
            color: #64748b;
            font-size: 0.9rem;
            margin: 0;

        }

        .legal-links {
            display: flex;
            gap: 1rem;
        }

        .legal-links a {
            color: #64748b;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .legal-links a:hover {
            color: #3b82f6;
        }

        .back-to-top a {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(59,130,246,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #3b82f6;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .back-to-top a:hover {
            background: #3b82f6;
            color: white;
            transform: translateY(-3px);
        }

        .footer-decoration {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        }

        @media (max-width: 875px) {
            .footer-container {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .footer-links {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 1.5rem;
                text-align: center;
            }
            
            .footer-legal {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .legal-links {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* //////////////// */
        /* ==================== */
/* Enhanced Responsiveness */
/* ==================== */

@media (max-width: 1024px) {
    /* Tablet adjustments */
    .hero {
        padding-top: 5rem;
    }
    
    .about-content {
        gap: 2rem;
    }
    
    .process-grid {
        justify-content: center;
    }
}

@media (max-width: 768px) {
    /* Small tablet adjustments */
    .section {
        margin: 4rem auto;
        padding: 1.5rem;
    }
    
    .hero-content {
        text-align: center;
    }
    
    .cta-button {
        margin: 0 auto;
    }
    
    .packs-grid {
        grid-template-columns: 1fr;
        max-width: 400px;
        margin: 0 auto;
    }
    
    .pack-card {
        width: 100%;
    }
}

@media (max-width: 640px) {
    /* Large mobile adjustments */
    .nav-container {
        padding: 0.8rem 1.2rem;
    }
    
    .hero h1 {
        font-size: 2.2rem;
    }
    
    .hero-description {
        font-size: 1rem;
    }
    
    .avatar {
        width: 200px;
        height: 200px;
    }
    
    .section-titles {
        margin-bottom: 1.5rem;
    }
    
    .project-card {
        min-width: 280px;
    }
    
    .contact-link {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
    }
    
    .contact-icon {
        margin-bottom: 0.5rem;
    }
}

@media (max-width: 480px) {
    /* Small mobile adjustments */
    .hero h1 {
        font-size: 1.8rem;
    }
    
    .hero-subtitle {
        font-size: 0.9rem;
    }
    
    .section-title {
        font-size: 1.8rem;
    }
    
    .section-subtitle {
        font-size: 0.9rem;
    }
    
    .about-text p,
    .why-text p {
        font-size: 0.95rem;
        line-height: 1.6;
    }
    
    .process-card {
        width: 100%;
        max-width: none;
    }
    
    .project-card {
        min-width: 260px;
    }
    
    .project-links {
        flex-direction: column;
    }
    
    .project-link {
        width: 100%;
        justify-content: center;
    }
    
    .footer-container {
        padding: 0 1rem;
    }
    
    .footer-links {
        grid-template-columns: 1fr;
    }
    
    .whatsapp-flotant-icon {
        width: 50px;
        height: 50px;
    }
    
    .whatsapp-flotant-icon:hover {
        width: 200px;
    }
}

@media (max-width: 360px) {
    /* Extra small devices */
    .hero h1 {
        font-size: 1.6rem;
    }
    
    .hero-image {
        max-width: 90%;
    }
    
    .avatar {
        width: 180px;
        height: 180px;
    }
    
    .project-card {
        min-width: 240px;
    }
    
    .pack-card {
        padding: 20px 12px;
    }
    
    .pack-button {
        padding: 10px 15px;
        font-size: 0.8rem;
        font-weight: 600;
        width: max-content;
        margin: 0 auto;
    }
}

    </style>
</head>
<body>
    <a href="https://wa.me/<?php echo $settings['contact']['whatsapp']; ?>?text=Bonjour%2C%20je%20souhaite%20savoir%20comment%20d%C3%A9marrer%20un%20projet%20avec%20AlloVirtuel."
       target="_blank" class="whatsapp-flotant-icon exte">
        <span class="fab fa-whatsapp"></span>
        <p>contacter nos sur WhatsApp</p>
    </a>

    <!-- Navigation -->
    <nav class="navbar" role="navigation" aria-label="Main navigation">
        <div class="nav-container">
            <a href="#home" class="logo" aria-label="Allo Virtuel logo">
                <img src="<?php echo $settings['logo']; ?>" alt="Allo Virtuel">
            </a>

            <ul class="nav-links" id="navLinks">
                <?php foreach ($navigation as $item) { ?>
                    <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
                <?php } ?>
            </ul>

            <div class="mobile-menu" id="mobileMenu" aria-label="Toggle navigation" aria-expanded="false" role="button" tabindex="0">
                <span></span><span></span><span></span>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="section">
        <div class="hero fade-in-up">
            <div class="hero-content">
                <h1><?php echo $hero['title']; ?></h1>
                <p class="hero-subtitle"><?php echo $hero['subtitle']; ?></p>
                <p class="hero-description"><?php echo $hero['description']; ?></p>
                <a href="#services" class="cta-button">Start Your Project With Us</a>
            </div>

            <div class="hero-image" aria-hidden="true">
                <div class="avatar float-animation">
                    <img src="<?php echo $hero['image']['src']; ?>" alt="<?php echo $hero['image']['alt']; ?>">
                </div>
            </div>
            
            <div class="hero-bg-elements">
                <div class="hero-bg-circle circle-1"></div>
                <div class="hero-bg-circle circle-2"></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $about['title']; ?></h2>
            <p class="section-subtitle"><?php echo $about['subtitle'] ?? ''; ?></p>
        </div>

        <div class="about-content">
            <div class="about-text">
                <?php foreach ($about['content'] as $content) { ?>
                    <p style="margin-bottom:0.75rem;"><?php echo $content; ?></p>
                <?php } ?>
            </div>

            <div class="tech-stack" aria-hidden="true">
                <img src="<?php echo $about['image']; ?>" alt="about us">
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section id="whyAllovirtuel" class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $whyAllovirtuel['title']; ?></h2>
            <p class="section-subtitle"><?php echo $whyAllovirtuel['subtitle'] ?? ''; ?></p>
        </div>

        <div class="why-content">
            <div class="why-text">
                <?php foreach ($whyAllovirtuel['content'] as $content) { ?>
                    <p style="margin-bottom:0.75rem; "><?php echo $content; ?></p>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Services / Packs -->
    <section id="services" class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $services['title']; ?></h2>
            <p class="section-subtitle"><?php echo $services['subtitle'] ?? ''; ?></p>
        </div>

        <div class="packs-grid">
            <?php foreach ($services['packages'] as $package) { ?>
                <div class="pack-card pack-<?php echo $package['id']; ?> <?php echo $package['popular'] ? 'popular' : ''; ?>">
                    <div class="pack-icon"><?php echo $package['icon']; ?></div>
                    <h3 class="pack-title"><?php echo $package['title']; ?></h3>
                    <p class="pack-description"><?php echo $package['description']; ?></p>

                    <a class="pack-button"
                       href="https://wa.me/212625929616?text=Bonjour%2C%20je%20suis%20int%C3%A9ress%C3%A9(e)%20par%20le%20Pack%20<?php echo urlencode($package['title']); ?>"
                       target="_blank"><span class="fab fa-whatsapp"></span>&nbsp;Get a Quote on WhatsApp</a>

                    <ul class="pack-features">
                        <?php foreach ($package['features'] as $feature) { ?>
                            <li><?php echo $feature; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- How it works -->
    <section id="howItWorks" class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $process['title']; ?></h2>
            <p class="section-subtitle"><?php echo $process['subtitle'] ?? ''; ?></p>
        </div>

        <div class="process-grid">
            <?php foreach ($process['steps'] as $step) { ?>
                <div class="process-card">
                    <div class="step-number"><?php echo $step['number']; ?></div>
                    <i class="fas fa-bullseye step-icon"></i>
                    <h3><?php echo $step['title']; ?></h3>
                    <p style="text-align:center;"><?php echo $step['description']; ?></p>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $projects['title']; ?></h2>
        </div>

        <div class="swipe-container" aria-roledescription="carousel">
           
            <div class="cards-wrapper" id="cardsWrapper" role="list">
                <?php for ($i = 0; $i < 2; $i++){ foreach ($projects['items'] as $project) { ?>
                    <div class="project-card" role="listitem">
                        <div class="project-image">
                            <?php
                            echo isset($project['image']) && $project['image']
                                ? '<img src="' . $project['image'] . '" alt="' . htmlspecialchars($project['title']) . '">'
                                : $project['icon'];
                            ?>
                        </div>

                        <div class="project-content">
                            <h3 class="project-title"><?php echo $project['title']; ?></h3>
                            <p class="project-description"><?php echo $project['description']; ?></p>
                            <div class="project-links">
                                <?php foreach ($project['links'] as $link) { ?>
                                    <a href="<?php echo $link['href']; ?>" target="_blank" class="project-link">
                                        <?php if (isset($link['icon'])): ?>
                                            <i class="<?php echo $link['icon']; ?>"></i>
                                        <?php endif; ?>
                                        <?php echo $link['text']; ?>
                                    </a>
                                <?php } ?>
                                
                            </div>
                        </div>
                    </div>
                <?php } } ?>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $faq['title']; ?></h2>
        </div>

        <div class="faq" role="list">
            <?php foreach ($faq['items'] as $question) { ?>
                <div class="faq-item">
                    <div class="faq-question"><?php echo $question['question']; ?><span class="arrow">›</span></div>
                    <div class="faq-answer"><?php echo $question['answer']; ?></div>
                </div>
            <?php } ?>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="section">
        <div class="section-titles">
            <h2 class="section-title"><?php echo $contact['title']; ?></h2>
        </div>

        <div class="contact-container">
            <div class="contact-info">
                <h3><?php echo $contact['channels']['subtitle']; ?></h3>
                <div class="contact-links">
                    <?php foreach ($contact['channels']['items'] as $channel) { ?>
                        <a href="<?php echo $channel['href']; ?>" class="contact-link" target="_blank" rel="noopener">
                            <div class="contact-icon">
                                <?php
                                if ($channel['iconWithClass']) {
                                    echo '<i class="' . $channel['icon'] . '"></i>';
                                } else {
                                    echo $channel['icon'];
                                }
                                ?>
                            </div>
                            <div>
                                <strong><?php echo $channel['label']; ?></strong><br>
                                <?php echo $channel['value']; ?>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <a href="#home" class="footer-logo">
                    <img src="<?php echo $settings['logo']; ?>" alt="AlloVirtuel Logo">
                    <span>AlloVirtuel</span>
                </a>
                <p class="footer-tagline">Transforming ideas into digital reality</p>
                
                <div class="social-links">
                    <?php foreach ($contact['channels']['items'] as $channel): ?>
                        <?php if (strpos(strtolower($channel['label']), 'social') !== false || 
                                strpos(strtolower($channel['label']), 'linkedin') !== false || 
                                strpos(strtolower($channel['label']), 'twitter') !== false || 
                                strpos(strtolower($channel['label']), 'instagram') !== false): ?>
                            <a href="<?php echo $channel['href']; ?>" target="_blank" rel="noopener" class="social-link">
                                <?php if ($channel['iconWithClass']): ?>
                                    <i class="<?php echo $channel['icon']; ?>"></i>
                                <?php else: ?>
                                    <?php echo $channel['icon']; ?>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="footer-links">
                <div class="links-column">
                    <h3 class="links-title">Quick Links</h3>
                    <ul class="links-list">
                        <?php foreach ($navigation as $item): ?>
                            <li><a href="<?php echo $item['href']; ?>"><?php echo $item['label']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="links-column">
                    <h3 class="links-title">Services</h3>
                    <ul class="links-list">
                        <?php foreach ($services['packages'] as $package): ?>
                            <li><a href="#services"><?php echo $package['title']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="links-column">
                    <h3 class="links-title">Contact</h3>
                    <ul class="links-list">
                        <?php foreach ($contact['channels']['items'] as $channel): ?>
                            <?php if (strpos(strtolower($channel['label']), 'social') === false): ?>
                                <li>
                                    <a href="<?php echo $channel['href']; ?>" target="_blank" rel="noopener">
                                        <?php echo $channel['label']; ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="footer-bottom">
            <div class="footer-legal">
                <center><p>&copy; <?php echo date('Y'); ?> AlloVirtuel. All rights reserved.</p></center>
            </div>
            
            <div class="back-to-top">
                <a href="#home" aria-label="Back to top">
                    <i class="fas fa-arrow-up"></i>
                </a>
            </div>
        </div>
        
        <div class="footer-decoration"></div>
    </footer>

    <script>
        /* ----------------- Utilities ----------------- */
        history.scrollRestoration = 'manual';

        function smoothScrollTo(targetY, duration = 500) {
            const startY = window.scrollY;
            const distance = targetY - startY;
            const startTime = performance.now();
            function animation(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const ease = 0.5 * (1 - Math.cos(Math.PI * progress));
                window.scrollTo(0, startY + distance * ease);
                if (progress < 1) requestAnimationFrame(animation);
            }
            requestAnimationFrame(animation);
        }

        /* ----------------- Smooth anchor links ----------------- */
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                // allow external anchors to function normally
                const href = this.getAttribute('href');
                if (!href || href === '#') return;
                const el = document.querySelector(href);
                if (!el) return;
                e.preventDefault();
                const top = el.getBoundingClientRect().top + window.scrollY - (document.querySelector('.navbar')?.offsetHeight || 60);
                smoothScrollTo(top, 600);
                // close mobile nav when clicked
                if (window.innerWidth <= 875) {
                    navLinks.classList.remove('active');
                    mobileMenu.setAttribute('aria-expanded', 'false');
                }
            });
        });

        /* ----------------- Intersection animations ----------------- */
        const observerOptions = { threshold: 0.12, rootMargin: '0px 0px -40px 0px' };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('fade-in-up'); });
        }, observerOptions);

        document.querySelectorAll('.project-card, .skill-category, .about-content > *, .contact-container > *').forEach(el => { if (el) observer.observe(el); });

        /* ----------------- Navbar hide on scroll ----------------- */
        let lastScrollTop = 0;
        window.addEventListener('scroll', () => {
            localStorage.setItem('scrollY', window.scrollY);
            const navbar = document.querySelector('.navbar');
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            if (scrollTop > lastScrollTop && scrollTop > window.innerHeight / 3) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = 'translateY(0)';
            }
            lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
            const opacity = Math.min(scrollTop / 200, 0.98);
            navbar.style.background = `rgba(10,10,10,${opacity})`;
        });

        /* ----------------- Mobile menu toggle (CSS-driven) ----------------- */
        const mobileMenu = document.getElementById('mobileMenu');
        const navLinks = document.getElementById('navLinks');
        mobileMenu.addEventListener('click', () => {
            const isActive = navLinks.classList.toggle('active');
            mobileMenu.setAttribute('aria-expanded', String(isActive));
        });
        mobileMenu.addEventListener('keydown', (e) => { if (e.key === 'Enter' || e.key === ' ') mobileMenu.click(); });

        // Close mobile nav on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth > 875) {
                navLinks.classList.remove('active');
                mobileMenu.setAttribute('aria-expanded', 'false');
            }
        });

        /* ----------------- Restore scroll position ----------------- */
        window.addEventListener('load', () => {
            const savedY = localStorage.getItem('scrollY');
            if (savedY) smoothScrollTo(parseInt(savedY), 700);
        });

        /* ----------------- Parallax hero (light) ----------------- */
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            if (!hero) return;
            const rate = scrolled * -0.12; // very light
            hero.style.transform = `translateY(${rate}px)`;
        });

        /* ----------------- Typing animation (hero) ----------------- */
        function typeWriter(element, text, speed = 80) {
            if (!element) return;
            let i = 0;
            element.textContent = '';
            function type() {
                if (i < text.length) {
                    element.textContent += text.charAt(i);
                    i++; setTimeout(type, speed);
                }
            }
            type();
        }
        window.addEventListener('load', () => {
            const heroTitle = document.querySelector('.hero h1');
            if (heroTitle) {
                const originalText = heroTitle.textContent.trim();
                typeWriter(heroTitle, originalText, 40);
            }
        });

        /* ----------------- Particles (optional, low cost) ----------------- */
        function createParticles() {
            const particlesContainer = document.createElement('div');
            particlesContainer.style.cssText = 'position:fixed;top:0;left:0;width:100%;height:100%;pointer-events:none;z-index:-1;overflow:hidden;';
            document.body.appendChild(particlesContainer);
            for (let i = 0; i < 30; i++) {
                const p = document.createElement('div');
                p.style.cssText = `position:absolute;width:2px;height:2px;border-radius:50%;background:#3b82f6;opacity:${Math.random()*0.5+0.15};left:${Math.random()*100}%;top:${Math.random()*100}%;animation:float-particles ${Math.random()*14+8}s linear infinite;`;
                particlesContainer.appendChild(p);
            }
        }

        const style = document.createElement('style');
        style.textContent = `@keyframes float-particles { 0%{transform:translateY(100vh) translateX(0);}100%{transform:translateY(-100vh) translateX(100px);} }`;
        document.head.appendChild(style);
        // low-cost particle init
        try { createParticles(); } catch (e) { /* fail silently */ }

        /* ----------------- Section reveal ----------------- */
        const sections = document.querySelectorAll('.section');
        const revealSection = function (entries, observer) {
            const [entry] = entries;
            if (!entry.isIntersecting) return;
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
        };
        const sectionObserver = new IntersectionObserver(revealSection, { root:null, threshold:0.12 });
        sections.forEach(function(section) { sectionObserver.observe(section); section.style.opacity='0'; section.style.transform='translateY(30px)'; section.style.transition='all .7s ease-out'; });

        /* ----------------- FAQ toggle ----------------- */
        document.querySelectorAll('.faq-question').forEach(item => {
            item.addEventListener('click', () => item.parentElement.classList.toggle('active'));
        });



        ///////
        /* ----------------- Touch device detection ----------------- */
const isTouchDevice = 'ontouchstart' in window || navigator.maxTouchPoints;

if (isTouchDevice) {
    document.body.classList.add('touch-device');
    
    // Improve touch target sizes
    document.querySelectorAll('a, button').forEach(el => {
        if (el.offsetWidth < 44 || el.offsetHeight < 44) {
            el.style.minWidth = '44px';
            el.style.minHeight = '44px';
            el.style.padding = '12px 0';
        }
    });
}

/* ----------------- Project cards swipe on mobile ----------------- */
if (isTouchDevice) {
    const cardsWrapper = document.getElementById('cardsWrapper');
    let isDown = false;
    let startX;
    let scrollLeft;

    cardsWrapper.addEventListener('mousedown', (e) => {
        isDown = true;
        startX = e.pageX - cardsWrapper.offsetLeft;
        scrollLeft = cardsWrapper.scrollLeft;
    });

    cardsWrapper.addEventListener('mouseleave', () => {
        isDown = false;
    });

    cardsWrapper.addEventListener('mouseup', () => {
        isDown = false;
    });

    cardsWrapper.addEventListener('mousemove', (e) => {
        if(!isDown) return;
        e.preventDefault();
        const x = e.pageX - cardsWrapper.offsetLeft;
        const walk = (x - startX) * 2;
        cardsWrapper.scrollLeft = scrollLeft - walk;
    });
}
    </script>
    <!-- Chatbot Styles -->
<style>
  .chatbot-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: linear-gradient(90deg,var(--gold1),var(--gold2));
    color: #111;
    border: none;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    font-size: 26px;
    cursor: pointer;
    z-index: 9999;
    transition: all 0.3s ease;
  }
  .chatbot-container {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 0;
    height: 0;
    background: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.4);
    z-index: 9998;
    transition: all 0.4s ease;
  }
  .chatbot-container.open {
    width: 350px;
    height: 500px;
  }
  }
</style>

<!-- Chatbot HTML -->
<div class="chatbot-container" id="chatbotContainer">
  <!-- Chatbase Script -->
  <script>
    (function(){
      if(!window.chatbase || window.chatbase("getState")!=="initialized"){
        window.chatbase=(...arguments)=>{if(!window.chatbase.q){window.chatbase.q=[]}window.chatbase.q.push(arguments)};
        window.chatbase=new Proxy(window.chatbase,{get(target,prop){if(prop==="q"){return target.q}return(...args)=>target(prop,...args)}});
      }
      const onLoad=function(){
        const script=document.createElement("script");
        script.src="https://www.chatbase.co/embed.min.js";script.id="0ColNAtk5dkSmhLjYTuMf";script.domain="www.chatbase.co";
        document.getElementById("chatbotContainer").appendChild(script);
      };
      if(document.readyState==="complete"){onLoad();}
      else{window.addEventListener("load",onLoad);}
    })();
  </script>
</div>

<button class="chatbot-toggle" id="chatbotToggle">
  
</button>

<!-- Chatbot Toggle Script -->
<script>
  const chatbotToggle = document.getElementById('chatbotToggle');
  const chatbotContainer = document.getElementById('chatbotContainer');
  chatbotToggle.addEventListener('click', () => {
    chatbotContainer.classList.toggle('open');
  });
</script>

</body>
</html>
