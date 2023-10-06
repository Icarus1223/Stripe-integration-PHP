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
        // $stripe->charges->create([
        //     'amount' => ((int) $amount) * 100,
        //     'currency' => 'aud',
        //     'source' => $tokenId,
        //     'description' => 'Stripe Charge',
        // ]);

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

        echo "Success!!!";
    } catch (Exception $e) {
        echo "Stripe Payment Error:" . $e->getMessage();
    }
}
?>