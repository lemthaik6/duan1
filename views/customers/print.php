<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách đoàn - <?= htmlspecialchars($tour['name']) ?></title>
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .tour-info {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #667eea; color: white; border: none; cursor: pointer; border-radius: 5px;">
            <i class="bi bi-printer"></i> In danh sách
        </button>
        <a href="<?= BASE_URL ?>?action=customers/index&tour_id=<?= $tour['id'] ?>" 
           style="padding: 10px 20px; background: #6c757d; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">
            Quay lại
        </a>
    </div>

    <div class="header">
        <h1>DANH SÁCH ĐOÀN</h1>
    </div>

    <div class="tour-info">
        <p><strong>Tour:</strong> <?= htmlspecialchars($tour['name']) ?></p>
        <p><strong>Mã tour:</strong> <?= htmlspecialchars($tour['code']) ?></p>
        <p><strong>Ngày khởi hành:</strong> <?= date('d/m/Y', strtotime($tour['start_date'])) ?></p>
        <p><strong>Ngày kết thúc:</strong> <?= date('d/m/Y', strtotime($tour['end_date'])) ?></p>
        <p><strong>Tổng số khách:</strong> <?= count($customers) ?> người</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">STT</th>
                <th style="width: 25%;">Họ và tên</th>
                <th style="width: 15%;">Số điện thoại</th>
                <th style="width: 20%;">Email</th>
                <th style="width: 15%;">CMND/CCCD</th>
                <th style="width: 20%;">Ghi chú</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customers)): ?>
                <?php foreach ($customers as $index => $customer): ?>
                    <tr>
                        <td style="text-align: center;"><?= $index + 1 ?></td>
                        <td><?= htmlspecialchars($customer['full_name']) ?></td>
                        <td><?= htmlspecialchars($customer['phone'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($customer['email'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($customer['id_card'] ?? 'N/A') ?></td>
                        <td>
                            <?= htmlspecialchars($customer['notes'] ?? '') ?>
                            <?php if (isset($customer['special_requests']) && $customer['special_requests']): ?>
                                <br><small style="color: #667eea;">Yêu cầu đặc biệt: <?= htmlspecialchars($customer['special_requests']) ?></small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">Chưa có khách nào</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>In ngày: <?= date('d/m/Y H:i') ?></p>
    </div>
</body>
</html>

