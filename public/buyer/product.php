<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else {
    // Check the id is valid or not
    $statement = $pdo->prepare("SELECT * FROM products WHERE id=?");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if( $total == 0 ) {
        header('location: index.php');
        exit;
    }
}

foreach($result as $row) {
    $p_name = $row['Product_Name'];
    $p_current_price = $row['price'];
    $p_qty = $row['Quantity'];
    $p_featured_photo = $row['photos'];
    $photo2 = $row['photos1'];
    $photo3 = $row['photos2'];
    $p_description = $row['Description'];
    // $p_total_view = $row['p_total_view'];
    // $p_is_featured = $row['p_is_featured'];
    // $p_is_active = $row['p_is_active'];
    // $ecat_id = $row['ecat_id'];
}





if(isset($_POST['form_add_to_cart'])) {

	// getting the currect stock of this product
	$statement = $pdo->prepare("SELECT * FROM products WHERE id=?");
	$statement->execute(array($_REQUEST['id']));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach ($result as $row) {
		$current_p_qty = $row['Quantity'];
	}
	if($_POST['p_Quantityqty'] > $current_p_qty):
		$temp_msg = 'Sorry! There are only '.$current_p_qty.' item(s) in stock';
		?>
		<script type="text/javascript">alert('<?php echo $temp_msg; ?>');</script>
		<?php
	else:
    if(isset($_SESSION['cart_p_id']))
    {
        $arr_cart_p_id = array();
        $arr_cart_size_id = array();
        $arr_cart_color_id = array();
        $arr_cart_p_qty = array();
        $arr_cart_p_current_price = array();

        $i=0;
        foreach($_SESSION['cart_p_id'] as $key => $value)
        {
            $i++;
            $arr_cart_p_id[$i] = $value;
        }

        $i=0;
        foreach($_SESSION['cart_size_id'] as $key => $value)
        {
            $i++;
            $arr_cart_size_id[$i] = $value;
        }

        $i=0;
        foreach($_SESSION['cart_color_id'] as $key => $value)
        {
            $i++;
            $arr_cart_color_id[$i] = $value;
        }


        $added = 0;
        if(!isset($_POST['size_id'])) {
            $size_id = 0;
        } else {
            $size_id = $_POST['size_id'];
        }
        if(!isset($_POST['color_id'])) {
            $color_id = 0;
        } else {
            $color_id = $_POST['color_id'];
        }
        for($i=1;$i<=count($arr_cart_p_id);$i++) {
            if( ($arr_cart_p_id[$i]==$_REQUEST['id']) && ($arr_cart_size_id[$i]==$size_id) && ($arr_cart_color_id[$i]==$color_id) ) {
                $added = 1;
                break;
            }
        }
        if($added == 1) {
           $error_message1 = 'This product is already added to the shopping cart.';
        } else {

            $i=0;
            foreach($_SESSION['cart_p_id'] as $key => $res)
            {
                $i++;
            }
            $new_key = $i+1;

            if(isset($_POST['size_id'])) {

                $size_id = $_POST['size_id'];

                $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
                $statement->execute(array($size_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $size_name = $row['size_name'];
                }
            } else {
                $size_id = 0;
                $size_name = '';
            }

            if(isset($_POST['color_id'])) {
                $color_id = $_POST['color_id'];
                $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
                $statement->execute(array($color_id));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    $color_name = $row['color_name'];
                }
            } else {
                $color_id = 0;
                $color_name = '';
            }


            $_SESSION['cart_p_id'][$new_key] = $_REQUEST['id'];
            $_SESSION['cart_size_id'][$new_key] = $size_id;
            $_SESSION['cart_size_name'][$new_key] = $size_name;
            $_SESSION['cart_color_id'][$new_key] = $color_id;
            $_SESSION['cart_color_name'][$new_key] = $color_name;
            $_SESSION['cart_p_qty'][$new_key] = $_POST['p_qty'];
            $_SESSION['cart_p_current_price'][$new_key] = $_POST['p_current_price'];
            $_SESSION['cart_p_name'][$new_key] = $_POST['p_name'];
            $_SESSION['cart_p_featured_photo'][$new_key] = $_POST['p_featured_photo'];

            $success_message1 = 'Product is added to the cart successfully!';
        }

    }
    else
    {

        if(isset($_POST['size_id'])) {

            $size_id = $_POST['size_id'];

            $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
            $statement->execute(array($size_id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $size_name = $row['size_name'];
            }
        } else {
            $size_id = 0;
            $size_name = '';
        }

        if(isset($_POST['color_id'])) {
            $color_id = $_POST['color_id'];
            $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
            $statement->execute(array($color_id));
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                $color_name = $row['color_name'];
            }
        } else {
            $color_id = 0;
            $color_name = '';
        }


        $_SESSION['cart_p_id'][1] = $_REQUEST['id'];
        $_SESSION['cart_size_id'][1] = $size_id;
        $_SESSION['cart_size_name'][1] = $size_name;
        $_SESSION['cart_color_id'][1] = $color_id;
        $_SESSION['cart_color_name'][1] = $color_name;
        $_SESSION['cart_p_qty'][1] = $_POST['p_qty'];
        $_SESSION['cart_p_current_price'][1] = $_POST['p_current_price'];
        $_SESSION['cart_p_name'][1] = $_POST['p_name'];
        $_SESSION['cart_p_featured_photo'][1] = $_POST['p_featured_photo'];

        $success_message1 = 'Product is added to the cart successfully!';
    }
	endif;
}
?>

<?php
if($error_message1 != '') {
    echo "<script>alert('".$error_message1."')</script>";
}
if($success_message1 != '') {
    echo "<script>alert('".$success_message1."')</script>";
    header('location: product.php?id='.$_REQUEST['id']);
}
?>


<div class="page">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
                <div class="breadcrumb mb_30">
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Home</a></li>
                        <li>></li>
                        <li><?php echo $p_name; ?></li>
                    </ul>
                </div>

				<div class="product">
					<div class="row">
						<div class="col-md-5">
							<ul class="prod-slider">

                            <li style="background-image: url(http://192.168.1.101:8080/storage/<?php echo str_replace('\/', '/', trim($p_featured_photo, '[]"')); ?>);">
                                <a class="popup" href="http://192.168.1.101:8080/storage/<?php echo str_replace('\/', '/', trim($p_featured_photo, '[]"')); ?>"></a>
                            </li>

							</ul>
							<div id="prod-pager">
								<?php
                                $i=1;
                                $statement = $pdo->prepare("SELECT * FROM products WHERE id=?");
                                $statement->execute(array($_REQUEST['id']));
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    ?>
                                    <a data-slide-index="<?php echo $i; ?>" href=""><div class="prod-pager-thumb" style="background-image: url(http://192.168.1.9:8080/storage/<?php echo str_replace('\/', '/', trim($p_featured_photo, '[]"')); ?>"></div></a>
                                    <a data-slide-index="<?php echo $i; ?>" href=""><div class="prod-pager-thumb" style="background-image: url(http://192.168.1.9:8080/storage/<?php echo str_replace('\/', '/', trim($photo2, '[]"')); ?>"></div></a>
                                    <a data-slide-index="<?php echo $i; ?>" href=""><div class="prod-pager-thumb" style="background-image: url(http://192.168.1.9:8080/storage/<?php echo str_replace('\/', '/', trim($photo3, '[]"')); ?>"></div></a>
                                    <?php
                                    $i++;
                                }
                                ?>
							</div>
						</div>
						<div class="col-md-7">
							<div class="p-title"><h2><?php echo $p_name; ?></h2></div>
							<div class="p-review">
								<div class="rating">

                                </div>
							</div>
							<div class="p-short-des">
								<p>
									<?php echo $p_description; ?>
								</p>
							</div>
                            <form action="" method="post">
                            <div class="p-quantity">
                                <div class="row">
                                    <?php if(isset($size)): ?>
                                    <div class="col-md-12 mb_20">
                                        <?php echo LANG_VALUE_52; ?> <br>
                                        <select name="size_id" class="form-control select2" style="width:auto;">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_size");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                if(in_array($row['size_id'],$size)) {
                                                    ?>
                                                    <option value="<?php echo $row['size_id']; ?>"><?php echo $row['size_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>

                                    <?php if(isset($color)): ?>
                                    <div class="col-md-12">
                                        <?php echo LANG_VALUE_53; ?> <br>
                                        <select name="color_id" class="form-control select2" style="width:auto;">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_color");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) {
                                                if(in_array($row['color_id'],$color)) {
                                                    ?>
                                                    <option value="<?php echo $row['color_id']; ?>"><?php echo $row['color_name']; ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <?php endif; ?>

                                </div>

                            </div>
							<div class="p-price">
                                <span style="font-size:14px;"><?php echo LANG_VALUE_54; ?></span><br>
                                <span>

                                        <?php echo LANG_VALUE_1; ?><?php echo $p_current_price; ?>s
                                </span>
                            </div>
                            <input type="hidden" name="p_current_price" value="<?php echo $p_current_price; ?>">
                            <input type="hidden" name="p_name" value="<?php echo $p_name; ?>">
                            <input type="hidden" name="p_featured_photo" value="<?php echo $p_featured_photo; ?>">
							<div class="p-quantity">
                                <?php echo LANG_VALUE_55; ?> <br>
								<input type="number" class="input-text qty" step="1" min="1" max="" name="p_qty" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
							</div>
							<div class="btn-cart btn-cart1">
                                <input type="submit" value="<?php echo LANG_VALUE_154; ?>" name="form_add_to_cart">
							</div>
                            </form>

						</div>
					</div>



				</div>

			</div>
		</div>
	</div>
</div>

<div class="product bg-gray pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="headline">
                    <h2><?php echo LANG_VALUE_155; ?></h2>
                    <h3><?php echo LANG_VALUE_156; ?></h3>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">

                <div class="product-carousel">

                    <?php
                    $statement = $pdo->prepare("SELECT * FROM products WHERE id!=?");
                    $statement->execute(array($_REQUEST['id']));
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        ?>
                        <div class="item">
                            <div class="thumb">

                            <?php
// Decode the JSON and extract the first photo URL
                                            $photos = json_decode($row['photos'], true);

                                            if (!empty($photos[0])) {
                                                $photoUrl = str_replace('\/', '/', $photos[0]);
                                                echo '<div class="photo" style="background-image:url(http://192.168.1.101:8080/storage/' . $photoUrl . ');"></div>';
                                            } else {
                                                echo '<div class="photo" style="background-color: gray;">No photo available</div>';
                                            }
                                            ?>
                            <!-- <div class="photo" style="background-image:url(http://192.168.1.9:8080/storage/<?php echo str_replace('\/', '/', trim($row['photos'][0])); ?>);"></div> -->
                                <div class="overlay"></div>
                            </div>
                            <div class="text">
                                <h3><a href="product.php?id=<?php echo $row['id']; ?>"><?php echo $row['Product_Name']; ?></a></h3>
                                <h4>
                                    <?php echo LANG_VALUE_1; ?><?php echo $row['price']; ?>

                                </h4>
                                <p><a href="product.php?id=<?php echo $row['id']; ?>"><?php echo LANG_VALUE_154; ?></a></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>
