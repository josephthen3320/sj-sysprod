<div id='' class='w3-bar w3-black w3-border-bottom w3-hide-small'>
    <?php
        $btnPolaMarker = "<button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/pola-marker\")' >Pola Marker</button>";
        echo ($_SESSION['user_role'] != 4) ? $btnPolaMarker : '';
    ?>

    <button class='w3-bar-item w3-button' onclick='openURL("/transaction/cutting")' >Cutting</button>
    
    <?php
        $btnOthers = "
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/embro\")' >Embro</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/print-sablon\")' >Print/Sablon</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/qc-embro\")' >QC Embro</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/sewing\")' >Sewing</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/washing\")' >Washing</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/finishing\")' >Finishing</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/qc-final\")' >QC Final</button>
            <button class='w3-bar-item w3-button' onclick='openURL(\"/transaction/perbaikan\")' >Perbaikan</button>
            
            <button class='w3-bar-item w3-button' onclick='openURL(\"/warehouse\")' >Gudang</button>
            ";

        echo ($_SESSION['user_role'] != 4) ? $btnOthers : '';


    ?>
</div>