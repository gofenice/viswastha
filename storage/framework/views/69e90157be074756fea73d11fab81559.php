<!DOCTYPE html>
<html>
<head>
    <title>Product Delivery Confirmation</title>
</head>
<body>
    <h2>Dear <?php echo e($user_name); ?>,</h2>
    <p>Your product selection is successful. Here are the details:</p>
    <p><strong>Product:</strong> <?php echo e($product_name); ?></p>
    <p><strong>Package:</strong> <?php echo e($package_name); ?></p>
    <p><strong>Delivery Address:</strong> <?php echo e($address); ?></p>
    <p><strong>Phone Number:</strong> <?php echo e($phone_no); ?></p>

    <p><strong>Product Image:</strong></p>
    <img src="<?php echo e($product_image); ?>" alt="Product Image" style="max-width: 50%; height: auto;">


    <p>We will update you once the product is shipped.</p>
    <p>Thank you for choosing us!</p>
</body>
</html>
<?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/emails/product_delivery.blade.php ENDPATH**/ ?>