<!DOCTYPE html>
<html>
<head>
    <title>Order Status Update</title>
</head>
<body>
    <h2>Dear {{ $user_name }},</h2>
    <p>Your order status has been updated. Here are the details:</p>
    
    <p><strong>Product:</strong> {{ $product_name }}</p>
    <p><strong>Package:</strong> {{ $package_name }}</p>
    <p><strong>Delivery Address:</strong> {{ $address }}</p>
    <p><strong>Phone Number:</strong> {{ $phone_no }}</p>
    <p><strong>Order Status:</strong> {{ $status }}</p>

    <p><strong>Product Image:</strong></p>
    <img src="{{ $product_image }}" alt="Product Image" style="max-width: 50%; height: auto;">

    <p>Thank you for shopping with us!</p>
</body>
</html>
