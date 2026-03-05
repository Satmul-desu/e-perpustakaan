{{-- ================================================
     FILE: resources/views/partials/footer.blade.php
     FUNGSI: Footer - Modern & Responsive
     ================================================ --}}

<footer class="modern-footer mt-5">
    <div class="container">
        {{-- Main Footer Content --}}
        <div class="row g-4">
            {{-- Brand & Description --}}
            <div class="col-lg-4 col-md-6 footer-brand">
                <div class="footer-logo mb-3">
                    <img src="{{ asset('images/logo-removebg-preview.png') }}" alt="Logo" class="footer-logo-img">
                    <span class="fw-bold fs-5">
                        <span class="text-white">Perpustakaan</span><span class="text-primary">Buku</span>
                    </span>
                </div>
                <p class="footer-desc text-secondary small mb-3">
                    Perpustakaan Buku Online Terpercaya, Murah, Aman, dan Lengkap dengan berbagai genre buku berkualitas.
                </p>
                {{-- Social Media Icons --}}
                <div class="social-links d-flex gap-2">
                    <a href="https://instagram.com/syasya_niss" class="social-link social-instagram" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://wa.me/6282129939458" class="social-link social-whatsapp" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading text-white mb-3">
                    <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i>Menu
                </h6>
                <ul class="footer-links list-unstyled m-0">
                    <li class="mb-2">
                        <a href="{{ route('catalog.index') }}" class="footer-link">
                            <i class="bi bi-box-seam me-2"></i>Katalog
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            <i class="bi bi-lightning-fill me-2" ></i>peminjaman buku
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            <i class="bi bi-info-circle me-2"></i>Tentang Kami
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="https://wa.me/6282129939458" class="footer-link">
                            <i class="bi bi-telephone me-2"></i>Kontak
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Help & Support --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading text-white mb-3">
                    <i class="bi bi-question-circle-fill me-2 text-primary"></i>Bantuan
                </h6>
                <ul class="footer-links list-unstyled m-0">
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            <i class="bi bi-patch-question me-2"></i>FAQ
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            <i class="bi bi-shield-check me-2"></i>Kebijakan Privasi
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="#" class="footer-link">
                            <i class="bi bi-truck me-2"></i>Pengiriman
                        </a>
                    </li>
                   <li class="mb-2">
                        <a href="#" class="footer-link">
                            <i class="bi bi-arrow-repeat me-2"></i>Pengembalian

                        </a>
                    </li>
                </ul>
            </div> 
            {{-- Contact Info --}}
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading text-white mb-3">
                    <i class="bi bi-geo-alt-fill me-2 text-primary"></i>Hubungi Kami
                </h6>
                <ul class="footer-contact list-unstyled m-0">
                    <li class="mb-2 d-flex align-items-start">
                        <i class="bi bi-geo-alt mt-1 me-2 text-primary"></i>
                        <span class="text-secondary small">Jl. Rancamanyar, Bandung, Jawa Barat</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-telephone me-2 text-primary"></i>
                        <span class="text-secondary small">0821-2993-9458</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-envelope me-2 text-primary"></i>
                        <span class="text-secondary small">PerpustakaanBuku@PerpustakaanOnline.com</span>
                    </li>
                    <li class="mb-2 d-flex align-items-center">
                        <i class="bi bi-clock me-2 text-primary"></i>
                        <span class="text-secondary small">24 Jam Online</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Payment Methods & Copyright --}}
        <div class="footer-bottom mt-4 pt-3 border-secondary">
            <div class="row align-items-center">
                {{-- Payment Badges --}}
                <div class="col-md-6 mb-3 mb-md-0">
                    <span class="text-secondary me-3 small">Metode Pembayaran:(jika ingin di tebus atau terkena denda)</span>
                    <div class="payment-badges d-inline-flex gap-2">
                        <span class="payment-badge bg-primary rounded px-2 py-1 text-white small">
                            <i class="bi bi-credit-card me-1"></i>Visa
                        </span>
                        <span class="payment-badge bg-danger rounded px-2 py-1 text-white small">
                            <i class="bi bi-credit-card-2-back me-1"></i>MasterCard
                        </span>
                        <span class="payment-badge bg-success rounded px-2 py-1 text-white small">
                            <i class="bi bi-cash me-1"></i>COD
                        </span>
                        <span class="payment-badge bg-warning rounded px-2 py-1 text-dark small">
                            <i class="bi bi-wallet2 me-1"></i>Transfer
                        </span>
                    </div>
                </div>

                {{-- Copyright --}}
                <div class="col-md-6 text-md-end">
                    <p class="copyright mb-0 text-secondary small">
                        <i class="bi bi-c-circle me-1"></i>
                        {{ date('Y') }} <span class="text-white fw-bold">Perpustakaan Buku</span>. 
                        All rights reserved. Made with tsutaz<i class="bi bi-heart-fill text-danger" style="font-size: 0.7rem;"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Floating Back to Top Button --}}
    <button class="back-to-top btn btn-primary rounded-circle" onclick="window.scrollTo({top: 0, behavior: 'smooth'})" title="Kembali ke atas">
        <i class="bi bi-arrow-up"></i>
    </button>
</footer>

<style>
    /* ========== FOOTER BASE STYLES ========== */
    .modern-footer {
        background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
        padding: 4rem 0 1.5rem;
        position: relative;
        border-top: 1px solid #334155;
    }
    
    .footer-logo {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .footer-logo-img {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }
    
    .text-primary {
        color: #60a5fa !important;
    }
    
    .footer-desc {
        line-height: 1.6;
        max-width: 300px;
    }
    
    /* ========== SOCIAL LINKS ========== */
    .social-links {
        margin-top: 0.5rem;
    }
    
    .social-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        text-decoration: none;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .social-facebook {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }
    
    .social-instagram {
        background: rgba(225, 48, 108, 0.2);
        color: #e1306c;
    }
    
    .social-whatsapp {
        background: rgba(37, 211, 102, 0.2);
        color: #25d366;
    }
    
    .social-twitter {
        background: rgba(255, 255, 255, 0.1);
        color: #e0e0e0;
    }
    
    .social-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    /* ========== FOOTER LINKS ========== */
    .footer-heading {
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    
    .footer-links {
        padding-left: 0;
    }
    
    .footer-link {
        color: #94a3b8;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }
    
    .footer-link:hover {
        color: #60a5fa;
        padding-left: 5px;
    }
    
    .footer-link i {
        font-size: 0.8rem;
    }
    
    .footer-contact li {
        font-size: 0.875rem;
    }
    
    .footer-contact i {
        font-size: 0.9rem;
    }
    
    /* ========== PAYMENT BADGES ========== */
    .payment-badges {
        flex-wrap: wrap;
    }
    
    .payment-badge {
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }
    
    .payment-badge i {
        font-size: 0.85rem;
    }
    
    .copyright {
        font-size: 0.85rem;
    }
    
    /* ========== BACK TO TOP BUTTON ========== */
    .back-to-top {
        position: absolute;
        bottom: 20px;
        right: 20px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        transition: all 0.3s ease;
        z-index: 100;
    }
    
    .back-to-top:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.5);
    }
    
    /* ========== RESPONSIVE STYLES ========== */
    
    /* Tablet (576px - 991px) */
    @media (min-width: 576px) and (max-width: 991.98px) {
        .modern-footer {
            padding: 3rem 0 1.5rem;
        }
        
        .footer-logo-img {
            width: 36px;
            height: 36px;
        }
        
        .social-link {
            width: 34px;
            height: 34px;
            font-size: 0.95rem;
        }
        
        .payment-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }
    }
    
    /* Mobile (< 576px) */
    @media (max-width: 575.98px) {
        .modern-footer {
            padding: 2.5rem 0 1rem;
        }
        
        .footer-brand {
            text-align: center;
        }
        
        .footer-logo {
            justify-content: center;
        }
        
        .footer-desc {
            max-width: 100%;
            text-align: center;
        }
        
        .social-links {
            justify-content: center;
        }
        
        .footer-heading {
            font-size: 0.95rem;
            margin-bottom: 0.75rem !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .footer-links {
            text-align: center;
        }
        
        .footer-link {
            justify-content: center;
        }
        
        .footer-contact {
            text-align: center;
        }
        
        .footer-contact li {
            justify-content: center;
        }
        
        .footer-bottom {
            text-align: center;
        }
        
        .payment-badges {
            justify-content: center;
            margin-top: 0.75rem;
        }
        
        .payment-badge {
            font-size: 0.65rem;
            padding: 0.2rem 0.4rem;
        }
        
        .payment-badge i {
            display: none;
        }
        
        .copyright {
            font-size: 0.75rem;
        }
        
        .back-to-top {
            width: 38px;
            height: 38px;
            bottom: 15px;
            right: 15px;
            font-size: 0.9rem;
        }
    }
    
    /* Very Small Mobile (< 360px) */
    @media (max-width: 359.98px) {
        .modern-footer {
            padding: 2rem 0 0.75rem;
        }
        
        .payment-badges {
            gap: 0.5rem !important;
        }
        
        .payment-badge {
            font-size: 0.6rem;
        }
    }
</style>

