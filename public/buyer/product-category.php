<?php
// Fetch categories from API
$api_url = 'http://192.168.1.101:8080/categories/all';
$response = file_get_contents($api_url);
$categories = json_decode($response, true);  // Decode the categories JSON response

// Check if a category_id is set in the URL
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// echo "<h2>Categories:</h2><ul>";
// foreach ($categories['data'] as $category) {
//     echo "<li><a href='?category_id={$category['id']}'>{$category['category']}</a></li>";
// }
// echo "</ul>";
// Initialize products array
$products = [];

if ($category_id) {
    // Fetch products for the selected category using API
    $product_api_url = "http://192.168.1.101:8080/products/category/{$category_id}";
    $product_response = file_get_contents($product_api_url);
    $products = json_decode($product_response, true);  // Decode the product JSON response

    // Display products for the selected category
    // if (!empty($products['data'])) {
    //     echo "<h2>Products in Category {$category_id}:</h2><ul>";
    //     foreach ($products['data'] as $product) {
    //         echo "<li>{$product['Product_Name']} - {$product['price']}</li>";
    //     }
    //     echo "</ul>";
    // } else {
    //     echo "<p>No products found in this category.</p>";
    // }
} else {
    // If no category is selected, fetch and display all products
    $product_api_url = "http://192.168.1.101:8080/products/all";
    $product_response = file_get_contents($product_api_url);
    $products = json_decode($product_response, true);  // Decode the product JSON response

    // if (!empty($products['data'])) {
    //     echo "<h2>All Products:</h2><ul>";
    //     foreach ($products['data'] as $product) {
    //         echo "<li>{$product['Product_Name']} - {$product['price']}</li>";
    //     }
    //     echo "</ul>";
    // } else {
    //     echo "<p>No products available.</p>";
    // }
}
?>

<?php require_once('header.php'); ?>

<div class="page">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <!-- Sidebar Category Display -->
                <div class="sidebar-category">
                    <h3>Categories</h3>
                    <ul>
                        <?php if (isset($categories['data']) && count($categories['data']) > 0): ?>
                            <?php foreach ($categories['data'] as $category): ?>
                                <li>
                                <a href="product-category.php?category_id=<?php echo $category['id']; ?>" class="btn btn-primary category-button">
                                        <?php echo $category['category']; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>No categories available.</p>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-9">
    <h3>Products</h3>
    <div class="product-list">
        <?php var_dump($products) ?>
        <?php if (isset($products['data']) && count($products['data']) > 0): ?>
            <div class="row">
                <?php foreach ($products['data'] as $product): ?>
                    <!--?php var_dump($products);?-->
                    <?php if (isset($product['id'], $product['Product_Name'], $product['price'], $product['photos'])): ?>
                        <div class="col-md-4 item item-product-cat">
                            <div class="inner">
                                <div class="thumb">
                                <?php
                                $photoPath = json_decode($product['photos'])[0];  // Decode the string into an array and get the first element
                                ?>
                                <div class="photo" style="background-image:url(http://192.168.1.101:8080/storage/<?php echo $photoPath; ?>); width: 100%; height: 200px; background-size: cover;"></div>
                                    <div class="overlay"></div>
                                </div>
                                <div class="text">
                                    <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo $product['Product_Name']; ?></a></h3>
                                    <h4>₱<?php echo $product['price']; ?></h4>
                                    <div class="rating">
                                        <?php
                                        $rating = isset($product['rating']) ? $product['rating'] : 0;
                                        for ($i = 1; $i <= 5; $i++) {
                                            echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                        }
                                        ?>
                                    </div>
                                    <?php if (isset($product['Quantity']) && $product['Quantity'] == 0): ?>
                                        <div class="out-of-stock">
                                            <div class="inner">Out Of Stock</div>
                                        </div>
                                    <?php else: ?>
                                        <p><a href="product.php?id=<?php echo $product['id']; ?>"><i class="fa fa-shopping-cart"></i> Add to Cart</a></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <p>Product information missing.</p>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No products available for this category.</p>
        <?php endif; ?>
    </div>
</div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
