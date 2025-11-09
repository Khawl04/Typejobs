<style>
.site-footer {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: #232a37;
    color: #ffffff;
    padding: 10px 16px;
    z-index: 100;
    box-shadow: 0px -1px 12px rgba(0,0,0,0.08);
}
.footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 18px;
}
.footer-contact {
    font-size: 16px;
    font-weight: 600;
    color: #75e4a6
}
.footer-contact a {
    color: #f7fffbff;
    text-decoration: underline;
    transition: color 0.18s;
}
.footer-support {
    font-size: 15px;
    color: #75e4a6;
    font-weight: 700;
    letter-spacing: .5px;
    background: rgba(255,255,255,0.06);
    padding: 5px 17px;
    border-radius:8px;
    text-decoration:none;
    transition: all .17s;
    margin: 0 24px;
    display:inline-block;
}
.footer-support:hover,
.footer-support:focus {
    background: #403a28;
    color: #fffbe8;
}
.footer-rights {
    font-size: 14px; 
    color: #75e4a6;
}
@media (max-width: 700px) {
    .footer-inner {
        flex-direction: column;
        gap:8px;
        text-align:center;
    }
    .footer-support { margin: 0 0 8px 0;}
}
</style>

<footer class="site-footer">
    <div class="footer-inner">
        <span class="footer-contact">
            Flowork Contacto: 
            <a href="mailto:floworksupp@gmail.com">floworksupp@gmail.com</a>
        </span>
        <a href="<?= BASE_URL ?>/soporte" class="footer-support">
            ¿Necesitás ayuda?
        </a>
        <span class="footer-rights">
            © 2025 Flowork. Todos los derechos reservados.
        </span>
    </div>
</footer>
