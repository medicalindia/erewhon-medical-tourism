/**
 * Navigation JavaScript - Erewhon Theme
 * Mobile menu toggle and accessibility
 */

(function() {
  'use strict';

  // Mobile menu toggle
  const menuToggle = document.querySelector('.menu-toggle');
  const navigation = document.querySelector('.main-navigation');
  
  if (menuToggle && navigation) {
    menuToggle.addEventListener('click', function() {
      // Toggle classes
      menuToggle.classList.toggle('toggled');
      navigation.classList.toggle('toggled');
      
      // Update ARIA attributes
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
      
      // Trap focus in mobile menu when open
      if (!isExpanded) {
        navigation.querySelector('a').focus();
      }
    });
  }
  
  // Close mobile menu on ESC key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && navigation && navigation.classList.contains('toggled')) {
      menuToggle.classList.remove('toggled');
      navigation.classList.remove('toggled');
      menuToggle.setAttribute('aria-expanded', 'false');
      menuToggle.focus();
    }
  });
  
  // Close mobile menu when clicking outside
  document.addEventListener('click', function(e) {
    if (navigation && navigation.classList.contains('toggled')) {
      if (!navigation.contains(e.target) && !menuToggle.contains(e.target)) {
        menuToggle.classList.remove('toggled');
        navigation.classList.remove('toggled');
        menuToggle.setAttribute('aria-expanded', 'false');
      }
    }
  });
  
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
  
  // Add active class on scroll (if using single-page navigation)
  function updateActiveNavOnScroll() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('.main-navigation a[href^="#"]');
    
    window.addEventListener('scroll', function() {
      let current = '';
      
      sections.forEach(section => {
        const sectionTop = section.offsetTop;
        const sectionHeight = section.clientHeight;
        if (scrollY >= (sectionTop - 100)) {
          current = section.getAttribute('id');
        }
      });
      
      navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === '#' + current) {
          link.classList.add('active');
        }
      });
    });
  }
  
  // Uncomment if using single-page navigation
  // updateActiveNavOnScroll();

})();
