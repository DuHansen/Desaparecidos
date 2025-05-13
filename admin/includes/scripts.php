<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="./dist/js/tabler.min.js?1741125160" defer></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->
<!-- BEGIN DEMO SCRIPTS -->
<script src="./preview/js/demo.min.js?1741125160" defer></script>
<!-- END DEMO SCRIPTS -->

<!-- BEGIN EXATA SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- END EXATA SCRIPTS -->

<!-- BEGIN PAGE LEVEL LIBRARIES -->
<?php
if (in_array($page, ['home','marcas'])) {
?>
<script src="./libs/apexcharts/dist/apexcharts.min.js?1741125165" defer></script>
<script src="./libs/jsvectormap/dist/jsvectormap.min.js?1741125165" defer></script>
<script src="./libs/jsvectormap/dist/maps/world.js?1741125165" defer></script>
<script src="./libs/jsvectormap/dist/maps/world-merc.js?1741125165" defer></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.js" defer></script>
<?php
}
?>
<!-- END PAGE LEVEL LIBRARIES -->