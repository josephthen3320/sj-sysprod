function openSearchPopup() {
    var popup = window.open("search-article-popup.php", "popupWindow", "width=800,height=400");
}

/* Find article data and output on corresponding field --- called by the popup window */
function selectArticle(articleId) {
    document.getElementById("articleInput").value = articleId;

    // Make an AJAX request to fetch the article details
    $.ajax({
        url: "/php-modules/fetch/fetch-article-details.php",
        type: "GET",
        data: { articleId: articleId },
        dataType: "json",
        success: function(data) {
            // Set values in their respective fields
            $("#modelNameField").val(data.model_name);
            $("#isRibField").val(data.is_rib === '1' ? 'YES' : 'NO');
            $("#sampleCodeField").val(data.sample_code);
            $("#imageField").attr("src", "/img/articles/" + data.sample_img_path);

            // Fetch brand_name from 'brand' table
            $.ajax({
                url: "/php-modules/fetch/fetch-brand-name.php",
                type: "GET",
                data: { brandId: data.brand_id },
                dataType: "json",
                success: function(brandData) {
                    $("#brandIdField").val(brandData.brand_name);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });

            // Fetch embro_cmt_name from 'cmt' table
            $.ajax({
                url: "/php-modules/fetch/fetch-cmt-name.php",
                type: "GET",
                data: { cmtId: data.embro_cmt_id },
                dataType: "json",
                success: function(cmtData) {
                    $("#embroCmtIdField").val(cmtData.cmt_name);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });

            // Fetch print_cmt_name from 'cmt' table
            $.ajax({
                url: "/php-modules/fetch/fetch-cmt-name.php",
                type: "GET",
                data: { cmtId: data.print_cmt_id },
                dataType: "json",
                success: function(cmtData) {
                    $("#printCmtIdField").val(cmtData.cmt_name);
                },
                error: function(xhr, status, error) {
                    console.log(error);
                }
            });
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });

    window.close();
}