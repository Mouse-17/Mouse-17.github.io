import express from 'express';
import crypto from 'crypto';
import https from 'https';

const app = express();
app.use(express.json());

// API MoMo Payment Endpoint
app.post('/api/momo/payment', (req, res) => {
    const { amount, orderInfo, redirectUrl, ipnUrl } = req.body;

    // Thông tin MoMo
    const partnerCode = "MOMO";
    const accessKey = "F8BBA842ECF85";
    const secretkey = "K951B6PE1waDMi640xX08PD3vg6EkVlz";
    const requestId = partnerCode + Date.now();
    const orderId = requestId;
    const requestType = "captureWallet";
    const extraData = ""; // Dữ liệu bổ sung, để trống nếu không sử dụng

    // Tạo raw signature
    const rawSignature = `accessKey=${accessKey}&amount=${amount}&extraData=${extraData}&ipnUrl=${ipnUrl}&orderId=${orderId}&orderInfo=${orderInfo}&partnerCode=${partnerCode}&redirectUrl=${redirectUrl}&requestId=${requestId}&requestType=${requestType}`;

    // Ký SHA256
    const signature = crypto.createHmac('sha256', secretkey).update(rawSignature).digest('hex');

    // Tạo request body
    const requestBody = JSON.stringify({
        partnerCode: partnerCode,
        accessKey: accessKey,
        requestId: requestId,
        amount: amount,
        orderId: orderId,
        orderInfo: orderInfo,
        redirectUrl: redirectUrl,
        ipnUrl: ipnUrl,
        extraData: extraData,
        requestType: requestType,
        signature: signature,
        lang: 'en'
    });

    // Options cho HTTPS request
    const options = {
        hostname: 'test-payment.momo.vn',
        port: 443,
        path: '/v2/gateway/api/create',
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Content-Length': Buffer.byteLength(requestBody)
        }
    };

    // Gửi request đến MoMo
    const reqMoMo = https.request(options, (response) => {
        let data = '';
        response.on('data', (chunk) => {
            data += chunk;
        });

        response.on('end', () => {
            const responseBody = JSON.parse(data);
            if (responseBody.payUrl) {
                res.json({
                    status: 'success',
                    payUrl: responseBody.payUrl
                });
            } else {
                res.status(400).json({
                    status: 'error',
                    message: responseBody.message || 'Không lấy được URL thanh toán từ MoMo.'
                });
            }
        });
    });

    reqMoMo.on('error', (e) => {
        console.error(`Lỗi khi gửi yêu cầu tới MoMo: ${e.message}`);
        res.status(500).json({
            status: 'error',
            message: 'Đã xảy ra lỗi khi kết nối tới MoMo.'
        });
    });

    reqMoMo.write(requestBody);
    reqMoMo.end();
});

// Start server
const PORT = 5555;
const HOST = '0.0.0.0'; // Lắng nghe trên tất cả các địa chỉ IP
app.listen(PORT, HOST, () => {
    console.log(`Server đang chạy trên host ${HOST} và cổng ${PORT}`);
});
