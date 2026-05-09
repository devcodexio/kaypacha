<?php
// vistas/layout/footer.php
?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Agregar el CDN de Font Awesome para los iconos -->
<footer class="footer text-white text-center py-4" style="background-color:#2b2f33;">

    <p>© <?= date('Y') ?> Restaurante Kay-Pacha. Todos los derechos reservados.</p>

    <div>
        <!-- Facebook -->
        <a href="https://www.facebook.com/eltemplobarycocina/?locale=es_LA"
           class="text-white me-3" target="_blank">
            <i class="bi bi-facebook" style="font-size: 24px;"></i>
        </a>

        <!-- WhatsApp -->
        <a href="https://w.app/FnOCWJ"
           class="text-white me-3" target="_blank">
            <i class="bi bi-whatsapp" style="font-size: 24px;"></i>
        </a>

        <!-- Instagram -->
        <a href="https://www.instagram.com/templobarycocinaayacucho/"
           class="text-white me-3" target="_blank">
            <i class="bi bi-instagram" style="font-size: 24px;"></i>
        </a>

        <!-- TikTok -->
        <a href="https://www.tiktok.com/@eltemplobarycocina"
           class="text-white" target="_blank">
            <i class="bi bi-tiktok" style="font-size: 24px;"></i>
        </a>
    </div>

</footer>

<!-- Ventanas modales (Nosotros) -->
<script>
    function mostrarVentana() {
        const v = document.getElementById('ventanaNosotros');
        if (v) v.style.display = 'flex';
    }

    function cerrarVentana() {
        const v = document.getElementById('ventanaNosotros');
        if (v) v.style.display = 'none';
    }
</script>

<!-- Scripts globales -->
<script src="/clientes/assets/scripts.js?v=4"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
