<?php
// Check if form is being submitted and handle email sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $company_name = $_POST['company_name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $products = $_POST['products'];
    $quantities = $_POST['quantity'];
    $packings = $_POST['packing'];

    $message = "You have received a new form submission:\n <br />";
    $message .= "Name: $name\n <br /> Email: $email\n  <br /> Company Name: $company_name\n <br />";
    $message .= "Address: $address\n <br /> Phone: $phone\n  <br /> City: $city\n <br />";
    $message .= "<b>Products :</b>\n";

    foreach ($products as $index => $product) {
        $quantity = $quantities[$index];
        $packing = $packings[$index];
        $message .= " <br /> Product : $product, Quantity: $quantity, Packing: $packing\n";
    }
    //echo $message;
    // Send email (adjust the email address and subject)
    mail('mdwidar84@gmail.com', 'New Request Products', $message);
    echo "<p>Email sent successfully!</p>";
    exit;
}


// Normal page load, display the form
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Dropdown Form</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</head>

<body>
    <form action="" id="productForm" method="post">
        <input type="text" name="name" placeholder="Name" value="Mohamed D" required><br>
        <input type="email" name="email" placeholder="Email" value="mdwidar@gmail.com" required><br>
        <input type="text" name="company_name" placeholder="Company Name" value="SevenSquare" required><br>
        <input type="text" name="address" placeholder="Address" value="Louran Address" required><br>
        <input type="text" name="phone" placeholder="Phone Number" value="01288989993" required><br>
        <input type="text" name="city" placeholder="City" value="Alexandria" required><br>
        <select id="category" name="category">
            <option value="">Select Category</option>
        </select><br>
        <select id="subcategory" name="subcategory">
            <option value="">Select Subcategory</option>
        </select><br>
        <select id="product" name="product">
            <option value="">Select Product</option>
        </select><br>
        <table id="product-details"></table>
        <button type="submit" name="submit">Submit</button>
    </form>




    <script>
        jQuery(document).ready(function() {
            // Fetch categories
            jQuery.ajax({
                //url: '?action=fetch_categories',
                url: 'https://mkg1975.com/wp-json/wp/v2/product-categories?parent=0',
                type: 'GET',
                success: function(catsObj) {
                    populateDropdown('#category', catsObj);
                }
            });

            // Fetch subcategories
            jQuery('#category').change(function() {
                var categoryId = jQuery(this).val();
                jQuery.ajax({
                    // url: '',
                    url: 'https://mkg1975.com/wp-json/wp/v2/product-categories?parent=' + categoryId,
                    // type: 'POST',
                    type: 'GET',
                    //data: {action: 'fetch_subcategories', category_id: categoryId},
                    success: function(data) {
                        populateDropdown('#subcategory', data);
                    }
                });
            });

            // Fetch products
            jQuery('#subcategory').change(function() {
                var subcategoryId = jQuery(this).val();
                jQuery.ajax({
                    // url: '',
                    url: 'https://mkg1975.com/wp-json/wp/v2/product?product-categories=' + subcategoryId,
                    // type: 'POST',
                    type: 'GET',
                    // data: {action: 'fetch_products', subcategory_id: subcategoryId},
                    success: function(data) {
                        populateProductsDropdown('#product', data);
                    }
                });
            });

            // Fetch products and generate row for quantity and packing
            jQuery('#product').change(function() {
                var productId = jQuery(this).val();
                var productName = jQuery("#product option:selected").text();
                if (productId) {
                    jQuery('#product-details').append(
                        '<tr>' +
                        '<td>' + productName + '<input type="hidden" name="products[]" value="' + productName + '"></td>' +
                        '<td><input type="number" name="quantity[]" min="1" max="100" step="1" required></td>' +
                        '<td><input type="text" name="packing[]" required></td>' +
                        '</tr>'
                    );
                }
            });

            function populateDropdown(selectId, items) {
                var select = jQuery(selectId);
                select.empty();
                select.append(jQuery('<option>', {
                    value: '',
                    text: 'Select'
                }));
                jQuery.each(items, function(index, item) {
                    select.append(jQuery('<option>', {
                        value: item.id,
                        text: item.name
                    }));
                });
            }


            function populateProductsDropdown(selectId, items) {
                var select = jQuery(selectId);
                select.empty();
                select.append(jQuery('<option>', {
                    value: '',
                    text: 'Select'
                }));
                jQuery.each(items, function(index, item) {
                    //console.log(item);
                    select.append(jQuery('<option>', {
                        value: item.id,
                        text: item.title.rendered
                    }));
                });
            }
        });
    </script>
</body>

</html>