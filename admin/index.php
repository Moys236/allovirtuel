<?php
require_once 'config.php';

if (!isLoggedIn()) {
    redirect('logIn.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #8b5cf6;
            --dark: #0a0a0a;
            --light: #f8fafc;
            --success: #10b981;
            --danger: #ef4444;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-weight: 600;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #1e293b;
            line-height: 1.6;
        }
        
        .dashboard {
            display: flex;
            min-height: 100vh;

        }
        
        /* Sidebar */
        .sidebar {
            background: linear-gradient(135deg, #1f2937, #111827);
            color: var(--light);
            padding: 1rem;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid #374151;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logo i {
            font-size: 1.8rem;
        }
        
        .nav-links {
            list-style: none;
        }

        
        
        .nav-links li {
            margin-bottom: 0.5rem;
        }
        
        .nav-links a {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--light);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .nav-links a:hover {
            background: rgba(59, 130, 246, 0.2);
            color: var(--primary);
        }
        
        .nav-links a.active {
            background: var(--primary);
            color: white;
        }
        
        .nav-links i {
            width: 24px;
            text-align: center;
            margin-right: 0.5rem;
        }
        
        /* Main Content */
        .main-content {
            padding: 2rem;
            padding-top: 180px; /* Add padding to account for fixed header */
            overflow-y: auto;
            min-height: 100vh;
            margin-left: 250px;
        }
        
        #previewContent {
            padding: 2rem;
            flex: 1;
            overflow-y: auto;
            margin-top: 60px; /* Space for header */
            box-sizing: border-box;
        }
        
        .header {
            position: fixed;
            top: 0;
            left: 250px; /* Match sidebar width */
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 1.5rem 2rem;
            background: white;
            box-shadow: 0 2px 4px 2px rgba(0,0,0,0.1);
            border-bottom: 1px solid #e2e8f0;
        }
        
        .header h1 {
            font-size: 1.8rem;
            color: var(--dark);
        }
        
        .actions {
            display: flex;
            gap: 1rem;
            align-items: center;
            /* flex-wrap: wrap; */
            /* max-width: 80%; */
            justify-content: flex-end;
        }
        
        
        .btn-group {
            outline: 1px solid var(--primary);
        }
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-outline {
            background: transparent;
            border: 1px solid #cbd5e1;
            color: #64748b;
        }
        
        .btn-outline:hover {
            background: #f1f5f9;
        }
        
        .btn-success {
            background: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background: #059669;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        /* Section Editor */
        .section-editor {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-title i {
            color: var(--primary);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #334155;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .form-array-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }
        
        .form-array-item .remove-btn {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            background: var(--danger);
            color: white;
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        
        .add-item-btn {
            background: var(--success);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        
        /* Preview Area */
        .preview-container {
            position: fixed;
            top: 0;
            right: 0;
            width: 50%;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
            z-index: 1005;
            overflow-y: auto;
            transform: translateX(100%);
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .preview-container.open {
            transform: translateX(0);
        }

        #previewContent {
            margin-top: 1px;
        }
        
        .preview-header {
            padding: 1rem;
            background: var(--dark);
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 2;
            width: 100%;
            box-sizing: border-box;
        }
        
        .close-preview {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .toggle-preview {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--primary);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            cursor: pointer;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 90;
        }
        
        .menu-toggle {
            display: none;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: var(--primary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            z-index: 1002;
        }
        
        .menu-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }
        
        .menu-toggle:active {
            transform: translateY(1px);
        }
        
        .sidebar {
            width: 250px;
            background-color: #16213e;
            color: white;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            color: var(--primary);
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1002;
            transition: all 0.3s ease;
        }
        
        .sidebar-toggle:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        /* Responsive */
        @media (max-width: 1200px) {
            .header {
                left: 0;
                padding: 1rem;
            }
            .actions {
                max-width: 70%;
            }
            .sidebar {
                z-index: 1001;
            }
            .actions button {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .preview-container {
                width: 70%;
            }
        }
        
        @media (max-width: 992px) {
            .header {
                /* flex-direction: column; */
                align-items: flex-end;
                padding: 1rem;
            }
            .actions {
                width: 100%;
                max-width: 100%;
                justify-content: flex-end;
                gap: 0.5rem;
                margin-left: 2.7rem;
            }
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.visible {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            }
            
            .sidebar-toggle {
                display: flex;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .preview-container {
                width: 100%;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                z-index: 1001;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            }
            
            .sidebar.visible {
                transform: translateX(0);
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.3);
            }
            
            .menu-toggle {
                display: flex;
            }
            
            /* .dashboard {
                grid-template-columns: 1fr;
            } */

            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .header {
                left: 0;
                height: fit-content;
                padding: 1rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                
            }
            
            .actions {
                position: fixed;
                top: 70px;
                left: 0;
                right: 0;
                background-color: #1a1a2e;
                padding: 1rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                transform: translateY(-150%);
                transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                z-index: 999;
                max-width: 90%;
                display: flex;
                flex-direction: column;
                gap: 0.75rem;
                opacity: 0;
                pointer-events: none;

            }
            
            .actions.visible {
                transform: translateY(-28px);
                opacity: 1;
                pointer-events: auto;
            }
            
            .actions button {
                width: 40%;
                justify-content: flex-start;
                padding: 0.75rem 1rem;
                border-radius: 6px;
                transition: all 0.2s ease;
            }
            
            .actions button i {
                margin-right: 0.5rem;
                width: 20px;
                text-align: center;
            }
            
            .btn {
                width: 40%;
                justify-content: center;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".sidebar" data-bs-offset="100" tabindex="0">
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="dashboard">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="logo">
                <i class="fas fa-cogs"></i>
                <span>Portfolio Dashboard</span>
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#general"><i class="fas fa-cog me-2"></i>General Settings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#hero"><i class="fas fa-home me-2"></i>Hero Section</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about"><i class="fas fa-user me-2"></i>About Section</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#services"><i class="fas fa-concierge-bell me-2"></i>Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#process"><i class="fas fa-project-diagram me-2"></i>Process</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#projects"><i class="fas fa-briefcase me-2"></i>Projects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#faq"><i class="fas fa-question-circle me-2"></i>FAQ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#contact"><i class="fas fa-envelope me-2"></i>Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#imageUpload"><i class="fas fa-envelope me-2"></i>Image upload</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#imageManagement"><i class="fas fa-envelope me-2"></i>Image management</a>
                </li>
            </ul>
        </nav>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                
                <div class="actions" id="actionsMenu">
                    <button id="saveBtn" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <button id="resetBtn" class="btn btn-outline" onclick="reset()">
                        <i class="fas fa-trash-alt"></i> Discard Changes
                    </button>
                    <button id="downloadJsonBtn" class="btn btn-success">
                        <i class="fas fa-download"></i> Download JSON
                    </button>
                    <button id="sendToServerBtn" class="btn btn-primary">
                        <i class="fas fa-cloud-upload-alt"></i> Send to Server
                    </button>
                    <button class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" id="langEn" class="btn btn-outline-primary lang-btn active" data-lang="en">EN</button>
                        <button type="button" id="langFr" class="btn btn-outline-primary lang-btn" data-lang="fr">FR</button>
                    </div>
                </div>
            </div>
            
            <!-- General Settings -->
            <div id="general" class="section-editor">
                <h2 class="section-title"><i class="fas fa-cog"></i> General Settings</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="companyName">Company Name</label>
                        <input type="text" id="companyName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="logo">Logo URL</label>
                        <input type="text" id="logo" class="form-control">
                    </div>
                </div>
                
                <h3 style="margin: 1.5rem 0 1rem;">Theme Colors</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="primaryColor">Primary Color</label>
                        <input type="color" id="primaryColor" class="form-control" style="height: 45px; padding: 0.25rem;">
                    </div>
                    <div class="form-group">
                        <label for="secondaryColor">Secondary Color</label>
                        <input type="color" id="secondaryColor" class="form-control" style="height: 45px; padding: 0.25rem;">
                    </div>
                    <div class="form-group">
                        <label for="backgroundColor">Background Color</label>
                        <input type="color" id="backgroundColor" class="form-control" style="height: 45px; padding: 0.25rem;">
                    </div>
                    <div class="form-group">
                        <label for="textColor">Text Color</label>
                        <input type="color" id="textColor" class="form-control" style="height: 45px; padding: 0.25rem;">
                    </div>
                </div>
                
                <h3 style="margin: 1.5rem 0 1rem;">Contact Information</h3>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp Number</label>
                        <input type="text" id="whatsapp" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="linkedin">LinkedIn</label>
                        <input type="text" id="linkedin" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="github">GitHub</label>
                        <input type="text" id="github" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="instagram">Instagram</label>
                        <input type="text" id="instagram" class="form-control">
                    </div>
                </div>
            </div>
            
            <!-- Hero Section -->
            <div id="hero" class="section-editor">
                <h2 class="section-title"><i class="fas fa-home"></i> Hero Section</h2>
                
                <!-- Main Content -->
                <div class="form-group">
                    <label for="heroTitle">Main Title</label>
                    <input type="text" id="heroTitle" class="form-control" placeholder="Welcome to AlloVirtuel">
                </div>
                
                <div class="form-group">
                    <label for="heroSubtitle">Subtitle</label>
                    <input type="text" id="heroSubtitle" class="form-control" placeholder="Full-Stack Developers & Creative Problem Solvers">
                </div>
                
                <div class="form-group">
                    <label for="heroDescription">Description</label>
                    <textarea id="heroDescription" class="form-control" rows="4" placeholder="We create smart, affordable digital solutions for small businesses and local shops. From clean websites to custom chatbots, we help you connect, engage, and grow — fast."></textarea>
                </div>
                
                <!-- Call to Action -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="heroCtaText">Button Text</label>
                        <input type="text" id="heroCtaText" class="form-control" placeholder="e.g., Start Your Project">
                    </div>
                    <div class="form-group">
                        <label for="heroCtaLink">Button Link</label>
                        <input type="text" id="heroCtaLink" class="form-control" placeholder="#services">
                    </div>
                </div>
                
                <!-- Hero Image/Emoji -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="heroImageAlt">Image Alt Text</label>
                        <input type="text" id="heroImageAlt" class="form-control" placeholder="Developer avatar">
                    </div>
                    <div class="form-group">
                        <label for="heroImageEmoji">Avatar Emoji</label>
                        <div class="emoji-input-container">
                            <input type="text" id="heroImageEmoji" class="form-control" placeholder="👨‍💻" maxlength="2">
                            <span class="emoji-preview" id="emojiPreview">👨‍💻</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- About Section -->
            <div id="about" class="section-editor">
                <h2 class="section-title"><i class="fas fa-user"></i> About Section</h2>
                <div class="form-group">
                    <label for="aboutTitle">Title</label>
                    <input type="text" id="aboutTitle" class="form-control">
                </div>
                
                <div id="aboutContentItems">
                    <!-- Dynamically generated content items -->
                </div>
                
                <button id="addAboutContent" class="add-item-btn">
                    <i class="fas fa-plus"></i> Add Paragraph
                </button>
                
                <div class="form-group" style="margin-top: 1.5rem;">
                    <label for="aboutImage">Image URL</label>
                    <input type="text" id="aboutImage" class="form-control">
                </div>
            </div>
            
            <!-- Services Section -->
            <div id="services" class="section-editor">
                <h2 class="section-title"><i class="fas fa-concierge-bell"></i> Services</h2>
                <div class="form-group">
                    <label for="servicesTitle">Title</label>
                    <input type="text" id="servicesTitle" class="form-control">
                </div>
                <div class="form-group">
                    <label for="servicesSubtitle">Subtitle</label>
                    <input type="text" id="servicesSubtitle" class="form-control">
                </div>
                
                <h3 style="margin: 1.5rem 0 1rem;">Packages</h3>
                <div id="servicePackages">
                    <!-- Dynamically generated packages -->
                </div>
                
                <button id="addServicePackage" class="add-item-btn">
                    <i class="fas fa-plus"></i> Add Package
                </button>
            </div>
            
            <!-- Process Section -->
            <div id="process" class="section-editor">
                <h2 class="section-title"><i class="fas fa-project-diagram"></i> Process</h2>
                <div class="form-group">
                    <label for="processTitle">Title</label>
                    <input type="text" id="processTitle" class="form-control">
                </div>
                <div class="form-group">
                    <label for="processSubtitle">Subtitle</label>
                    <input type="text" id="processSubtitle" class="form-control">
                </div>
                
                <h3 style="margin: 1.5rem 0 1rem;">Steps</h3>
                <div id="processSteps">
                    <!-- Dynamically generated steps -->
                </div>
                
                <button id="addProcessStep" class="add-item-btn">
                    <i class="fas fa-plus"></i> Add Step
                </button>
            </div>
            
            <!-- Projects Section -->
            <div id="projects" class="section-editor">
                <h2 class="section-title"><i class="fas fa-briefcase"></i> Projects</h2>
                <div class="form-group">
                    <label for="projectsTitle">Title</label>
                    <input type="text" id="projectsTitle" class="form-control">
                </div>
                
                <h3 style="margin: 1.5rem 0 1rem;">Project Items</h3>
                <div id="projectItems">
                    <!-- Dynamically generated projects -->
                </div>
                
                <button id="addProject" class="add-item-btn">
                    <i class="fas fa-plus"></i> Add Project
                </button>
            </div>
            
            <!-- FAQ Section -->
            <div id="faq" class="section-editor">
                <h2 class="section-title"><i class="fas fa-question-circle"></i> FAQ</h2>
                <div class="form-group">
                    <label for="faqTitle">Title</label>
                    <input type="text" id="faqTitle" class="form-control">
                </div>
                
                <h3 style="margin: 1.5rem 0 1rem;">Questions & Answers</h3>
                <div id="faqItems">
                    <!-- Dynamically generated FAQ items -->
                </div>
                
                <button id="addFaqItem" class="add-item-btn">
                    <i class="fas fa-plus"></i> Add FAQ Item
                </button>
            </div>
            
            <!-- Contact Section -->
            <div id="contact" class="section-editor">
                <h2 class="section-title"><i class="fas fa-envelope"></i> Contact</h2>
                <div class="form-group">
                    <label for="contactTitle">Title</label>
                    <input type="text" id="contactTitle" class="form-control">
                </div>
                
                
                
                <h3 style="margin: 1.5rem 0 1rem;">Contact Channels</h3>
                <div id="contactChannels">
                    <!-- Dynamically generated contact channels -->
                </div>
                
                <button id="addContactChannel" class="add-item-btn">
                    <i class="fas fa-plus"></i> Add Contact Channel
                </button>
            </div>
            
            <!-- Image Upload Section -->
            <div id="imageUpload" class="section-editor">
                <h2 class="section-title"><i class="fas fa-images"></i> Image Upload</h2>
                
                <div class="form-group">
                    <label for="imageUploadInput">Upload Images</label>
                    <input type="file" id="imageUploadInput" class="form-control" multiple accept="image/*">
                    <small class="text-muted">Select one or more images to upload (JPG, PNG, GIF, etc.)</small>
                </div>
                
                <div class="form-group">
                    <button id="uploadButton" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Images
                    </button>
                    <div id="uploadProgress" class="progress mt-3" style="display: none;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                
                <div id="uploadStatus" class="mt-3"></div>
                
                <div id="uploadedImages" class="mt-4">
                    <h5>Uploaded Images</h5>
                    <div class="row" id="imageGallery">
                        <!-- Uploaded images will appear here -->
                    </div>
                </div>
            </div>
            
            <!-- Image Management Section -->
            <div id="imageManagement" class="section-editor mt-4">
                <h2 class="section-title"><i class="fas fa-images"></i> Manage Uploaded Images</h2>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Uploaded Images</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="refreshGallery">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                    <div id="imageGalleryContainer">
                        <div class="row g-3" id="imageGalleryGrid">
                            <!-- Images will be loaded here via JavaScript -->
                        </div>
                        <div class="col-12 text-center py-5" id="loadingGallery">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading images...</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rename Modal -->
            <div class="modal fade" id="renameModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Rename Image</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="newFileName" class="form-label">New Filename (without extension)</label>
                                <input type="text" class="form-control" id="newFileName">
                                <input type="hidden" id="originalFileName">
                                <div class="form-text">Only letters, numbers, hyphens, and underscores are allowed.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmRename">Rename</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Confirm Deletion</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete <strong id="fileNameToDelete"></strong>?</p>
                            <p class="text-danger">This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Preview Container -->
    <div class="preview-container" id="previewContainer">
        <div class="preview-header">
            <h3>Live Preview</h3>
            <button class="close-preview">&times;</button>
        </div>
        <div id="previewContent" style="padding: 2rem;"></div>
    </div>
    
    <button class="toggle-preview" id="togglePreview">
        <i class="fas fa-eye"></i>
    </button>
    
    <script>
        // Toggle sidebar and menu
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const menuToggle = document.getElementById('menuToggle');
        const actionsMenu = document.getElementById('actionsMenu');
        const mainContent = document.querySelector('.main-content');
        
        // Toggle sidebar
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                sidebar.classList.toggle('visible');
                actionsMenu.classList.remove('visible');
                // Only adjust margin on larger screens
                if (window.innerWidth > 768 && window.innerWidth <= 992) {
                    mainContent.style.marginLeft = sidebar.classList.contains('visible') ? '250px' : '0';
                }
            });
        }
        
        // Toggle actions menu
        if (menuToggle) {
            menuToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                actionsMenu.classList.toggle('visible');
                sidebar.classList.remove('visible');
            });
        }
        
        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !e.target.matches('.sidebar-toggle')) {
                sidebar.classList.remove('visible');
                if (window.innerWidth <= 992) {
                    mainContent.style.marginLeft = '0';
                }
            }
            
            // Close actions menu when clicking outside
            if (!actionsMenu.contains(e.target) && !e.target.matches('.menu-toggle')) {
                actionsMenu.classList.remove('visible');
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 992) {
                sidebar.classList.remove('visible');
                sidebar.style.transform = '';
                mainContent.style.marginLeft = '250px';
            } else if (window.innerWidth > 768) {
                mainContent.style.marginLeft = sidebar.classList.contains('visible') ? '250px' : '0';
            } else {
                mainContent.style.marginLeft = '0';
            }
        });
        
        // Current language
        let currentLang = 'en';
        
        // Language switcher
        document.querySelectorAll('.lang-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                // Update active state
                document.querySelectorAll('.lang-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update current language
                currentLang = this.getAttribute('data-lang');
                
                // Reload data with new language
                portfolioData = await loadPortfolioData();
                if (portfolioData) {
                    loadFormData();
                    // Show success message
                    const toast = document.createElement('div');
                    toast.className = 'position-fixed bottom-0 end-0 m-3 p-3 bg-success text-white rounded';
                    toast.style.zIndex = '1100';
                    toast.textContent = `Language changed to ${currentLang.toUpperCase()}`;
                    document.body.appendChild(toast);
                    
                    // Remove toast after 3 seconds
                    setTimeout(() => {
                        toast.remove();
                    }, 3000);
                }
            });
        });
        
        async function loadPortfolioData() {
            try {
                const response = await fetch(`../allovirtuelContent_${currentLang}.json`);
                if (!response.ok) {
                    // If the requested language file doesn't exist, fall back to English
                    if (currentLang !== 'en') {
                        currentLang = 'en';
                        return await loadPortfolioData();
                    }
                    throw new Error('Failed to load portfolio data');
                }
                return await response.json();
            } catch (error) {
                console.error('Error loading portfolio data:', error);
                return null;
            }
        }
        // At the top level of your script (replace the old portfolioData declaration)
        let portfolioData;

        // When your page loads or when you need to load the data
        async function initialize() {
            portfolioData = await loadPortfolioData();
            if (portfolioData) {
                loadFormData();
                // Any other initialization code you have
            } else {
                alert('Failed to load portfolio data');
            }
        }

        // Call initialize when your page loads
        document.addEventListener('DOMContentLoaded', initialize);
        
        // DOM Elements
        const previewContainer = document.getElementById('previewContainer');
        const togglePreview = document.getElementById('togglePreview');
        const closePreview = document.querySelector('.close-preview');
        const previewContent = document.getElementById('previewContent');
        const saveBtn = document.getElementById('saveBtn');
        const resetBtn = document.getElementById('resetBtn');
        const sendToServerBtn = document.getElementById('sendToServerBtn');
        
        
        // Navigation links
        const navLinks = document.querySelectorAll('.nav-links a');
        
        // Form elements
        // General Settings
        const companyName = document.getElementById('companyName');
        const logo = document.getElementById('logo');
        const primaryColor = document.getElementById('primaryColor');
        const secondaryColor = document.getElementById('secondaryColor');
        const backgroundColor = document.getElementById('backgroundColor');
        const textColor = document.getElementById('textColor');
        const whatsapp = document.getElementById('whatsapp');
        const email = document.getElementById('email');
        const linkedin = document.getElementById('linkedin');
        const github = document.getElementById('github');
        const instagram = document.getElementById('instagram');
        
        // Hero Section
        const heroTitle = document.getElementById('heroTitle');
        const heroSubtitle = document.getElementById('heroSubtitle');
        const heroDescription = document.getElementById('heroDescription');
        const heroCtaText = document.getElementById('heroCtaText');
        const heroCtaLink = document.getElementById('heroCtaLink');
        const heroImageEmoji = document.getElementById('heroImageEmoji');
        
        // About Section
        const aboutTitle = document.getElementById('aboutTitle');
        const aboutContentItems = document.getElementById('aboutContentItems');
        const addAboutContent = document.getElementById('addAboutContent');
        const aboutImage = document.getElementById('aboutImage');
        
        // Services Section
        const servicesTitle = document.getElementById('servicesTitle');
        const servicesSubtitle = document.getElementById('servicesSubtitle');
        const servicePackages = document.getElementById('servicePackages');
        const addServicePackage = document.getElementById('addServicePackage');
        
        // Process Section
        const processTitle = document.getElementById('processTitle');
        const processSubtitle = document.getElementById('processSubtitle');
        const processSteps = document.getElementById('processSteps');
        const addProcessStep = document.getElementById('addProcessStep');
        
        // Projects Section
        const projectsTitle = document.getElementById('projectsTitle');
        const projectItems = document.getElementById('projectItems');
        const addProject = document.getElementById('addProject');
        
        // FAQ Section
        const faqTitle = document.getElementById('faqTitle');
        const faqItems = document.getElementById('faqItems');
        const addFaqItem = document.getElementById('addFaqItem');
        
        // Contact Section
        const contactTitle = document.getElementById('contactTitle');
        const contactChannels = document.getElementById('contactChannels');
        
        // Initialize the form with current data
        function loadFormData() {
            // General Settings
            companyName.value = portfolioData.settings.companyName;
            logo.value = portfolioData.settings.logo;
            primaryColor.value = portfolioData.settings.theme.primaryColor;
            secondaryColor.value = portfolioData.settings.theme.secondaryColor;
            backgroundColor.value = portfolioData.settings.theme.backgroundColor;
            textColor.value = portfolioData.settings.theme.textColor;
            whatsapp.value = portfolioData.settings.contact.whatsapp;
            email.value = portfolioData.settings.contact.email;
            linkedin.value = portfolioData.settings.contact.linkedin;
            github.value = portfolioData.settings.contact.github;
            instagram.value = portfolioData.settings.contact.instagram;
            
            // Hero Section
            heroTitle.value = portfolioData.hero.title;
            heroSubtitle.value = portfolioData.hero.subtitle;
            heroDescription.value = portfolioData.hero.description;
            heroCtaText.value = portfolioData.hero.cta.text;
            heroCtaLink.value = portfolioData.hero.cta.href;
            heroImageEmoji.value = portfolioData.hero.image.emoji;
            
            // About Section
            aboutTitle.value = portfolioData.about.title;
            renderAboutContentItems();
            aboutImage.value = portfolioData.about.image;
            
            // Services Section
            servicesTitle.value = portfolioData.services.title;
            servicesSubtitle.value = portfolioData.services.subtitle;
            renderServicePackages();
            
            // Process Section
            processTitle.value = portfolioData.process.title;
            processSubtitle.value = portfolioData.process.subtitle;
            renderProcessSteps();
            
            // Projects Section
            projectsTitle.value = portfolioData.projects.title;
            renderProjectItems();
            
            // FAQ Section
            faqTitle.value = portfolioData.faq.title;
            renderFaqItems();
            
            // Contact Section
            contactTitle.value = portfolioData.contact.title;
             renderContactChannels();
        }
        
        // Render dynamic content items
        function renderAboutContentItems() {
            aboutContentItems.innerHTML = '';
            portfolioData.about.content.forEach((content, index) => {
                const itemDiv = document.createElement('div');
                itemDiv.className = 'form-array-item';
                itemDiv.innerHTML = `
                    <label>Paragraph ${index + 1}</label>
                    <textarea class="form-control about-content" data-index="${index}">${content}</textarea>
                    <button class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                aboutContentItems.appendChild(itemDiv);
            });
        }

        function renderServicePackages() {
            servicePackages.innerHTML = '';
            portfolioData.services.packages.forEach((pkg, index) => {
                const pkgDiv = document.createElement('div');
                pkgDiv.className = 'form-array-item';
                pkgDiv.innerHTML = `
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Package Title</label>
                            <input type="text" class="form-control package-title" data-index="${index}" value="${pkg.title}">
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="text" class="form-control package-icon" data-index="${index}" value="${pkg.icon}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control package-description" data-index="${index}">${pkg.description}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Features (one per line)</label>
                        <textarea class="form-control package-features" data-index="${index}">${pkg.features.join('\n')}</textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" class="package-popular" data-index="${index}" ${pkg.popular ? 'checked' : ''}>
                            Mark as Popular
                        </label>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Button Text</label>
                            <input type="text" class="form-control package-cta-text" data-index="${index}" value="${pkg.cta.text}">
                        </div>
                        <div class="form-group">
                            <label>Button Link</label>
                            <input type="text" class="form-control package-cta-link" data-index="${index}" value="${pkg.cta.href}">
                        </div>
                    </div>
                    <button class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                servicePackages.appendChild(pkgDiv);
            });
        }

        function renderProcessSteps() {
            processSteps.innerHTML = '';
            portfolioData.process.steps.forEach((step, index) => {
                const stepDiv = document.createElement('div');
                stepDiv.className = 'form-array-item';
                stepDiv.innerHTML = `
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Step Number</label>
                            <input type="number" class="form-control step-number" data-index="${index}" value="${step.number}">
                        </div>
                        <div class="form-group">
                            <label>Icon Class (Font Awesome)</label>
                            <input type="text" class="form-control step-icon" data-index="${index}" value="${step.icon}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" class="form-control step-title" data-index="${index}" value="${step.title}">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control step-description" data-index="${index}">${step.description}</textarea>
                    </div>
                    <button class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                processSteps.appendChild(stepDiv);
            });
        }

        function renderProjectItems() {
            projectItems.innerHTML = '';
            portfolioData.projects.items.forEach((project, index) => {
                const projectDiv = document.createElement('div');
                projectDiv.className = 'form-array-item';
                projectDiv.innerHTML = `
                    <div class="form-group">
                        <label>Project Title</label>
                        <input type="text" class="form-control project-title" data-index="${index}" value="${project.title}">
                    </div>
                    <div class="form-group">
                        <label>Icon</label>
                        <input type="text" class="form-control project-icon" data-index="${index}" value="${project.icon}">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control project-description" data-index="${index}">${project.description}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Links (one per line, format: "Text|URL")</label>
                        <textarea class="form-control project-links" data-index="${index}">${project.links.map(link => `${link.text}|${link.href}`).join('\n')}</textarea>
                    </div>
                    <button class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                projectItems.appendChild(projectDiv);
            });
        }

        function renderFaqItems() {
            faqItems.innerHTML = '';
            portfolioData.faq.items.forEach((item, index) => {
                const faqDiv = document.createElement('div');
                faqDiv.className = 'form-array-item';
                faqDiv.innerHTML = `
                    <div class="form-group">
                        <label>Question</label>
                        <input type="text" class="form-control faq-question" data-index="${index}" value="${item.question}">
                    </div>
                    <div class="form-group">
                        <label>Answer</label>
                        <textarea class="form-control faq-answer" data-index="${index}">${item.answer}</textarea>
                    </div>
                    <button class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                faqItems.appendChild(faqDiv);
            });
        }

        function renderContactChannels() {
            contactChannels.innerHTML = '';
            
            if (!portfolioData.contact.channels || !portfolioData.contact.channels.items) {
                return; // Exit if no channels data exists
            }
            
            portfolioData.contact.channels.items.forEach((item, index) => {
                const formGroup = document.createElement('div');
                formGroup.className = 'form-array-item form-group mb-5 border-bottom pb-4 position-relative';

                formGroup.innerHTML = `
                    
                    <h5 class="d-flex align-items-center mb-3">
                        <span class="contact-icon-preview me-2" id="preview-${index}">
                            ${item.iconWithClass ? `<i class="${item.icon}"></i>` : `<span>${item.icon || '@'}</span>`}
                        </span>
                        ${item.label || 'Unnamed Field'}
                    </h5>

                    <div class="row g-3">
                    <!-- Type -->
                    <div class="col-md-4">
                        <label for="type-${index}" class="form-label">Type</label>
                        <input type="text" id="type-${index}" class="form-control channelType" value="${item.type || ''}" >
                    </div>

                    <!-- Label -->
                    <div class="col-md-4">
                        <label for="label-${index}" class="form-label">Label</label>
                        <input type="text" id="label-${index}" class="form-control channelLabel" value="${item.label || ''}">
                    </div>

                    <!-- Href -->
                    <div class="col-md-4">
                        <label for="href-${index}" class="form-label">href (Link)</label>
                        <input type="text" id="href-${index}" class="form-control channelHref" value="${item.href || ''}">
                    </div>

                    <!-- Value -->
                    <div class="col-md-6">
                        <label for="value-${index}" class="form-label">Value</label>
                        <input type="text" id="value-${index}" class="form-control channelValue" value="${item.value || ''}">
                    </div>

                    <!-- Icon With Class Toggle -->
                    <div class="col-md-6">
                        <label class="form-label">Icon Type</label>
                        <select class="form-select channelIconWithClass" id="iconWithClass-${index}">
                        <option value="true" ${item.iconWithClass ? 'selected' : ''}>Class CSS</option>
                        <option value="false" ${!item.iconWithClass ? 'selected' : ''}>Emoji ou Character</option>
                        </select>
                    </div>

                    <!-- Icon -->
                    <div class="col-md-12">
                        <label for="icon-${index}" class="form-label">Icon</label>
                        <input type="text" id="icon-${index}" class="form-control channelIcon" value="${item.icon || ''}" placeholder="${item.iconWithClass ? 'e.g., fas fa-phone' : 'e.g., @'}">
                    </div>
                    <button class="remove-btn" data-index="${index}">
                        <i class="fas fa-times"></i>
                    </button>
                    </div>
                `;
                contactChannels.appendChild(formGroup);
            });
        }

        // Add contact channel function
        function addContactChannel() {
            if (!portfolioData.contact.channels) {
                portfolioData.contact.channels = { items: [] };
            }
            if (!portfolioData.contact.channels.items) {
                portfolioData.contact.channels.items = [];
            }
            
            portfolioData.contact.channels.items.push({
                type: 'email',
                label: 'Email',
                href: 'mailto:',
                value: 'contact@example.com',
                icon: 'fas fa-envelope',
                iconWithClass: true
            });
            
            renderContactChannels();
        }
        
        // Add event listener for add contact channel button
        document.getElementById('addContactChannel').addEventListener('click', addContactChannel);
        
        // Add item functions
        addAboutContent.addEventListener('click', () => {
            portfolioData.about.content.push('');
            renderAboutContentItems();
        });

        addServicePackage.addEventListener('click', () => {
            portfolioData.services.packages.push({
                id: `package-${Date.now()}`,
                title: 'New Package',
                icon: '⭐',
                description: 'Package description',
                features: ['Feature 1', 'Feature 2'],
                popular: false,
                cta: {
                    text: 'Get Started',
                    href: '#'
                }
            });
            renderServicePackages();
        });

        addProcessStep.addEventListener('click', () => {
            portfolioData.process.steps.push({
                number: portfolioData.process.steps.length + 1,
                title: 'New Step',
                description: 'Step description',
                icon: 'fas fa-question-circle'
            });
            renderProcessSteps();
        });

        addProject.addEventListener('click', () => {
            portfolioData.projects.items.push({
                title: 'New Project',
                description: 'Project description',
                icon: '🆕',
                links: [
                    { text: 'Demo', href: '#' },
                    { text: 'GitHub', href: '#' }
                ]
            });
            renderProjectItems();
        });

        addFaqItem.addEventListener('click', () => {
            portfolioData.faq.items.push({
                question: 'New question?',
                answer: 'Answer to the question.'
            });
            renderFaqItems();
        });
        
        // Image Upload Functionality
        const imageUploadInput = document.getElementById('imageUploadInput');
        const uploadButton = document.getElementById('uploadButton');
        const uploadProgress = document.getElementById('uploadProgress');
        const progressBar = uploadProgress.querySelector('.progress-bar');
        const uploadStatus = document.getElementById('uploadStatus');
        const imageGallery = document.getElementById('imageGallery');
        
        // Track existing filenames to check for duplicates
        const existingFilenames = new Set();

        uploadButton.addEventListener('click', async () => {
            const files = imageUploadInput.files;
            if (files.length === 0) {
                showStatus('Please select at least one image to upload', 'warning');
                return;
            }

            // Check for duplicate filenames
            for (let i = 0; i < files.length; i++) {
                const fileName = files[i].name;
                const fileExists = await isFileExists(fileName);
                if (fileExists) {
                    showStatus(`Duplicate filenames. Please rename the files and try again.`, 'warning');
                    return;
                }
            }

            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('images[]', files[i]);
            }

            try {
                uploadProgress.style.display = 'block';
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', 0);
                uploadButton.disabled = true;

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'upload.php', true);

                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = Math.round((e.loaded / e.total) * 100);
                        progressBar.style.width = percentComplete + '%';
                        progressBar.setAttribute('aria-valuenow', percentComplete);
                        progressBar.textContent = percentComplete + '%';
                    }
                };

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            showStatus('Images uploaded successfully!', 'success');
                            // Refresh the image gallery
                            loadUploadedImages();
                        } else {
                            showStatus('Error: ' + (response.message || 'Upload failed'), 'danger');
                        }
                    } else {
                        showStatus('Error: ' + xhr.statusText, 'danger');
                    }
                    uploadProgress.style.display = 'none';
                    uploadButton.disabled = false;
                };

                xhr.onerror = () => {
                    showStatus('Error: Upload failed. Please try again.', 'danger');
                    uploadProgress.style.display = 'none';
                    uploadButton.disabled = false;
                };

                xhr.send(formData);

            } catch (error) {
                console.error('Upload error:', error);
                showStatus('Error: ' + error.message, 'danger');
                uploadProgress.style.display = 'none';
                uploadButton.disabled = false;
            }
        });

        function showStatus(message, type = 'info') {
            uploadStatus.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>`;
        }

        function loadUploadedImages() {
            // This would typically fetch the list of uploaded images from your server
            // For now, we'll just clear and update with the newly uploaded files
            imageGallery.innerHTML = '';
            
            const files = imageUploadInput.files;
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();
                    
                    reader.onload = (e) => {
                        const col = document.createElement('div');
                        col.className = 'col-6 col-md-4 col-lg-3 mb-4';
                        col.innerHTML = `
                            <div class="card">
                                <img src="${e.target.result}" class="card-img-top" alt="${file.name}">
                                <div class="card-body p-2">
                                    <p class="card-text small text-truncate" title="${file.name}">${file.name}</p>
                                    <p class="card-text small text-muted">${formatFileSize(file.size)}</p>
                                </div>
                            </div>
                        `;
                        imageGallery.appendChild(col);
                    };
                    
                    reader.readAsDataURL(file);
                }
            } else {
                imageGallery.innerHTML = '<div class="col-12"><p class="text-muted">No images uploaded yet.</p></div>';
            }
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Check if filename already exists in the images folder
        async function isFileExists(filename) {
            try {
                const response = await fetch(`check_file.php?filename=${encodeURIComponent(filename)}`);
                const data = await response.json();
                return data.exists;
            } catch (error) {
                console.error('Error checking file:', error);
                return false;
            }
        }
        
        // Update file input styling based on duplicate status
        function updateFileInputStyle() {
            const files = imageUploadInput.files;
            const fileList = Array.from(files);
            
            // Reset existing filenames
            existingFilenames.clear();
            
            // First pass: collect all filenames to check for duplicates
            fileList.forEach(file => {
                existingFilenames.add(file.name.toLowerCase());
            });
            
            // Second pass: update UI
            fileList.forEach((file, index) => {
                const label = document.querySelector(`label[for="file-${index}"]`);
                if (label) {
                    label.style.border = '2px solid #198754';
                    label.style.borderRadius = '4px';
                    label.style.padding = '5px';
                    label.style.display = 'inline-block';
                    label.style.margin = '5px 0';
                }
            });
        }
        
        // Preview images before upload and check for duplicates
        imageUploadInput.addEventListener('change', async () => {
            loadUploadedImages();
            updateFileInputStyle();
            
            // Add filename display with duplicate status
            const fileList = document.getElementById('fileList') || document.createElement('div');
            fileList.id = 'fileList';
            fileList.innerHTML = '';
            
            // Process files sequentially to check for existing files
            for (let i = 0; i < imageUploadInput.files.length; i++) {
                const file = imageUploadInput.files[i];
                const fileExists = await isFileExists(file.name);
                
                const fileLabel = document.createElement('div');
                fileLabel.id = `file-${i}`;
                fileLabel.className = 'mb-2';
                
                let statusClass = 'text-success';
                let statusIcon = 'fa-check-circle';
                let statusBadge = '';
                
                if (fileExists) {
                    statusClass = 'text-warning';
                    statusIcon = 'fa-exclamation-circle';
                    statusBadge = '<span class="badge bg-warning ms-2">File exists</span>';
                }
                
                fileLabel.innerHTML = `
                    <div class="d-flex align-items-center">
                        <button class="btn btn-sm btn-outline-danger me-2 delete-file" data-index="${i}" title="Remove file">
                            <i class="fas fa-times"></i>
                        </button>
                        <label for="file-${i}" class="${statusClass} mb-0">
                            <i class="fas ${statusIcon}"></i>
                            ${file.name} (${formatFileSize(file.size)})
                            ${statusBadge}
                        </label>
                    </div>
                `;
                fileList.appendChild(fileLabel);
            }
            
            // Insert after the file input
            if (imageUploadInput.files.length > 0) {
                imageUploadInput.parentNode.insertBefore(fileList, imageUploadInput.nextSibling);
                
                // Add event listeners to delete buttons
                document.querySelectorAll('.delete-file').forEach(button => {
                    button.addEventListener('click', function() {
                        const index = parseInt(this.getAttribute('data-index'));
                        removeFileFromInput(index);
                    });
                });
            }
        });

        // Function to remove a file from the file input
        function removeFileFromInput(index) {
            const dt = new DataTransfer();
            const input = imageUploadInput;
            const { files } = input;
            
            // Add all files except the one to be removed
            for (let i = 0; i < files.length; i++) {
                if (index !== i) {
                    dt.items.add(files[i]);
                }
            }
            
            // Update the files in the input
            input.files = dt.files;
            
            // Trigger the change event to update the UI
            const event = new Event('change');
            input.dispatchEvent(event);
        }
        
        // Image Management Functions
        async function loadImageGallery() {
            const container = document.getElementById('imageGalleryContainer');
            const loading = document.getElementById('loadingGallery');
            const grid = document.getElementById('imageGalleryGrid');
            
            try {
                if (loading) loading.style.display = 'block';
                if (grid) grid.innerHTML = '';
                
                // Fetch the list of images from the server
                const response = await fetch('get_images.php');
                const data = await response.json();
                
                if (data.success && data.images.length > 0) {
                    if (grid) {
                        data.images.forEach((image, index) => {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3 mb-4';
                            col.innerHTML = `
                                <div class="card h-100">
                                    <img src="../${image.path}" class="card-img-top" alt="${image.name}" style="height: 150px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <p class="card-text small text-truncate" title="${image.name}">${image.name}</p>
                                        <p class="card-text small text-muted">${formatFileSize(image.size)}</p>
                                        <div class="d-flex justify-content-between">
                                            <button class="btn btn-sm btn-outline-primary rename-btn" data-filename="${image.name}" data-fullpath="${image.path}">
                                                <i class="fas fa-edit"></i> Rename
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger delete-btn" data-filename="${image.name}" data-fullpath="${image.path}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            grid.appendChild(col);
                        });
                    }
                    
                    // Add event listeners for the new buttons
                    addImageActionListeners();
                } else {
                    if (grid) {
                        grid.innerHTML = `
                            <div class="col-12 text-center py-5">
                                <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                <p>No images found in the images folder.</p>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error loading images:', error);
                if (grid) {
                    grid.innerHTML = `
                        <div class="col-12 text-center py-5">
                            <i class="fas fa-exclamation-triangle text-danger mb-3"></i>
                            <p>Error loading images. Please try again.</p>
                        </div>
                    `;
                }
            } finally {
                if (loading) loading.style.display = 'none';
            }
        }
        
        function addImageActionListeners() {
            // Rename button click
            document.querySelectorAll('.rename-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const filename = btn.getAttribute('data-filename');
                    const fullPath = btn.getAttribute('data-fullpath');
                    const modal = new bootstrap.Modal(document.getElementById('renameModal'));
                    
                    // Set current filename in the modal
                    const nameWithoutExt = filename.replace(/\.[^/.]+$/, '');
                    document.getElementById('newFileName').value = nameWithoutExt;
                    document.getElementById('originalFileName').value = fullPath;
                    
                    modal.show();
                });
            });
            
            // Delete button click
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const filename = btn.getAttribute('data-filename');
                    const fullPath = btn.getAttribute('data-fullpath');
                    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                    
                    document.getElementById('fileNameToDelete').textContent = filename;
                    document.getElementById('confirmDelete').setAttribute('data-fullpath', fullPath);
                    
                    modal.show();
                });
            });
        }
        
        // Handle rename confirmation
        document.getElementById('confirmRename').addEventListener('click', async () => {
            const newName = document.getElementById('newFileName').value.trim();
            const originalPath = document.getElementById('originalFileName').value;
            const modal = bootstrap.Modal.getInstance(document.getElementById('renameModal'));
            
            if (!newName) {
                alert('Please enter a new filename');
                return;
            }
            
            // Validate filename (only letters, numbers, hyphens, and underscores)
            if (!/^[a-zA-Z0-9\-_]+$/.test(newName)) {
                alert('Filename can only contain letters, numbers, hyphens, and underscores');
                return;
            }
            
            try {
                const response = await fetch('rename_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `oldPath=${encodeURIComponent(originalPath)}&newName=${encodeURIComponent(newName)}`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showStatus('Image renamed successfully!', 'success');
                    loadImageGallery();
                    modal.hide();
                } else {
                    showStatus('Error: ' + (result.message || 'Failed to rename image'), 'danger');
                }
            } catch (error) {
                console.error('Error renaming image:', error);
                showStatus('Error: Failed to rename image', 'danger');
            }
        });
        
        // Handle delete confirmation
        document.getElementById('confirmDelete').addEventListener('click', async function() {
            const fullPath = this.getAttribute('data-fullpath');
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            
            try {
                const response = await fetch('delete_image.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `path=${encodeURIComponent(fullPath)}`
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showStatus('Image deleted successfully!', 'success');
                    loadImageGallery();
                } else {
                    showStatus('Error: ' + (result.message || 'Failed to delete image'), 'danger');
                }
            } catch (error) {
                console.error('Error deleting image:', error);
                showStatus('Error: Failed to delete image', 'danger');
            } finally {
                modal.hide();
            }
        });
        
        // Refresh gallery button
        document.getElementById('refreshGallery').addEventListener('click', loadImageGallery);
        
        // Load the image gallery when the page loads
        document.addEventListener('DOMContentLoaded', () => {
            loadImageGallery();
        });
        
        // Remove item functions
            document.addEventListener('click', e => {
                if (e.target.matches('.remove-btn') || e.target.matches('.remove-btn *')) {
                    const btn = e.target.closest('.remove-btn'); // find nearest button
                    if (!btn) return; // click was not inside a remove button

                    const index = parseInt(btn.getAttribute('data-index'), 10);
                    const section = btn.closest('.section-editor');
                    if (!section) return;
                    const parentId = section.id;
                    
                    switch(parentId) {
                        case 'about':
                            portfolioData.about.content.splice(index, 1);
                            renderAboutContentItems();
                            break;
                        case 'contact':
                                portfolioData.contact.channels.items.splice(index, 1);
                                renderContactChannels();
                            break;
                        case 'services':
                            portfolioData.services.packages.splice(index, 1);
                            renderServicePackages();
                            break;
                        case 'process':
                            portfolioData.process.steps.splice(index, 1);
                            renderProcessSteps();
                            break;
                        case 'projects':
                            portfolioData.projects.items.splice(index, 1);
                            renderProjectItems();
                            break;
                        case 'faq':
                            portfolioData.faq.items.splice(index, 1);
                            renderFaqItems();
                            break;
                        
                    }
                }});
            // });
        // }

        // Save form data to portfolioData object
        function saveFormData() {
            // General Settings
            portfolioData.settings.companyName = companyName.value;
            portfolioData.settings.logo = logo.value;
            portfolioData.settings.theme.primaryColor = primaryColor.value;
            portfolioData.settings.theme.secondaryColor = secondaryColor.value;
            portfolioData.settings.theme.backgroundColor = backgroundColor.value;
            portfolioData.settings.theme.textColor = textColor.value;
            portfolioData.settings.contact.whatsapp = whatsapp.value;
            portfolioData.settings.contact.email = email.value;
            portfolioData.settings.contact.linkedin = linkedin.value;
            portfolioData.settings.contact.github = github.value;
            portfolioData.settings.contact.instagram = instagram.value;
            
            // Hero Section
            portfolioData.hero.title = heroTitle.value;
            portfolioData.hero.subtitle = heroSubtitle.value;
            portfolioData.hero.description = heroDescription.value;
            portfolioData.hero.cta.text = heroCtaText.value;
            portfolioData.hero.cta.href = heroCtaLink.value;
            portfolioData.hero.image.emoji = heroImageEmoji.value;
            
            // About Section
            portfolioData.about.title = aboutTitle.value;
            portfolioData.about.content = Array.from(document.querySelectorAll('.about-content')).map(el => el.value);
            portfolioData.about.image = aboutImage.value;
            
            // Services Section
            portfolioData.services.title = servicesTitle.value;
            portfolioData.services.subtitle = servicesSubtitle.value;
            portfolioData.services.packages = Array.from(document.querySelectorAll('.form-array-item')).map((item, index) => {
                if (item.closest('#servicePackages')) {
                    return {
                        id: portfolioData.services.packages[index]?.id || `package-${index}`,
                        title: item.querySelector('.package-title').value,
                        icon: item.querySelector('.package-icon').value,
                        description: item.querySelector('.package-description').value,
                        features: item.querySelector('.package-features').value.split('\n').filter(f => f.trim() !== ''),
                        popular: item.querySelector('.package-popular').checked,
                        cta: {
                            text: item.querySelector('.package-cta-text').value,
                            href: item.querySelector('.package-cta-link').value
                        }
                    };
                }
            }).filter(Boolean);
            
            // Process Section
            portfolioData.process.title = processTitle.value;
            portfolioData.process.subtitle = processSubtitle.value;
            portfolioData.process.steps = Array.from(document.querySelectorAll('.form-array-item')).map((item, index) => {
                if (item.closest('#processSteps')) {
                    return {
                        number: parseInt(item.querySelector('.step-number').value),
                        title: item.querySelector('.step-title').value,
                        description: item.querySelector('.step-description').value,
                        icon: item.querySelector('.step-icon').value
                    };
                }
            }).filter(Boolean);
            
            // Projects Section
            portfolioData.projects.title = projectsTitle.value;
            portfolioData.projects.items = Array.from(document.querySelectorAll('.form-array-item')).map((item, index) => {
                if (item.closest('#projectItems')) {
                    const links = item.querySelector('.project-links').value.split('\n')
                        .filter(l => l.trim() !== '')
                        .map(l => {
                            const [text, href] = l.split('|');
                            return { text: text?.trim(), href: href?.trim() };
                        });
                    
                    return {
                        title: item.querySelector('.project-title').value,
                        icon: item.querySelector('.project-icon').value,
                        description: item.querySelector('.project-description').value,
                        links: links
                    };
                }
            }).filter(Boolean);
            
            // FAQ Section
            portfolioData.faq.title = faqTitle.value;
            portfolioData.faq.items = Array.from(document.querySelectorAll('.form-array-item')).map((item, index) => {
                if (item.closest('#faqItems')) {
                    return {
                        question: item.querySelector('.faq-question').value,
                        answer: item.querySelector('.faq-answer').value
                    };
                }
            }).filter(Boolean);
            
            // Contact Section
            portfolioData.contact.title = contactTitle.value;
             
            // Update contact information from the form fields
            portfolioData.contact.channels.items = Array.from(document.querySelectorAll('.form-array-item')).map((item, index) => {
                if (item.closest('#contactChannels')) {
                    return {
                        type: item.querySelector('.channelType').value,
                        icon: item.querySelector('.channelIcon').value,
                        iconWithClass: item.querySelector('.channelIconWithClass').checked,
                        label: item.querySelector('.channelLabel').value,
                        value: item.querySelector('.channelValue').value,
                        href: item.querySelector('.channelHref').value
                    };
                }
            }).filter(Boolean);
            
            alert('Changes saved successfully!');
        }

        async function savePortfolioData() {
            try {
                saveFormData();
                
                // Send data to server with language parameter
                const response = await fetch('update_json.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ...portfolioData,
                        _language: currentLang  // Add current language to the data
                    })
                });
                
                const result = await response.json();
                
                if (result.status === 'success') {
                    alert(`Data saved successfully for ${currentLang.toUpperCase()}!`);
                } else {
                    alert('Error saving data: ' + result.message);
                }
            } catch (error) {
                console.error('Error saving portfolio data:', error);
                alert('Error saving data');
            }
        }

        

        // Generate HTML preview
        function generatePreview() {
            // This would generate a complete HTML preview of the portfolio
            // For brevity, we'll just show a JSON representation
            previewContent.innerHTML = `
                <h3>Current Portfolio Data</h3>
                <pre>${JSON.stringify(portfolioData, null, 2)}</pre>
            `;
        }

        // Function to download JSON
        function downloadJSON() {
            // First save the current form data to ensure we have the latest changes
            saveFormData();
            
            // Create a blob from the portfolioData
            const dataStr = JSON.stringify(portfolioData, null, 2);
            const dataBlob = new Blob([dataStr], { type: 'application/json' });
            
            // Create a download link
            const downloadLink = document.createElement('a');
            downloadLink.href = URL.createObjectURL(dataBlob);
            downloadLink.download = 'portfolio-data-' + new Date().toISOString().split('T')[0] + '.json';
            
            // Trigger download
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        }
        
        // Event Listeners
        document.getElementById('downloadJsonBtn').addEventListener('click', downloadJSON);

        togglePreview.addEventListener('click', (e) => {
            e.stopPropagation();
            const wasOpen = previewContainer.classList.contains('open');
            previewContainer.classList.toggle('open');
            if (!wasOpen) {
                generatePreview();
            }
        });

        // Close preview when clicking outside
        document.addEventListener('click', (e) => {
            if (previewContainer.classList.contains('open') && 
                !previewContainer.contains(e.target) && 
                e.target !== togglePreview) {
                previewContainer.classList.remove('open');
            }
        });

        // Stop propagation for clicks inside the preview
        previewContainer.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        closePreview.addEventListener('click', () => {
            previewContainer.classList.remove('open');
        });

        sendToServerBtn.addEventListener('click', () => {
            if(confirm('Are you sure you want to send this data to the server?')){
                savePortfolioData();
            }
        });

        saveBtn.addEventListener('click', () => {
            saveFormData();
            
        });


        function reset() {
            if (confirm('This will overwrite any unsaved changes.')) {
                initialize();
                loadFormData();
            }
        }

        resetBtn.addEventListener('click', reset);

        // Navigation
        navLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                navLinks.forEach(l => l.classList.remove('active'));
                this.classList.add('active');
                
                const targetId = this.getAttribute('href').substring(1);
                document.getElementById(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Initialize the form
        loadFormData();
        

        // Initialize Bootstrap ScrollSpy
        const scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '.sidebar',
            offset: 100
        });

        // Update active nav items on scroll
        document.body.addEventListener('activate.bs.scrollspy', function (e) {
            const activeNavItem = document.querySelector('.sidebar .nav-link.active');
            if (activeNavItem) {
                // Remove active class from all nav items
                document.querySelectorAll('.sidebar .nav-link').forEach(item => {
                    item.classList.remove('active');
                });
                // Add active class to current nav item
                activeNavItem.classList.add('active');
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('.sidebar a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 20,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>
</html>