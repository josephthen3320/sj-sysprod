<div id='' class='w3-bar w3-black w3-border-bottom w3-hide-small w3-small'>
    <?php if ($_SESSION['user_role'] != 4) : ?>
        <a class='w3-bar-item w3-button' href='/transaction/pola-marker'>Pola Marker</a>
    <?php endif; ?>

    <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/cutting'>Cutting</a>

    <?php if ($_SESSION['user_role'] != 4) : ?>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/embro'>Embro</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/print-sablon'>Print/Sablon</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/qc-embro'>QC Embro</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/sewing'>Sewing</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/transit'>Transit</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/washing'>Washing</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/finishing'>Finishing</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/qc-final'>QC Final</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/transaction/perbaikan'>Perbaikan</a>
        <a class='w3-bar-item w3-button w3-border w3-border-dark-grey' href='/warehouse'>Gudang</a>
    <?php endif; ?>
</div>


<?php /* With Icons */
/*
<div id='' class='w3-bar w3-black w3-border-bottom w3-hide-small'>
    <?php if ($_SESSION['user_role'] != 4) : ?>
        <a class='w3-bar-item w3-button' href='/transaction/pola-marker'><i class="fas fa-fw fa-draw-square"></i>&nbsp;Pola Marker</a>
    <?php endif; ?>

    <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/cutting'><i class="fas fa-fw fa-scissors"></i>&nbsp;Cutting</a>

    <?php if ($_SESSION['user_role'] != 4) : ?>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/embro'><i class="fas fa-fw fa-scarf"></i>&nbsp;Embro</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/print-sablon'><i class="fas fa-fw fa-pen-paintbrush"></i>&nbsp;Print/Sablon</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/qc-embro'><i class="fas fa-fw fa-clipboard-list-check"></i>&nbsp;QC Embro</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/sewing'><i class="fas fa-fw fa-reel"></i>&nbsp;Sewing</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/washing'><i class="fas fa-fw fa-washing-machine"></i>&nbsp;Washing</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/finishing'><i class="fas fa-fw fa-list"></i>&nbsp;Finishing</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/qc-final'><i class="fas fa-fw fa-clipboard-check"></i>&nbsp;QC Final</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/transaction/perbaikan'><i class="fas fa-fw fa-screwdriver-wrench"></i>&nbsp;Perbaikan</a>
        <a class='w3-bar-item w3-button w3-border-left w3-border-dark-gray' href='/warehouse'><i class="fas fa-fw fa-warehouse-full"></i>&nbsp;Gudang</a>
    <?php endif; ?>
</div>
*/
?>
