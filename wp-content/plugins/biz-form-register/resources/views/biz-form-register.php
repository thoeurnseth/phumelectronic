<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-12">
                    <div class="otp-card">
                        <div class="otp-title">
                            <h2>Enter OTP</h2>
                        </div>
                        <div class="otp-sumrize">
                            <p>A OTP ( One Time Passcode ) has been sent to <label> '.$phone.'</label></p>
                        </div>
                        <div class="otp-sumrize-1">
                            <p>Please enter the OTP below to verify your phone number.</p>
                        </div>
                        <div class="otp-input">
                            <div class="otp-wrapper otp-event">
                                <div class="otp-container">
                                    <input type="tel" id="otp-number-input-1" class="otp-number-input" maxlength="1" autocomplete="off">
                                    <input type="tel" id="otp-number-input-2" class="otp-number-input" maxlength="1" autocomplete="off">
                                    <input type="tel" id="otp-number-input-3" class="otp-number-input" maxlength="1" autocomplete="off">
                                    <input type="tel" id="otp-number-input-4" class="otp-number-input" maxlength="1" autocomplete="off">
                                    <input type="tel" id="otp-number-input-5" class="otp-number-input" maxlength="1" autocomplete="off">
                                    <input type="tel" id="otp-number-input-6" class="otp-number-input" maxlength="1" autocomplete="off">
                                </div>
                                <div class="otp-resend">
                                    <p>Resent OTP : ( <span id="timer"></span> )</p>
                                </div>
                            <div>

                            <form action="' . $_SERVER['REQUEST_URI'] . '&otp_form=true#sign_up" method="post" class="text-center woocommerce-form woocommerce-form-register register">
                                <input type="hidden" name="otp_number" id="otp_number"/>
                                <input type="hidden" name="phone" value="' . $phone . '"/>
                                <input type="hidden" name="user_id" value="' . $user_id . '"/>
                                <button id="confirm" type="submit" class="otp-submit" disabled>Validate OTP</button> 
                            </form>

                        </div>
                    </div>
                </div>
            </div>       
        </div>

</body>
</html>