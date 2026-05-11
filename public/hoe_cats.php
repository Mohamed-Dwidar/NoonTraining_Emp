<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owl Carousel with Filters</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
</head>

<body>

    <div id="product-tabs">
        <!-- Tabs (categories) will be populated here -->
    </div>

    <div class="owl-carousel owl-theme">
        <!-- Carousel content will be populated dynamically -->
    </div>



    <script>
        jQuery(document).ready(function() {
            // Fetch categories
            jQuery.ajax({
                url: 'https://mkg1975.com/wp-json/wp/v2/product-categories?parent=0',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    // return false;
                    setupTabs(response);
                },
                error: function() {
                    console.error('Failed to fetch categories');
                }
            });
        });

        function setupTabs(categories) {
            categories.forEach(category => {
                var button = jQuery('<button class="tab-link" data-category-id="' + category.id + '">' + category.name + '</button>');
                jQuery('#product-tabs').append(button);

                // Attach click event to each tab to load products
                button.on('click', function() {
                    var categoryId = jQuery(this).data('category-id');
                    fetchProductsForCategory(categoryId);
                });
            });

            // Automatically click the first tab to load products initially
            jQuery('#product-tabs .tab-link:first').trigger('click');
        }

        function fetchProductsForCategory(categoryId) {
            jQuery.ajax({
                // url: 'https://mkg1975.com/wp-json/wp/v2/product?product-categories=' + categoryId + '&featured_product=false',
                url: 'https://mkg1975.com/wp-json/custom/v1/products?product-categories=' + categoryId + '&featured_product=1',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    setupCarousel(response);
                },
                error: function() {
                    console.error('Failed to fetch products for category ' + categoryId);
                }
            });
        }

        function setupCarousel(products) {
            var carousel = jQuery(".owl-carousel");
            carousel.owlCarousel('destroy'); // Destroy existing carousel instance if it exists
            carousel.empty(); // Clear existing items

            // Add new items
            products.forEach(product => {
                console.log(product);
                var productId = product.id;
                var productName = product.name;
                var productImage = product.featured_image;  
                var productURL = product.url;  
                var productHTML = '<div class="item">' +
                    '<h4>' +
                    '<img src="' + productImage + '">' +
                    productName +
                    '</h4></div>';
                carousel.append(productHTML);
            });

            // Re-initialize the carousel
            carousel.owlCarousel({
                loop: false,
                margin: 10,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    }
                }
            });
        }
    </script>


</body>

</html>