document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Header Functionality
    const header = document.getElementById('header');
    
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('sticky');
            } else {
                header.classList.remove('sticky');
            }
        });
    }

    // 2. Mobile Menu Toggle
    const hamburger = document.getElementById('hamburger');
    const mobileNav = document.getElementById('mobile-nav');
    const mobileLinks = document.querySelectorAll('.mobile-nav a:not(.mobile-accordion-trigger)');

    if (hamburger && mobileNav) {
        hamburger.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
            
            // Toggle hamburger icon between bars and times
            const icon = hamburger.querySelector('i');
            if (mobileNav.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });

        // Close mobile menu when a non-trigger nav link is clicked
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileNav.classList.remove('active');
                const icon = hamburger.querySelector('i');
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            });
        });
    }

    // 3. Tutors Auto Carousel for Mobile
    const tutorsGrid = document.querySelector('.tutors-grid');
    if (tutorsGrid) {
        let scrollInterval;
        const startCarousel = () => {
            if (window.innerWidth <= 768) {
                if(!scrollInterval) {
                    scrollInterval = setInterval(() => {
                        // Check if we reached the right end
                        if(tutorsGrid.scrollLeft + tutorsGrid.clientWidth >= tutorsGrid.scrollWidth - 10) {
                            tutorsGrid.scrollTo({ left: 0, behavior: 'smooth' }); // Loop back
                        } else {
                            tutorsGrid.scrollBy({ left: tutorsGrid.clientWidth * 0.85, behavior: 'smooth' });
                        }
                    }, 3000); // 3 seconds interval
                }
            } else {
                clearInterval(scrollInterval);
                scrollInterval = null;
            }
        };
        
        startCarousel();
        window.addEventListener('resize', startCarousel);
        
        // Pause on touch to let user scroll manually
        tutorsGrid.addEventListener('touchstart', () => clearInterval(scrollInterval), {passive: true});
        tutorsGrid.addEventListener('touchend', () => {
            clearInterval(scrollInterval);
            scrollInterval = null;
            setTimeout(startCarousel, 2000); // Resume after 2 seconds
        }, {passive: true});
    }

    // 4. Achievements Auto Carousel (Desktop & Mobile)
    const achGrid = document.querySelector('.achievements-carousel');
    if (achGrid) {
        // Clone cards to ensure enough content for scrolling
        const originalCards = Array.from(achGrid.children);
        if (originalCards.length > 0) {
            for (let i = 0; i < 3; i++) {
                originalCards.forEach(card => {
                    achGrid.appendChild(card.cloneNode(true));
                });
            }
        }

        let achInterval;

        // Calculate actual card width + gap dynamically
        const getCardWidth = () => {
            const firstCard = achGrid.querySelector('.achievement-card');
            if (!firstCard) return 280;
            const style = getComputedStyle(achGrid);
            const gap = parseFloat(style.gap) || 20;
            return firstCard.offsetWidth + gap;
        };

        // Scroll the carousel by one card in a given direction
        const scrollCarousel = (direction) => {
            const amount = getCardWidth() * direction;
            // Temporarily disable scroll-snap so scrollBy works smoothly
            achGrid.style.scrollSnapType = 'none';
            achGrid.scrollBy({ left: amount, behavior: 'smooth' });
            setTimeout(() => {
                achGrid.style.scrollSnapType = 'x mandatory';
            }, 500);
        };

        const startAchCarousel = () => {
            if (!achInterval) {
                achInterval = setInterval(() => {
                    // If scrolled past halfway (cloned area), jump back seamlessly
                    const halfScroll = achGrid.scrollWidth / 2;
                    if (achGrid.scrollLeft >= halfScroll) {
                        achGrid.style.scrollSnapType = 'none';
                        achGrid.style.scrollBehavior = 'auto';
                        achGrid.scrollLeft = 0;
                        achGrid.offsetHeight; // force reflow
                        achGrid.style.scrollBehavior = 'smooth';
                        achGrid.style.scrollSnapType = 'x mandatory';
                    }
                    scrollCarousel(1);
                }, 3000);
            }
        };

        startAchCarousel();

        // Pause on hover (desktop)
        achGrid.addEventListener('mouseenter', () => {
            clearInterval(achInterval);
            achInterval = null;
        });
        achGrid.addEventListener('mouseleave', () => {
            startAchCarousel();
        });
        // Pause on touch (mobile)
        achGrid.addEventListener('touchstart', () => {
            clearInterval(achInterval);
            achInterval = null;
        }, { passive: true });
        achGrid.addEventListener('touchend', () => {
            setTimeout(startAchCarousel, 2000);
        }, { passive: true });

        // Arrow button controls (Prev & Next)
        const prevBtn = document.getElementById('ach-prev');
        const nextBtn = document.getElementById('ach-next');

        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                clearInterval(achInterval);
                achInterval = null;
                scrollCarousel(-1);
                setTimeout(startAchCarousel, 3000);
            });
        }
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                clearInterval(achInterval);
                achInterval = null;
                scrollCarousel(1);
                setTimeout(startAchCarousel, 3000);
            });
        }
    }

    // 5. Desktop Mega Menu & Mobile Accordion Controllers
    const desktopTriggers = document.querySelectorAll('.desktop-dropdown-trigger');
    const megaPanels = document.querySelectorAll('.mega-menu-panel');
    const backdrop = document.getElementById('nav-backdrop');

    // Helper to close all desktop menus
    let isAnimating = false;

    function closeAllMegaMenus() {
        desktopTriggers.forEach(t => t.classList.remove('open'));
        megaPanels.forEach(p => p.classList.remove('open'));
        if (backdrop) backdrop.classList.remove('show');
    }

    // Desktop Mega Menu Logic
    desktopTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (isAnimating) return; // transition lock

            const targetId = trigger.getAttribute('data-target');
            const targetPanel = document.getElementById(targetId);
            const isOpen = trigger.classList.contains('open');

            isAnimating = true;
            setTimeout(() => { isAnimating = false; }, 350); // match css transition duration

            closeAllMegaMenus(); // Close others

            if (!isOpen && targetPanel) {
                trigger.classList.add('open');
                targetPanel.classList.add('open');
                if (backdrop) backdrop.classList.add('show');
            }
        });
    });

    // Close desktop menu on outside click or backdrop click
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.desktop-nav') && !e.target.closest('.mega-menu-panel')) {
            closeAllMegaMenus();
        }
    });

    if (backdrop) {
        backdrop.addEventListener('click', closeAllMegaMenus);
    }

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAllMegaMenus();
    });

    // Mobile Accordion Logic
    const accordionTriggers = document.querySelectorAll('.mobile-accordion-trigger');
    
    accordionTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = trigger.classList.contains('open');
            const content = trigger.nextElementSibling;
            
            // Close all others first
            accordionTriggers.forEach(t => {
                t.classList.remove('open');
                if (t.nextElementSibling) t.nextElementSibling.classList.remove('open');
            });
            
            if (!isOpen && content) {
                trigger.classList.add('open');
                content.classList.add('open');
            }
        });
    });
});

