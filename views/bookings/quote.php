<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo giá hợp đồng - <?= htmlspecialchars($booking['booking_code']) ?></title>
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
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
        }
        .header .subtitle {
            margin-top: 10px;
            font-size: 16px;
            color: #666;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
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
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
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
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 15px;
        }
        .total-row.final {
            font-size: 18px;
            font-weight: bold;
            color: #d32f2f;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
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
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #667eea; color: white; border: none; cursor: pointer; border-radius: 5px; margin-right: 10px;">
            <i class="bi bi-printer"></i> In báo giá
        </button>
        <a href="<?= BASE_URL ?>?action=bookings/view&id=<?= $booking['id'] ?>" 
           style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px;">
            Quay lại
        </a>
    </div>

    <div class="header">
        <h1>BÁO GIÁ HỢP ĐỒNG DU LỊCH</h1>
        <div class="subtitle">Mã booking: <?= htmlspecialchars($booking['booking_code']) ?></div>
    </div>

    <div class="info-section">
        <h3>THÔNG TIN KHÁCH HÀNG</h3>
        <div class="info-row">
            <div class="info-label">Tên khách hàng:</div>
            <div class="info-value"><?= htmlspecialchars($booking['customer_name']) ?></div>
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
        <div class="info-row">
            <div class="info-label">Loại booking:</div>
            <div class="info-value"><?= $booking['booking_type'] === 'group' ? 'Đoàn' : 'Khách lẻ' ?></div>
        </div>
    </div>

    <div class="info-section">
        <h3>THÔNG TIN TOUR</h3>
        <div class="info-row">
            <div class="info-label">Tên tour:</div>
            <div class="info-value"><?= htmlspecialchars($tour['name']) ?></div>
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
        <h3>CHI TIẾT BÁO GIÁ</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">STT</th>
                    <th style="width: 50%;">Nội dung</th>
                    <th style="width: 15%;">Số lượng</th>
                    <th style="width: 15%;">Đơn giá</th>
                    <th style="width: 15%;">Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-center">1</td>
                    <td>Tour: <?= htmlspecialchars($tour['name']) ?></td>
                    <td class="text-center"><?= $booking['number_of_guests'] ?> người</td>
                    <td class="text-right"><?= number_format($booking['total_amount'] / $booking['number_of_guests'], 0, ',', '.') ?> đ</td>
                    <td class="text-right"><?= number_format($booking['total_amount'], 0, ',', '.') ?> đ</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <div class="total-row">
            <span><strong>Tổng tiền:</strong></span>
            <span><strong><?= number_format($booking['total_amount'], 0, ',', '.') ?> đ</strong></span>
        </div>
        <div class="total-row">
            <span>Tiền cọc (đề xuất):</span>
            <span><?= number_format($booking['deposit_amount'], 0, ',', '.') ?> đ</span>
        </div>
        <div class="total-row">
            <span>Còn lại:</span>
            <span><?= number_format($booking['remaining_amount'], 0, ',', '.') ?> đ</span>
        </div>
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
            <h4>KHÁCH HÀNG</h4>
            <p>(Ký và ghi rõ họ tên)</p>
        </div>
        <div class="signature-box">
            <h4>ĐẠI DIỆN CÔNG TY</h4>
            <p>(Ký và ghi rõ họ tên)</p>
        </div>
    </div>

    <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
        <p>Báo giá có hiệu lực đến: <?= date('d/m/Y', strtotime('+30 days')) ?></p>
        <p>Ngày lập báo giá: <?= date('d/m/Y H:i') ?></p>
    </div>
</body>
</html>

