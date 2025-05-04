<?php
session_start();

function generateOTP() {
    return rand(100000, 999999);
}

if (isset($_SESSION['otp_for'], $_SESSION['mobile'], $_SESSION['email'])) {
    $otp = generateOTP();
    $_SESSION['otp'] = $otp;

    $mobile = $_SESSION['mobile'];
    $message = "Your Hexshop OTP is $otp. Please do not share it with anyone.";

    // ✅ Prepare URL for Fast2SMS GET API
    $encodedMessage = urlencode($message);
    $apiKey = "GxuiSLNEpBRa1v4fmMCVU3AF52qeo7bcQ89grkyK0wTjYnXhIzlc6Up8tvI7dRgSaJjCXDh2YzWBK9G1"; // 🔒 Replace with your real Fast2SMS API key

    $url = "https://www.fast2sms.com/dev/bulkV2?authorization=$apiKey&sender_id=FSTSMS&message=$encodedMessage&language=english&route=p&numbers=$mobile";

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
    ]);

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        echo "❌ cURL Error: " . curl_error($curl);
    } else {
        echo "✅ Fast2SMS Response: " . $response;
    }

    curl_close($curl);

    // Show OTP in pop-up using JavaScript
    echo "<script type='text/javascript'>
            alert('Your OTP is: $otp');
            window.location.href = 'verify_otp.php'; // Redirect to OTP verification page
          </script>";

} else {
    echo "❌ Missing OTP context (mobile, email, or otp_for not set in session).";
}
?>
