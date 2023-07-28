<div id='' class='w3-bar w3-black w3-border-bottom w3-hide-small'>
    <?php if ($_SESSION['user_role'] != 4) : ?>
        <a class='w3-bar-item w3-button' href='/transaction/pola-marker'>Pola Marker</a>
    <?php endif; ?>

    <a class='w3-bar-item w3-button' href='/transaction/cutting'>Cutting</a>

    <?php if ($_SESSION['user_role'] != 4) : ?>
        <a class='w3-bar-item w3-button' href='/transaction/embro'>Embro</a>
        <a class='w3-bar-item w3-button' href='/transaction/print-sablon'>Print/Sablon</a>
        <a class='w3-bar-item w3-button' href='/transaction/qc-embro'>QC Embro</a>
        <a class='w3-bar-item w3-button' href='/transaction/sewing'>Sewing</a>
        <a class='w3-bar-item w3-button' href='/transaction/washing'>Washing</a>
        <a class='w3-bar-item w3-button' href='/transaction/finishing'>Finishing</a>
        <a class='w3-bar-item w3-button' href='/transaction/qc-final'>QC Final</a>
        <a class='w3-bar-item w3-button' href='/transaction/perbaikan'>Perbaikan</a>
        <a class='w3-bar-item w3-button' href='/warehouse'>Gudang</a>
    <?php endif; ?>
</div>
