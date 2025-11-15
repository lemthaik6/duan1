<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="bi bi-graph-up"></i> Báo cáo & Thống kê</h2>
    </div>

    <!-- Bộ lọc -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <input type="hidden" name="action" value="reports/index">
                <div class="col-md-4">
                    <label class="form-label">Năm</label>
                    <select name="year" class="form-select" onchange="this.form.submit()">
                        <?php 
                        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
                        for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                            <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Thống kê theo tháng -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Thống kê tour theo tháng (Năm <?= $year ?>)</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($stats)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tháng</th>
                                        <th>Tổng số tour</th>
                                        <th>Tour đã hoàn thành</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($stats as $stat): ?>
                                        <tr>
                                            <td>Tháng <?= $stat['month'] ?></td>
                                            <td><span class="badge bg-primary"><?= $stat['total_tours'] ?></span></td>
                                            <td><span class="badge bg-success"><?= $stat['completed_tours'] ?></span></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Không có dữ liệu</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

