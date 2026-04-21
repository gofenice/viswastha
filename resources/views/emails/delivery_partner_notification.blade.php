<!DOCTYPE html>
<html>
<head>
    <title>New Product Delivery Request</title>
</head>
<body>
    <h2>New Delivery Request</h2>
    <p>A new product needs to be delivered. Below are the details:</p>
    
    <p><strong>Customer Name:</strong> {{ $user_name }}</p>
    <p><strong>Product:</strong> {{ $product_name }}</p>
    <p><strong>Package:</strong> {{ $package_name }}</p>
    <p><strong>Delivery Address:</strong> {{ $address }}</p>
    <p><strong>Phone Number:</strong> {{ $phone_no }}</p>
    <p><strong>Customer Email:</strong> {{ $user_email }}</p>

    <p><strong>Product Image:</strong></p>
    <img src="{{ $product_image }}" alt="Product Image" style="max-width: 50%; height: auto;">

    <p>Please proceed with the delivery process.</p>
</body>
</html>
