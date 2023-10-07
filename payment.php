<?php
require_once 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stripe = new \Stripe\StripeClient('sk_test_QoMBojLxDJWlKhKWaeWG0jLc');

    // Retrieve data from the form
    $amount = $_POST["amount"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $tokenId = $_POST["tokenId"];

    try {

        $paymenMethod = $stripe->paymentMethods->create([
            'type' => 'card',
            'card' => [
                'token' => $tokenId
            ],
            'billing_details' => [
                'email' => $email,
                'name' => $name
            ]
        ]);

        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => ((int) $amount) * 100,
            'currency' => 'aud',
            'payment_method_types' => ['card'],
            'payment_method' => $paymenMethod['id'],
            'capture_method' => 'automatic',
        ]);

        $stripe->paymentIntents->confirm(
            $paymentIntent['id'],
            [
                'payment_method' => $paymenMethod['id'],
            ]
        );

        echo json_encode(array(
            'msg' => "Success!!!",
            'transactionId' => $paymentIntent['id']
        ));
    } catch (Exception $e) {
        echo json_encode(array(
            'msg' => "Stripe Payment Error:" . $e->getMessage()
        ));
    }
}
?>