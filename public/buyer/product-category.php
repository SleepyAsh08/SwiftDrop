<?php
// Fetch categories from API
$api_url = 'http://192.168.1.9:8080/categories/all';
$response = file_get_contents($api_url);
$categories = json_decode($response, true);  // Decode the categories JSON response

// Check if a category_id is set in the URL
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Initialize products array
$products = [];

if ($category_id) {
    // Fetch products for the selected category using API (or from your database)
    $product_api_url = "http://192.168.1.9:8080/products?category_id={$category_id}";
    $product_response = file_get_contents($product_api_url);
    $products = json_decode($product_response, true);  // Decode the product JSON response
} else {
    // If no category is selected, fetch all active products (you can modify this part to use your own database)
    $product_api_url = "http://192.168.1.9:8080/products";
    $product_response = file_get_contents($product_api_url);
    $products = json_decode($product_response, true);  // Decode the product JSON response
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
                        <?php foreach ($categories as $category): ?>
                            <li>
                                <a href="products.php?category_id=<?php echo $category['id']; ?>">
                                    <?php echo $category['category']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-9">
                <h3>Products</h3>
                <div class="product-list">
                    <?php if (count($products) > 0): ?>
                        <div class="row">
                            <?php foreach ($products as $product): ?>
                                <div class="col-md-4 item item-product-cat">
                                    <div class="inner">
                                        <div class="thumb">
                                            <div class="photo" style="background-image:url(assets/uploads/<?php echo $product['p_featured_photo']; ?>);"></div>
                                            <div class="overlay"></div>
                                        </div>
                                        <div class="text">
                                            <h3><a href="product.php?id=<?php echo $product['id']; ?>"><?php echo $product['name']; ?></a></h3>
                                            <h4>
                                                ₱<?php echo $product['p_current_price']; ?>
                                                <?php if (!empty($product['p_old_price'])): ?>
                                                    <del>₱<?php echo $product['p_old_price']; ?></del>
                                                <?php endif; ?>
                                            </h4>
                                            <div class="rating">
                                                <?php
                                                // Display rating here if needed (adjust this part based on your actual data)
                                                $rating = $product['rating'] ?? 0;
                                                for ($i = 1; $i <= 5; $i++) {
                                                    echo $i <= $rating ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>';
                                                }
                                                ?>
                                            </div>
                                            <?php if ($product['p_qty'] == 0): ?>
                                                <div class="out-of-stock">
                                                    <div class="inner">Out Of Stock</div>
                                                </div>
                                            <?php else: ?>
                                                <p><a href="product.php?id=<?php echo $product['id']; ?>"><i class="fa fa-shopping-cart"></i> Add to Cart</a></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
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
