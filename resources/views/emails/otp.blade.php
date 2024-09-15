<!-- resources/views/emails/html.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>OTP Verification</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 90%;
            margin: 0 auto;
            padding: 16px;
        }

        .card {
            background-color: #fff;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.4);
            border-radius: 8px;
            padding: 16px;
            margin: 16px 0;
        }

        .text-center {
            text-align: center;
        }

        .card-title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .text-gray-500 {
            color: #6c757d;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-16">
        <div class="card shadow-lg p-3 mb-5 bg-body-tertiary rounded m-3" style="border: 1px solid gray;">
            <div class="card-body">
                <div class="max-w-lg mx-auto">
                    <div class="text-center">
                        <h1 class="text-3xl font-bold card-title center">kavitas Healling Reanaissance OTP Verification</h1>
                        <p class="text-gray-500 center">This OTP is valid for 60 min.</p>
                    </div>
                    <card class="mt-8">
                        <div class="grid grid-cols-6 gap-4">
                            <div class="col-span-1 text-center">
                                <input type="text" id="otp-1"
                                class="text-center"
                                    style="width: 20%; font-size: 18px; color: gray; box-shadow: 0 6px 7px rgba(0, 0, 0, 0.1); border: none; height: 50px;" readonly
                                    value="{{$details['otp']}}" maxlength="1" />
                            </div>
                        </div>
                        <div class="mt-8">
                            <h5 class="text-center text-gray-500" style="font-size: 12px;">{{$details['contact_details']}}</h5>
                                
                        </div>
                    </card>
                </div>
            </div>
        </div>
    </div>

</body>

</html>