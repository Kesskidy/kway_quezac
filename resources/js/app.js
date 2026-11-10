import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Expose GSAP globally (handy in Blade inline scripts / the console)
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;

document.addEventListener("DOMContentLoaded", () => {
    
    // Animation du Hero (Header) au chargement
    gsap.from(".hero-title", {
        opacity: 0,
        y: 50,
        duration: 1
    });

    // Animation de la section "Fonctionnalités" au scroll
    gsap.from(".feature-card", {
        opacity: 0,
        y: 30,
        stagger: 0.2, // Anime les cartes les unes après les autres
        scrollTrigger: {
            trigger: ".features-section",
            start: "top 70%",
            toggleActions: "play none none reverse"
        }
    });

});