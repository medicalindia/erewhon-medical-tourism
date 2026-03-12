<?php
/**
 * Prototype for "Brand First" - EVready Expo
 */

// Simulate pulling live CSV data
$simulated_csv_data = "Pre-Event_Registrations,Post-Event_Footfall\n1250,3420\n";

// Parse the simulated CSV data
$rows = explode("\n", trim($simulated_csv_data));
$headers = str_getcsv(array_shift($rows));
$data = str_getcsv(array_shift($rows));

$stats = array_combine($headers, $data);

$pre_event_registrations = $stats['Pre-Event_Registrations'] ?? '0';
$post_event_footfall = $stats['Post-Event_Footfall'] ?? '0';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVready Expo | Brand First</title>
    <style>
        :root {
            /* Design Tokens */
            --bg-color: oklch(0.18 0.03 255);
            --text-main: oklch(0.96 0.01 255);
            --text-muted: oklch(0.75 0.02 255);
            --accent: oklch(0.68 0.18 260);

            /* Glassmorphism Tokens */
            --glass-bg: color-mix(in oklch, oklch(0.25 0.03 255) 30%, transparent);
            --glass-border: color-mix(in oklch, oklch(0.90 0.02 255) 15%, transparent);
            --glass-blur: 16px;

            /* Fluid Spacing */
            --space-xs: clamp(0.5rem, 0.4rem + 0.5vw, 0.75rem);
            --space-sm: clamp(1rem, 0.8rem + 1vw, 1.5rem);
            --space-md: clamp(1.5rem, 1.2rem + 1.5vw, 2.25rem);
            --space-lg: clamp(2rem, 1.6rem + 2vw, 3rem);
            --space-xl: clamp(3rem, 2.5rem + 3vw, 5rem);

            /* Fluid Typography */
            --font-sans: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --h1: clamp(2.5rem, 5vw + 1rem, 5.5rem);
            --h2: clamp(2rem, 4vw + 1rem, 4rem);
            --h3: clamp(1.25rem, 2vw + 0.5rem, 2rem);
            --p: clamp(1rem, 1vw + 0.5rem, 1.25rem);
        }

        /* Resets */
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background-color: var(--bg-color);
            color: var(--text-main);
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Sticky Navigation (Glassmorphism) */
        .site-nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: var(--space-sm) var(--space-lg);
            background: var(--glass-bg);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            border-bottom: 1px solid var(--glass-border);
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-nav__logo {
            font-size: var(--h3);
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        .btn {
            background: var(--accent);
            color: #fff;
            border: none;
            padding: var(--space-xs) var(--space-md);
            border-radius: 9999px; /* Perfect pill shape */
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s cubic-bezier(0.25, 1, 0.5, 1), background 0.2s ease, box-shadow 0.2s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn:hover {
            transform: translateY(-2px);
            background: color-mix(in oklch, var(--accent) 85%, white);
            box-shadow: 0 4px 12px color-mix(in oklch, var(--accent) 40%, transparent);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: var(--space-xl) var(--space-md);
            position: relative;
        }

        .hero__headline {
            font-size: var(--h1);
            line-height: 1.1;
            margin-bottom: var(--space-md);
            font-weight: 800;
            letter-spacing: -0.03em;
            max-width: 1200px;
        }

        /* Pure CSS Rotating Text Animation */
        .hero__rotating-text-wrapper {
            font-size: var(--h2);
            font-weight: 600;
            height: calc(var(--h2) * 1.4);
            line-height: calc(var(--h2) * 1.4);
            overflow: hidden;
            position: relative;
            color: var(--accent);
        }

        .hero__rotating-text {
            display: flex;
            flex-direction: column;
            animation: rotateText 6s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        }

        .hero__rotating-text span {
            height: calc(var(--h2) * 1.4);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        @keyframes rotateText {
            0%, 20% { transform: translateY(0); }
            33%, 53% { transform: translateY(calc(-1 * var(--h2) * 1.4)); }
            66%, 86% { transform: translateY(calc(-2 * var(--h2) * 1.4)); }
            100% { transform: translateY(calc(-3 * var(--h2) * 1.4)); }
        }

        /* Dynamic Dashboard Section */
        .dashboard {
            padding: var(--space-xl) var(--space-lg);
            max-width: 1000px;
            margin: 0 auto;
        }

        .dashboard__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--space-lg);
        }

        /* Glassmorphism Cards */
        .card {
            background: var(--glass-bg);
            backdrop-filter: blur(var(--glass-blur));
            -webkit-backdrop-filter: blur(var(--glass-blur));
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: var(--space-lg);
            text-align: center;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card__title {
            font-size: var(--h3);
            color: var(--text-muted);
            margin-bottom: var(--space-sm);
            font-weight: 500;
        }

        .card__value {
            font-size: var(--h1);
            font-weight: 700;
            color: var(--accent);
            line-height: 1;
        }

        /* Event Details Section */
        .event-details {
            padding: var(--space-xl) var(--space-lg);
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding-bottom: calc(var(--space-xl) * 2); /* Extra padding for scrolling */
        }

        .event-details__title {
            font-size: var(--h2);
            margin-bottom: var(--space-md);
            font-weight: 700;
        }

        .event-details__desc {
            font-size: var(--p);
            color: var(--text-muted);
            margin-bottom: var(--space-lg);
        }
    </style>
</head>
<body>

    <nav class="site-nav">
        <div class="site-nav__logo">Brand First</div>
        <button class="btn">Register Now</button>
    </nav>

    <main>
        <!-- 1. Hero Section -->
        <section class="hero">
            <h1 class="hero__headline">Where Brands Become Experiences</h1>
            <div class="hero__rotating-text-wrapper">
                <div class="hero__rotating-text">
                    <span>Experience.</span>
                    <span>Engage.</span>
                    <span>Elevate.</span>
                    <span>Experience.</span>
                </div>
            </div>
        </section>

        <!-- 2. Dynamic API Dashboard -->
        <section class="dashboard">
            <div class="dashboard__grid">
                <div class="card gs-reveal">
                    <h2 class="card__title">Pre-Event Registrations</h2>
                    <div class="card__value"><?php echo htmlspecialchars($pre_event_registrations); ?></div>
                </div>
                <div class="card gs-reveal">
                    <h2 class="card__title">Post-Event Footfall</h2>
                    <div class="card__value"><?php echo htmlspecialchars($post_event_footfall); ?></div>
                </div>
            </div>
        </section>

        <!-- 3. Event Details Section -->
        <section class="event-details">
            <h2 class="event-details__title gs-reveal">The EVready Expo</h2>
            <p class="event-details__desc gs-reveal">
                Join us for the premier experiential marketing event of the year. Discover cutting-edge strategies, interactive showcases, and unparalleled networking opportunities that will transform the way your brand connects with its audience. EVready Expo is where the future of engagement takes center stage.
            </p>
        </section>
    </main>

    <!-- GSAP via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <script>
        // Register GSAP ScrollTrigger
        gsap.registerPlugin(ScrollTrigger);

        // Smooth fade-in and translate-up animations for Event Details and Cards
        const revealElements = document.querySelectorAll('.gs-reveal');

        revealElements.forEach((el) => {
            gsap.fromTo(el,
                {
                    opacity: 0,
                    y: 40
                },
                {
                    scrollTrigger: {
                        trigger: el,
                        start: "top 85%", // Animation triggers when element is 85% into viewport
                        toggleActions: "play none none reverse"
                    },
                    opacity: 1,
                    y: 0,
                    duration: 1,
                    ease: "power3.out"
                }
            );
        });
    </script>
</body>
</html>