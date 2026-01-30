<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Swipeable Project Cards</title>
    <!-- Add Hammer.js for touch gestures -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/hammer.js/2.0.8/hammer.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0a0a0a;
            color: white;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .section {
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-titles {
            text-align: center;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 20px;
        }

        /* ===========================
        SWIPE CONTAINER
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
            cursor: grab;
            user-select: none;
            transition: transform 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            will-change: transform;
        }
        
        .cards-wrapper.grabbing {
            cursor: grabbing;
            user-select: none;
            transition: none;
        }

        .cards-wrapper.animating {
            transition: transform 0.5s cubic-bezier(0.4, 0.0, 0.2, 1);
        }

        /* ===========================
        CARTE INDIVIDUELLE
        =========================== */
        .project-card {
            position: relative;
            width: 380px;
            min-width: 380px;
            padding: 0;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(145deg, #1a1a1a, #0f0f0f);
            overflow: hidden;
            flex-shrink: 0;
            transform: translateY(0);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }

        .project-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
        }

        /* Effet de brillance au survol */
        .project-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                45deg,
                transparent,
                rgba(59,130,246,0.08),
                transparent
            );
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .project-card:hover::before {
            opacity: 1;
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

        .project-image::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                transparent 0%,
                rgba(0,0,0,0.1) 100%
            );
            z-index: 1;
        }

        .project-image img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            display: block;
            transition: transform 0.6s ease;
        }

        .project-card:hover .project-image img {
            transform: scale(1.08);
        }

        /* Icon fallback */
        .project-icon {
            font-size: 4rem;
            color: white;
            z-index: 2;
            position: relative;
        }

        /* ===========================
        CONTENU DE LA CARTE
        =========================== */
        .project-content { 
            position: relative;
            z-index: 2;
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            background: rgba(0,0,0,0.2);
        }

        .project-title { 
            position: relative;
            font-size: 1.4rem; 
            font-weight: 700; 
            margin-bottom: 12px; 
            color: #f8fafc;
            line-height: 1.3;
        }

        .project-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #1d4ed8);
            border-radius: 2px;
        }

        .project-description { 
            flex-grow: 1;
            font-size: 1rem; 
            line-height: 1.6;
            color: #cbd5e1; 
            margin-bottom: 20px;
            margin-top: 16px;
        }

        /* ===========================
        LIENS DE LA CARTE
        =========================== */
        .project-links { 
            display: flex; 
            flex-wrap: wrap;
            gap: 10px;
        }

        .project-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 10px;
            background: rgba(255,255,255,0.05);
            color: #f8fafc;
            border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s cubic-bezier(0.4, 0.0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .project-link::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .project-link:hover::before {
            opacity: 1;
        }

        .project-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59,130,246,0.3);
            border-color: #3b82f6;
        }

        .project-link i,
        .project-link span {
            position: relative;
            z-index: 1;
            transition: transform 0.3s ease;
        }

        .project-link:hover i {
            transform: translateX(3px);
        }

        /* Auto-scroll animation */
        @keyframes autoScroll {
            0% { 
                transform: translateX(0); 
            }
            100% { 
                transform: translateX(-50%); 
            }
        }

        .cards-wrapper.auto-scrolling {
            animation: autoScroll 20s linear infinite;
        }

        .cards-wrapper.auto-scrolling:hover,
        .cards-wrapper.paused {
            animation-play-state: paused;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .project-card {
                width: 320px;
                min-width: 320px;
            }

            .project-content {
                padding: 20px;
            }

            .project-title {
                font-size: 1.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .cards-wrapper.auto-scrolling {
                animation-duration: 15s;
            }
        }
    </style>
</head>
<body>
    <section id="projects" class="section">
        <div class="section-titles">
            <h2 class="section-title">Featured Projects</h2>
        </div>

        <div class="swipe-container" aria-roledescription="carousel">
            <div class="cards-wrapper" id="cardsWrapper" role="list">
                <!-- Sample project cards -->
                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🚀</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Space Explorer</h3>
                        <p class="project-description">A cutting-edge space exploration simulation that allows users to navigate through different galaxies and discover new planets with realistic physics.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>View Demo</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>GitHub</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🎨</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Creative Studio</h3>
                        <p class="project-description">A powerful digital art platform with advanced brush engines, layer management, and collaborative features for creative professionals.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>View Demo</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>Download</span>
                                <i>↓</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">⚡</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Lightning Fast</h3>
                        <p class="project-description">Ultra-fast web framework designed for modern applications with zero-config setup and lightning-fast performance optimization.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>Documentation</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>GitHub</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🌊</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Ocean Analytics</h3>
                        <p class="project-description">Advanced marine data analysis platform providing real-time ocean monitoring and predictive analytics for environmental research.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>View Demo</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>API Docs</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🧠</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Neural Network</h3>
                        <p class="project-description">Machine learning platform with intuitive visual interface for building, training, and deploying neural networks without coding.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>Try Now</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>Learn More</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Duplicate cards for infinite scroll effect -->
                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🚀</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Space Explorer</h3>
                        <p class="project-description">A cutting-edge space exploration simulation that allows users to navigate through different galaxies and discover new planets with realistic physics.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>View Demo</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>GitHub</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🎨</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Creative Studio</h3>
                        <p class="project-description">A powerful digital art platform with advanced brush engines, layer management, and collaborative features for creative professionals.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>View Demo</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>Download</span>
                                <i>↓</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">⚡</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Lightning Fast</h3>
                        <p class="project-description">Ultra-fast web framework designed for modern applications with zero-config setup and lightning-fast performance optimization.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>Documentation</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>GitHub</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="project-card" role="listitem">
                    <div class="project-image">
                        <div class="project-icon">🌊</div>
                    </div>
                    <div class="project-content">
                        <h3 class="project-title">Ocean Analytics</h3>
                        <p class="project-description">Advanced marine data analysis platform providing real-time ocean monitoring and predictive analytics for environmental research.</p>
                        <div class="project-links">
                            <a href="#" class="project-link">
                                <span>View Demo</span>
                                <i>→</i>
                            </a>
                            <a href="#" class="project-link">
                                <span>API Docs</span>
                                <i>↗</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.swipe-container');
            const wrapper = document.querySelector('.cards-wrapper');
            const cards = document.querySelectorAll('.project-card');
            
            if (!wrapper || !cards.length) return;
            
            let isDragging = false;
            let startPos = 0;
            let currentTranslate = 0;
            let prevTranslate = 0;
            let animationID = 0;
            let currentIndex = 0;
            let autoScrollTimeout;
            let isAutoScrollEnabled = true;
            
            // Calculate bounds
            const cardWidth = cards[0].offsetWidth + 18; // card width + gap
            const containerWidth = container.offsetWidth;
            const maxTranslate = 0;
            const minTranslate = -(cardWidth * cards.length - containerWidth + 50);
            
            // Initialize auto-scroll
            function startAutoScroll() {
                if (!isAutoScrollEnabled) return;
                
                wrapper.classList.remove('grabbing', 'animating');
                wrapper.classList.add('auto-scrolling');
                
                // Reset position for infinite scroll
                currentTranslate = 0;
                setWrapperTransform(0);
            }
            
            function stopAutoScroll() {
                wrapper.classList.remove('auto-scrolling');
                
                // Get current animation position
                const computedStyle = window.getComputedStyle(wrapper);
                const matrix = new WebKitCSSMatrix(computedStyle.transform);
                currentTranslate = matrix.m41;
                prevTranslate = currentTranslate;
                
                // Clear any existing timeout
                clearTimeout(autoScrollTimeout);
            }
            
            function resumeAutoScrollAfterDelay() {
                clearTimeout(autoScrollTimeout);
                autoScrollTimeout = setTimeout(() => {
                    if (!isDragging && isAutoScrollEnabled) {
                        startAutoScroll();
                    }
                }, 2000); // Resume after 2 seconds
            }
            
            // Mouse enter/leave events for hover pause
            wrapper.addEventListener('mouseenter', () => {
                if (wrapper.classList.contains('auto-scrolling')) {
                    wrapper.classList.add('paused');
                }
            });
            
            wrapper.addEventListener('mouseleave', () => {
                wrapper.classList.remove('paused');
            });
            
            // Prevent image drag and context menu
            wrapper.querySelectorAll('img').forEach(img => {
                img.addEventListener('dragstart', (e) => e.preventDefault());
            });
            
            wrapper.addEventListener('contextmenu', (e) => e.preventDefault());
            
            // Mouse events
            wrapper.addEventListener('mousedown', dragStart);
            wrapper.addEventListener('touchstart', dragStart, { passive: true });
            document.addEventListener('mouseup', dragEnd);
            document.addEventListener('mouseleave', dragEnd);
            document.addEventListener('touchend', dragEnd);
            wrapper.addEventListener('mousemove', drag);
            wrapper.addEventListener('touchmove', drag, { passive: false });
            
            function dragStart(e) {
                isDragging = true;
                isAutoScrollEnabled = false;
                
                stopAutoScroll();
                wrapper.classList.add('grabbing');
                wrapper.classList.remove('animating', 'paused');
                
                if (e.type === 'touchstart') {
                    startPos = e.touches[0].clientX;
                } else {
                    startPos = e.clientX;
                    e.preventDefault();
                }
                
                // Get current transform
                const transformValue = window.getComputedStyle(wrapper).transform;
                if (transformValue !== 'none') {
                    const matrix = new WebKitCSSMatrix(transformValue);
                    prevTranslate = matrix.m41;
                } else {
                    prevTranslate = currentTranslate;
                }
                
                cancelAnimationFrame(animationID);
            }
            
            function drag(e) {
                if (!isDragging) return;
                
                e.preventDefault();
                
                let currentPosition;
                if (e.type === 'touchmove') {
                    currentPosition = e.touches[0].clientX;
                } else {
                    currentPosition = e.clientX;
                }
                
                const deltaX = currentPosition - startPos;
                currentTranslate = prevTranslate + deltaX;
                
                // Apply bounds
                currentTranslate = Math.max(minTranslate, Math.min(maxTranslate, currentTranslate));
                
                setWrapperTransform(currentTranslate);
            }
            
            function dragEnd() {
                if (!isDragging) return;
                
                isDragging = false;
                wrapper.classList.remove('grabbing');
                wrapper.classList.add('animating');
                
                const movedBy = currentTranslate - prevTranslate;
                const threshold = 80;
                
                // Snap logic
                if (Math.abs(movedBy) > threshold) {
                    if (movedBy > 0) {
                        // Dragged right - go to previous
                        currentIndex = Math.max(0, currentIndex - 1);
                    } else {
                        // Dragged left - go to next
                        const maxIndex = Math.max(0, Math.floor(cards.length / 2) - Math.floor(containerWidth / cardWidth));
                        currentIndex = Math.min(maxIndex, currentIndex + 1);
                    }
                }
                
                // Calculate target position
                let targetTranslate = -currentIndex * cardWidth;
                targetTranslate = Math.max(minTranslate, Math.min(maxTranslate, targetTranslate));
                
                // Smooth animation to target
                animateToPosition(targetTranslate, () => {
                    isAutoScrollEnabled = true;
                    resumeAutoScrollAfterDelay();
                });
            }
            
            function animateToPosition(target, callback) {
                const start = currentTranslate;
                const distance = target - start;
                const duration = 300;
                const startTime = performance.now();
                
                function animate(currentTime) {
                    const elapsed = currentTime - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    
                    // Easing function
                    const easedProgress = 1 - Math.pow(1 - progress, 3);
                    
                    currentTranslate = start + distance * easedProgress;
                    setWrapperTransform(currentTranslate);
                    
                    if (progress < 1) {
                        animationID = requestAnimationFrame(animate);
                    } else {
                        wrapper.classList.remove('animating');
                        currentTranslate = target;
                        prevTranslate = target;
                        if (callback) callback();
                    }
                }
                
                animationID = requestAnimationFrame(animate);
            }
            
            function setWrapperTransform(translateX) {
                wrapper.style.transform = `translateX(${translateX}px)`;
            }
            
            // Handle animation end for infinite scroll reset
            wrapper.addEventListener('animationiteration', () => {
                wrapper.classList.remove('auto-scrolling');
                currentTranslate = 0;
                setWrapperTransform(0);
                
                // Small delay before restarting
                setTimeout(() => {
                    if (isAutoScrollEnabled && !isDragging) {
                        wrapper.classList.add('auto-scrolling');
                    }
                }, 50);
            });
            
            // Hammer.js setup for better touch support
            if (typeof Hammer !== 'undefined') {
                const hammer = new Hammer(wrapper);
                hammer.get('pan').set({ 
                    direction: Hammer.DIRECTION_HORIZONTAL,
                    threshold: 10
                });
                
                hammer.on('panstart', (e) => {
                    startPos = e.center.x;
                    isDragging = true;
                    isAutoScrollEnabled = false;
                    
                    stopAutoScroll();
                    wrapper.classList.add('grabbing');
                    wrapper.classList.remove('animating', 'paused');
                    
                    const transformValue = window.getComputedStyle(wrapper).transform;
                    if (transformValue !== 'none') {
                        const matrix = new WebKitCSSMatrix(transformValue);
                        prevTranslate = matrix.m41;
                    } else {
                        prevTranslate = currentTranslate;
                    }
                });
                
                hammer.on('panmove', (e) => {
                    if (!isDragging) return;
                    
                    currentTranslate = prevTranslate + e.deltaX;
                    currentTranslate = Math.max(minTranslate, Math.min(maxTranslate, currentTranslate));
                    setWrapperTransform(currentTranslate);
                });
                
                hammer.on('panend', (e) => {
                    if (!isDragging) return;
                    
                    isDragging = false;
                    wrapper.classList.remove('grabbing');
                    wrapper.classList.add('animating');
                    
                    const threshold = 80;
                    
                    if (Math.abs(e.deltaX) > threshold) {
                        if (e.deltaX > 0) {
                            currentIndex = Math.max(0, currentIndex - 1);
                        } else {
                            const maxIndex = Math.max(0, Math.floor(cards.length / 2) - Math.floor(containerWidth / cardWidth));
                            currentIndex = Math.min(maxIndex, currentIndex + 1);
                        }
                    }
                    
                    let targetTranslate = -currentIndex * cardWidth;
                    targetTranslate = Math.max(minTranslate, Math.min(maxTranslate, targetTranslate));
                    
                    animateToPosition(targetTranslate, () => {
                        isAutoScrollEnabled = true;
                        resumeAutoScrollAfterDelay();
                    });
                });
            }
            
            // Handle window resize
            window.addEventListener('resize', () => {
                const newContainerWidth = container.offsetWidth;
                const newMinTranslate = -(cardWidth * cards.length - newContainerWidth + 50);
                
                // Reset position if needed
                if (currentTranslate < newMinTranslate) {
                    currentTranslate = newMinTranslate;
                    setWrapperTransform(currentTranslate);
                }
            });
            
            // Handle visibility change (pause when tab is not active)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    wrapper.classList.add('paused');
                } else {
                    wrapper.classList.remove('paused');
                }
            });
            
            // Start auto-scroll initially
            setTimeout(() => {
                startAutoScroll();
            }, 1000);
        });
    </script>
</body>
</html>