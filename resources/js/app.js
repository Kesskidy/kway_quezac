import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Expose GSAP globally (handy in Blade inline scripts / the console)
window.gsap = gsap;
window.ScrollTrigger = ScrollTrigger;
