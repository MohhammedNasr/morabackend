<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Metronic CSS -->
    <link href="{{ asset('metronic_theme/assets/css/demo1/style.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('metronic_theme/assets/css/demo1/pages/login/login-1.css') }}" rel="stylesheet" type="text/css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.15);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        body {
            overflow: hidden;
        }

        .kt-checkbox {
            display: flex;
            align-items: center;
        }

        .kt-checkbox input[type="checkbox"] {
            margin: 0 8px 0 0;
            position: relative;
            top: -1px;
        }

        .kt-checkbox span {
            top: 0;
        }

        .kt-login__aside {
            position: relative;
            overflow: hidden;
        }

        .kt-login__aside::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: var(--primary-gradient);
            animation: rotate 15s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .kt-login__wrapper {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            border-left: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        .kt-login__logo img {
            transition: transform 0.3s ease, filter 0.3s ease;
        }

        .kt-login__logo:hover img {
            transform: scale(1.05);
            filter: drop-shadow(0 6px 20px rgba(0,0,0,0.2));
        }

        .kt-login__title {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: textReveal 1s ease-out;
        }

        @keyframes textReveal {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        .kt-login__subtitle {
            font-size: 1.25rem;
            font-weight: 400;
            opacity: 0.9;
            animation: fadeInUp 1s 0.5s ease-out both;
        }

        @media (max-width: 768px) {
            .kt-login__aside {
                height: 40vh;
            }

            .kt-login__wrapper {
                height: 60vh;
            }
        }

        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        /* Enhanced particles container */
        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
        }
    </style>
    @stack('styles')
</head>
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

    <!-- begin:: Page -->
    <div class="kt-grid kt-grid--ver kt-grid--root">
        <div class="kt-grid kt-grid--hor kt-grid--root kt-login kt-login--v1" id="kt_login">
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">
                <!-- begin:: Aside -->
                <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-1 kt-grid kt-grid--hor kt-login__aside" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div id="particles-js"></div>
                    <div class="kt-grid__item">
                        <a href="#" class="kt-login__logo">
                            <img src="{{ asset('images/brand/mora.png') }}" alt="Logo"
                                 style="max-height: 100px; filter: drop-shadow(0 4px 12px rgba(0,0,0,0.15));"
                                 class="animate__animated animate__fadeInUp animate__delay-1s floating">
                        </a>
                    </div>
                    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
                        <div class="kt-grid__item kt-grid__item--middle animate__animated animate__fadeInUp">
                            <h3 class="kt-login__title" style="font-size: 2.5rem; font-weight: 700; letter-spacing: -0.5px; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">مرحبا بكم في مورا</h3>
                            <h4 class="kt-login__subtitle" style="font-size: 1.25rem; font-weight: 400; opacity: 0.9;">المنصة المثالية لإدارة أعمالك.</h4>
                        </div>
                    </div>
                    <div class="kt-grid__item">
                        <div class="kt-login__info">
                            <div class="kt-login__copyright">
                                &copy; {{ date('Y') }} {{ config('app.name') }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end:: Aside -->

                <!-- begin:: Content -->
                <div class="kt-grid__item kt-grid__item--fluid kt-grid__item--order-tablet-and-mobile-2 kt-login__wrapper" style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border-right: 1px solid rgba(255, 255, 255, 0.1); border-left: none;">
                    @yield('content')
                </div>
                <!-- end:: Content -->
            </div>
        </div>
    </div>
    <!-- end:: Page -->

    <!-- Scripts -->
    <script src="{{ asset('metronic_theme/assets/vendors/general/jquery/dist/jquery.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/popper.js/dist/umd/popper.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('metronic_theme/assets/js/demo1/scripts.bundle.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js"></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ['#ffffff', '#667eea', '#764ba2']
                },
                shape: {
                    type: 'circle',
                    stroke: {
                        width: 0,
                        color: '#000000'
                    },
                    polygon: {
                        nb_sides: 5
                    }
                },
                opacity: {
                    value: 0.5,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 1,
                        opacity_min: 0.1,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 2,
                        size_min: 0.1,
                        sync: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#667eea',
                    opacity: 0.4,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 3,
                    direction: 'none',
                    random: true,
                    straight: false,
                    out_mode: 'bounce',
                    bounce: true,
                    attract: {
                        enable: true,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: 'canvas',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'bubble'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    bubble: {
                        distance: 200,
                        size: 6,
                        duration: 2,
                        opacity: 0.8,
                        speed: 3
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    </script>

    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>
