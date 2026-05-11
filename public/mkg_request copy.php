<?php
// Check if this is an AJAX request for categories
// if (isset($_GET['action']) && $_GET['action'] === 'fetch_categories') {
//     $categories = [
//         ['id' => '1', 'name' => 'Electronics'],
//         ['id' => '2', 'name' => 'Clothing'],
//         ['id' => '3', 'name' => 'Furniture']
//     ];
//     header('Content-Type: application/json');
//     echo json_encode($categories);
//     exit;
// }

// // Check if this is an AJAX request for subcategories
// if (isset($_POST['action']) && $_POST['action'] === 'fetch_subcategories') {
//     $subcategories = [
//         '1' => [
//             ['id' => '11', 'name' => 'Phones'],
//             ['id' => '12', 'name' => 'Laptops']
//         ],
//         '2' => [
//             ['id' => '21', 'name' => 'Men'],
//             ['id' => '22', 'name' => 'Women']
//         ],
//         '3' => [
//             ['id' => '31', 'name' => 'Tables'],
//             ['id' => '32', 'name' => 'Chairs']
//         ]
//     ];
//     header('Content-Type: application/json');
//     echo json_encode($subcategories[$_POST['category_id']] ?? []);
//     exit;
// }

// // Check if this is an AJAX request for products
// if (isset($_POST['action']) && $_POST['action'] === 'fetch_products') {
//     $products = [
//         '11' => [
//             ['id' => '111', 'name' => 'iPhone'],
//             ['id' => '112', 'name' => 'Samsung Galaxy']
//         ],
//         '12' => [
//             ['id' => '121', 'name' => 'MacBook'],
//             ['id' => '122', 'name' => 'Dell XPS']
//         ],
//         '21' => [
//             ['id' => '211', 'name' => 'T-shirt'],
//             ['id' => '212', 'name' => 'Jeans']
//         ],
//         '22' => [
//             ['id' => '221', 'name' => 'Dress'],
//             ['id' => '222', 'name' => 'Skirt']
//         ],
//         '31' => [
//             ['id' => '311', 'name' => 'Dining Table'],
//             ['id' => '312', 'name' => 'Coffee Table']
//         ],
//         '32' => [
//             ['id' => '321', 'name' => 'Office Chair'],
//             ['id' => '322', 'name' => 'Gaming Chair']
//         ]
//     ];
//     header('Content-Type: application/json');
//     echo json_encode($products[$_POST['subcategory_id']] ?? []);
//     exit;
// }


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

    $message = "You have received a new form submission:\n";
    $message .= "Name: $name\nEmail: $email\nCompany Name: $company_name\n";
    $message .= "Address: $address\nPhone: $phone\nCity: $city\n";
    $message .= "Products and Details:\n";

    foreach ($products as $index => $product) {
        $quantity = $quantities[$index];
        $packing = $packings[$index];
        $message .= "Product ID: $product, Quantity: $quantity, Packing: $packing\n";
    }
    print_r($message);
    // Send email (adjust the email address and subject)
    mail('your-email@example.com', 'New Form Submission', $message);
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
    <script>
        $(document).ready(function() {
            // Fetch categories
            $.ajax({
                //url: '?action=fetch_categories',
                url: 'https://mkg1975.com/wp-json/wp/v2/product-categories?parent=0',
                type: 'GET',
                success: function(catsObj) {
                    populateDropdown('#category', catsObj);
                }
            });

            // Fetch subcategories
            $('#category').change(function() {
                var categoryId = $(this).val();
                $.ajax({
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
            $('#subcategory').change(function() {
                var subcategoryId = $(this).val();
                $.ajax({
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
            $('#product').change(function() {
                var productId = $(this).val();
                var productName = $("#product option:selected").text();
                if (productId) {
                    $('#product-details').append(
                        '<tr>' +
                            '<td>' + productName + '<input type="hidden" name="products[]" value="' + productId + '"></td>' +
                            '<td><input type="number" name="quantity[]" min="1" max="100" step="1" required></td>' +
                            '<td><input type="text" name="packing[]" required></td>' +
                        '</tr>'
                    );
                }
            });


            function populateDropdown(selectId, items) {
                var select = $(selectId);
                select.empty();
                select.append($('<option>', { value: '', text: 'Select' }));
                $.each(items, function(index, item) {
                    select.append($('<option>', { 
                        value: item.id,
                        text: item.name 
                    }));
                });
            }


            function populateProductsDropdown(selectId, items) {
                var select = $(selectId);
                select.empty();
                select.append($('<option>', { value: '', text: 'Select' }));
                $.each(items, function(index, item) {
                    //console.log(item);
                    select.append($('<option>', { 
                        value: item.id,
                        text: item.title.rendered 
                    }));
                });
            }
        });
    </script>
</head>
<body>
    <form action="" method="post">
        <input type="text" name="name" placeholder="Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="company_name" placeholder="Company Name" required><br>
        <input type="text" name="address" placeholder="Address" required><br>
        <input type="text" name="phone" placeholder="Phone Number" required><br>
        <input type="text" name="city" placeholder="City" required><br>
        <select id="category" name="category"><option value="">Select Category</option></select><br>
        <select id="subcategory" name="subcategory"><option value="">Select Subcategory</option></select><br>
        <select id="product" name="product"><option value="">Select Product</option></select><br>
        <table id="product-details"></table>
        <button type="submit" name="submit">Submit</button>
    </form>
</body>
</html>
