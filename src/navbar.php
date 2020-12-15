<nav class="navbar navbar-expand-sm static-top navbar-dark bg-dark">
    <a class="navbar-brand" href="start.php">
        <img class="ml-2 img-fluid"
            src="../../img/plum-logo.png"
            alt="Logo"
            style="width:40px">
        <span style="color: #CC54A4;">Pflaumen Pflamminger</span>
    </a>
    <ul class="navbar-nav ml-3">
        <li class="nav-item <?php if ($activeFile == "start") { echo "active"; } ?>">
            <a class="nav-link" href="../shop/start.php">Start<?php if ($activeFile == "start") { echo "<span class='sr-only'>(current)</span>"; } ?></a>
        </li>
        <li class="nav-item <?php if ($activeFile == "kategorie") { echo "active"; } ?>">
            <a class="nav-link" href="../shop/kategorie.php">Produkte<?php if ($activeFile == "kategorie") { echo "<span class='sr-only'>(current)</span>"; } ?></a>
        </li>
        <li class="nav-item <?php if ($activeFile == "warenkorb") { echo "active"; } ?>">
            <a class="nav-link" href="../shop/warenkorb.php">Warenkorb<?php if ($activeFile == "warenkorb") { echo "<span class='sr-only'>(current)</span>"; } ?></a>
        </li>

        <!-- 
            Folgender Code würde dem Admin Zugriff auf das Admin-Portal vom Shop aus ermöglichen:
            <?php if ($_SESSION["typ"] == "admin") { ?>
            <li class="nav-item <?php if ($activeSubdir == "admin") { echo "active"; } ?>">
                <a class="nav-link" href="../admin/admin.php">Admin-Verwaltung<?php if ($activeFile == "admin") { echo "<span class='sr-only'>(current)</span>"; } ?></a>
            </li>
        <?php } ?>
        -->
    </ul>
</nav>