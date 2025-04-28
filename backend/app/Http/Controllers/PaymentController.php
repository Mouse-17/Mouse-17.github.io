<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \MService\Payment\Shared\SharedModels\Environment;
use \MService\Payment\Shared\SharedModels\PartnerInfo;

class PaymentController extends Controller
{
    public function processPayment(Request $request)
    {
        $amount = $request->input('amount') ?? 20000; // Số tiền thanh toán
        $description = $request->input('description') ?? "THanh toan"; // Thông tin đơn hàng
        $partnerCode = "MOMO";
        $accessKey = "F8BBA842ECF85";
        $secretKey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";

        $requestId = $partnerCode . time();
        $orderId = $requestId;
        $orderInfo = "pay with MoMo";
        $redirectUrl = "https://momo.vn/return";
        $ipnUrl = "https://callback.url/notify";
        $extraData = ""; //pass empty value if your merchant does not have stores
        $requestType = "captureWallet";

        // Tạo raw signature
        $rawSignature = "accessKey=" . $accessKey .
            "&amount=" . $amount .
            "&extraData=" . $extraData .
            "&ipnUrl=" . $ipnUrl .
            "&orderId=" . $orderId .
            "&orderInfo=" . $orderInfo .
            "&partnerCode=" . $partnerCode .
            "&redirectUrl=" . $redirectUrl .
            "&requestId=" . $requestId .
            "&requestType=" . $requestType;

        // Ký HMAC SHA256
        $signature = hash_hmac('sha256', $rawSignature, $secretKey);

        // Dữ liệu JSON gửi đến MoMo
        $requestBody = json_encode([
            'partnerCode' => $partnerCode,
            'accessKey' => $accessKey,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
            'lang' => 'en'
        ]);

        // Tạo request cURL
        $ch = curl_init('https://test-payment.momo.vn/v2/gateway/api/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($requestBody)
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestBody);

        // Gửi request và nhận response
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $response;

        if ($httpCode === 200) {
            $responseBody = json_decode($response, true);
            if (isset($responseBody['payUrl'])) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'URL thanh toán MoMo được tạo thành công',
                    'payUrl' => $responseBody['payUrl'],
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Lỗi từ MoMo: ' . ($responseBody['message'] ?? 'Không rõ lỗi'),
                ], 400);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi kết nối tới MoMo: HTTP Code ' . $httpCode,
            ], 500);
        }
    }
}
