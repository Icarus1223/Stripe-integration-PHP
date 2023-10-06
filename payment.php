<?php
require_once 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stripe = new \Stripe\StripeClient('sk_test_QoMBojLxDJWlKhKWaeWG0jLc');

    // Retrieve data from the form
    $amount = $_POST["amount"];
    $fname = $_POST["fname"];
    $lname = $_POST["lname"];
    $email = $_POST["email"];
    $cardNumber = $_POST["cardNumber"];
    $cvc = $_POST["cvc"];
    $expMonth = $_POST["month"];
    $expYear = $_POST["year"];

    try {
        $token = $stripe->tokens->create([
            'card' => [
                'number' => $cardNumber,
                'exp_month' => $expMonth,
                'exp_year' => $expYear,
                'cvc' => $cvc,
            ],
        ]);

        echo $token;
    } catch (Exception $e) {
        echo "Token Create Error". $e->getMessage();
    }

    // try {
    //     $paymentMethod = \Stripe\PaymentMethod::create([
    //         'type' => 'card',
    //         'card' => [
    //             'number' => $cardNumber,
    //             'exp_month' => $expMonth,
    //             'exp_year' => substr($expYear, 2),
    //             'cvc' => $cvc,
    //         ],
    //         'billing_details' => [
    //             'name' => $fname . " " . $lname,
    //             'email' => $email,
    //         ],
    //     ]);

    //     echo "Payment was successful!";
    // } catch (\Stripe\Exception\CardException $e) {
    //     // Payment failed due to a card error
    //     echo "Card Error: " . $e->getMessage();
    // } catch (\Stripe\Exception\InvalidRequestException $e) {
    //     // Invalid parameters were supplied to Stripe's API
    //     echo "Invalid Request: " . $e->getMessage();
    // } catch (\Stripe\Exception\AuthenticationException $e) {
    //     // Authentication with Stripe's API failed
    //     echo "Authentication Error: " . $e->getMessage();
    // } catch (\Stripe\Exception\ApiConnectionException $e) {
    //     // Network communication with Stripe failed
    //     echo "Network Error: " . $e->getMessage();
    // } catch (\Stripe\Exception\ApiErrorException $e) {
    //     // Generic API error
    //     echo "API Error: " . $e->getMessage();
    // } catch (Exception $e) {
    //     // Handle other exceptions
    //     echo "Error: " . $e->getMessage();
    // }
}
?>