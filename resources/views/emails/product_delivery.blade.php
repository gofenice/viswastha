<!DOCTYPE html>
<html>
<head>
    <title>Product Delivery Confirmation</title>
</head>
<body>
    <h2>Dear {{ $user_name }},</h2>
    <p>Your product selection is successful. Here are the details:</p>
    <p><strong>Product:</strong> {{ $product_name }}</p>
    <p><strong>Package:</strong> {{ $package_name }}</p>
    <p><strong>Delivery Address:</strong> {{ $address }}</p>
    <p><strong>Phone Number:</strong> {{ $phone_no }}</p>

    <p><strong>Product Image:</strong></p>
    <img src="{{ $product_image }}" alt="Product Image" style="max-width: 50%; height: auto;">


    <p>We will update you once the product is shipped.</p>
    <p>Thank you for choosing us!</p>
</body>
</html>
