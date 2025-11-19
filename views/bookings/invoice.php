<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn - <?= htmlspecialchars($booking['booking_code']) ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
            .page-break { page-break-after: always; }
        }
        body {
            font-family: 'Times New Roman', serif;
            padding: 20px;
            font-size: 14px;
            line-height: 1.6;
        }
        .no-print {
            margin-bottom: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
            letter-spacing: 2px;
        }
        .header .subtitle {
            margin-top: 10px;
            font-size: 18px;
            color: #d32f2f;
            font-weight: bold;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
        }
        .invoice-number {
            text-align: right;
        }
        .invoice-number strong {
            font-size: 16px;
            color: #333;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
            color: #333;
        }
        .info-value {
            flex: 1;
            color: #555;
        }
        .table-section {
            margin: 30px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #333;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #d32f2f;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            margin-top: 20px;
            padding: 20px;
            background-color: #fff3e0;
            border: 2px solid #d32f2f;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 16px;
            padding: 8px 0;
        }
        .total-row.deposit {
            color: #1976d2;
            border-bottom: 1px dashed #999;
            padding-bottom: 12px;
        }
        .total-row.remaining {
            color: #f57c00;
            border-bottom: 1px dashed #999;
            padding-bottom: 12px;
        }
        .total-row.final {
            font-size: 20px;
            font-weight: bold;
            color: #d32f2f;
            border-top: 2px solid #d32f2f;
            padding-top: 15px;
            margin-top: 15px;
        }
        .payment-status {
            margin-top: 20px;
            padding: 15px;
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
        }
        .payment-status h4 {
            margin-top: 0;
            color: #2e7d32;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-box h4 {
            margin-bottom: 60px;
            font-size: 14px;
            font-weight: bold;
        }
        .notes {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid #ff9800;
        }
        .notes h4 {
            margin-top: 0;
            color: #e65100;
        }
        .company-info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #d32f2f; color: white; border: none; cursor: pointer; border-radius: 5px; margin-right: 10px;">
            <i class="bi bi-printer"></i> In hóa đơn
        </button>
        <a href="<?= BASE_URL ?>?action=bookings/view&id=<?= $booking['id'] ?>" 
           style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
            Quay lại
        </a>
    </div>

    <div class="header">
        <h1>HÓA ĐƠN</h1>
        <div class="subtitle">INVOICE</div>
    </div>

    <div class="invoice-info">
        <div>
            <div class="company-info">
                <strong>CÔNG TY DU LỊCH</strong><br>
                Địa chỉ: [Địa chỉ công ty]<br>
                Điện thoại: [Số điện thoại] | Email: [Email công ty]
            </div>
        </div>
        <div class="invoice-number">
            <strong>Số hóa đơn:</strong> <?= htmlspecialchars($booking['booking_code']) ?><br>
            <strong>Ngày xuất:</strong> <?= date('d/m/Y') ?><br>
            <strong>Mã booking:</strong> <?= htmlspecialchars($booking['booking_code']) ?>
        </div>
    </div>

    <div class="info-section">
        <h3>THÔNG TIN KHÁCH HÀNG</h3>
        <div class="info-row">
            <div class="info-label">Tên khách hàng:</div>
            <div class="info-value"><strong><?= htmlspecialchars($booking['customer_name']) ?></strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Số điện thoại:</div>
            <div class="info-value"><?= htmlspecialchars($booking['customer_phone'] ?? 'N/A') ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Email:</div>
            <div class="info-value"><?= htmlspecialchars($booking['customer_email'] ?? 'N/A') ?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Địa chỉ:</div>
            <div class="info-value"><?= htmlspecialchars($booking['customer_address'] ?? 'N/A') ?></div>
        </div>
    </div>

    <div class="info-section">
        <h3>THÔNG TIN TOUR</h3>
        <div class="info-row">
            <div class="info-label">Tên tour:</div>
            <div class="info-value"><strong><?= htmlspecialchars($tour['name']) ?></strong></div>
        </div>
        <div class="info-row">
            <div class="info-label">Mã tour:</div>
            <div class="info-value"><?= htmlspecialchars($tour['code']) ?></div>
        </div>
        <?php if ($tour['start_date']): ?>
        <div class="info-row">
            <div class="info-label">Ngày khởi hành:</div>
            <div class="info-value"><?= date('d/m/Y', strtotime($tour['start_date'])) ?></div>
        </div>
        <?php endif; ?>
        <?php if ($tour['end_date']): ?>
        <div class="info-row">
            <div class="info-label">Ngày kết thúc:</div>
            <div class="info-value"><?= date('d/m/Y', strtotime($tour['end_date'])) ?></div>
        </div>
        <?php endif; ?>
        <div class="info-row">
            <div class="info-label">Số lượng khách:</div>
            <div class="info-value"><?= $booking['number_of_guests'] ?> người</div>
        </div>
    </div>

    <div class="table-section">
        <h3>CHI TIẾT DỊCH VỤ</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">STT</th>
                    <th style="width: 50%;">Nội dung dịch vụ</th>
                    <th style="width: 15%;">Số lượng</th>
                    <th style="width: 15%;">Đơn giá</th>
                    <th style="width: 15%;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Tour: <?= htmlspecialchars($tour['name']) ?><br>
                        <small style="color: #666;">
                            <?php if ($tour['start_date'] && $tour['end_date']): ?>
                                Từ <?= date('d/m/Y', strtotime($tour['start_date'])) ?> đến <?= date('d/m/Y', strtotime($tour['end_date'])) ?>
                            <?php endif; ?>
                        </small>
                    </td>
                    <td class="text-center"><?= $booking['number_of_guests'] ?> người</td>
                    <td class="text-right"><?= number_format($booking['total_amount'] / $booking['number_of_guests'], 0, ',', '.') ?> đ</td>
                    <td class="text-right"><strong><?= number_format($booking['total_amount'], 0, ',', '.') ?> đ</strong></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <div class="total-row final">
            <span>TỔNG CỘNG:</span>
            <span><?= number_format($booking['total_amount'], 0, ',', '.') ?> đ</span>
        </div>
        <div class="total-row deposit">
            <span>Đã thanh toán (tiền cọc):</span>
            <span><?= number_format($booking['deposit_amount'], 0, ',', '.') ?> đ</span>
        </div>
        <div class="total-row remaining">
            <span>Còn lại phải thanh toán:</span>
            <span><strong><?= number_format($booking['remaining_amount'], 0, ',', '.') ?> đ</strong></span>
        </div>
    </div>

    <div class="payment-status">
        <h4>Trạng thái thanh toán:</h4>
        <p>
            <?php
            $statusText = [
                'pending' => 'Chưa thanh toán',
                'deposited' => 'Đã đặt cọc',
                'confirmed' => 'Đã xác nhận',
                'completed' => 'Đã thanh toán đủ',
                'cancelled' => 'Đã hủy'
            ];
            echo $statusText[$booking['status']] ?? $booking['status'];
            ?>
        </p>
    </div>

    <?php if ($booking['special_requests']): ?>
    <div class="notes">
        <h4>Yêu cầu đặc biệt:</h4>
        <p><?= nl2br(htmlspecialchars($booking['special_requests'])) ?></p>
    </div>
    <?php endif; ?>

    <?php if ($booking['notes']): ?>
    <div class="notes">
        <h4>Ghi chú:</h4>
        <p><?= nl2br(htmlspecialchars($booking['notes'])) ?></p>
    </div>
    <?php endif; ?>

    <div class="footer">
        <div class="signature-box">
            <h4>NGƯỜI MUA HÀNG</h4>
            <p>(Ký và ghi rõ họ tên)</p>
            <p style="margin-top: 40px;"><?= htmlspecialchars($booking['customer_name']) ?></p>
        </div>
        <div class="signature-box">
            <h4>NGƯỜI BÁN HÀNG</h4>
            <p>(Ký và ghi rõ họ tên)</p>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 15px;">
        <p><em>Cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi!</em></p>
        <p>Ngày xuất hóa đơn: <?= date('d/m/Y H:i') ?></p>
    </div>
</body>
</html>

