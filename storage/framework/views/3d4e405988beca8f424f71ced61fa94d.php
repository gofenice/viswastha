<!DOCTYPE html>
<html>
<head>
    <title>New Product Delivery Request</title>
</head>
<body>
    <h2>New Delivery Request</h2>
    <p>A new product needs to be delivered. Below are the details:</p>
    
    <p><strong>Customer Name:</strong> <?php echo e($user_name); ?></p>
    <p><strong>Product:</strong> <?php echo e($product_name); ?></p>
    <p><strong>Package:</strong> <?php echo e($package_name); ?></p>
    <p><strong>Delivery Address:</strong> <?php echo e($address); ?></p>
    <p><strong>Phone Number:</strong> <?php echo e($phone_no); ?></p>
    <p><strong>Customer Email:</strong> <?php echo e($user_email); ?></p>

    <p><strong>Product Image:</strong></p>
    <img src="<?php echo e($product_image); ?>" alt="Product Image" style="max-width: 50%; height: auto;">

    <p>Please proceed with the delivery process.</p>
</body>
</html>
<?php /**PATH /Users/shiyasnazar/php/vishwastha/resources/views/emails/delivery_partner_notification.blade.php ENDPATH**/ ?>