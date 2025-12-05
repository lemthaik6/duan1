# Sửa lỗi SQLSTATE[HY093]: Invalid parameter number

## Mô tả lỗi

```
SQLSTATE[HY093]: Invalid parameter number: number of bound variables does not match number of tokens
```

**Dịch**: Số lượng biến được bind không khớp với số lượng tokens (placeholders) trong câu SQL

## Nguyên nhân

Lỗi này xảy ra khi:
- Số lượng `?` hoặc `:param` trong câu SQL **không bằng** số lượng tham số được truyền vào `execute()`
- Hoặc các tên tham số không khớp với nhau

### Ví dụ sai:

```php
$sql = "INSERT INTO table (name, email, phone) VALUES (:name, :email, :phone)";
$stmt = $this->pdo->prepare($sql);
$stmt->execute(['name' => 'John']); // ❌ Thiếu email và phone
```

```php
$sql = "SELECT * FROM users WHERE id = :id AND status = :status";
$stmt = $this->pdo->prepare($sql);
$stmt->execute(['id' => 1]); // ❌ Thiếu status
```

## Lỗi trong project

### File: `models/TourCostModel.php` - Hàm `create()`

**Vấn đề**: Khi thêm chi phí, code cũ thêm field vào `$fields` nhưng lại kiểm tra `if (!empty($data[...]))` SAU khi đã thêm, dẫn đến không có giá trị tương ứng trong `$params`:

```php
// ❌ SAI - Sắp xếp không hợp lý
$fields[] = 'tour_id';
$params['tour_id'] = $data['tour_id']; // có thể null nếu không có key này

// ...

if (!empty($data['description'])) { // kiểm tra SAU khi đã add field
    $fields[] = 'description';
    $params['description'] = $data['description'];
}
```

Khi `$_POST['description']` trống (rỗng), nó không được thêm vào `$params`, nhưng lại có `:description` trong câu SQL → lỗi!

## Giải pháp

### ✅ Cách 1: Luôn thêm tất cả tham số (GIẢI PHÁP ĐÃ ÁP DỤNG)

```php
public function create($data)
{
    $fields = [];
    $params = [];

    // Các trường bắt buộc - luôn thêm
    $fields[] = 'tour_id';
    $params['tour_id'] = $data['tour_id'] ?? null;

    $fields[] = 'cost_category_id';
    $params['cost_category_id'] = $data['cost_category_id'] ?? null;

    $fields[] = 'amount';
    $params['amount'] = $data['amount'] ?? 0;

    $fields[] = 'date';
    $params['date'] = $data['date'] ?? date('Y-m-d');

    $fields[] = 'created_by';
    $params['created_by'] = $data['created_by'] ?? null;

    // Các trường không bắt buộc - kiểm tra TRƯỚC khi thêm
    if (!empty($data['description'])) {
        $fields[] = 'description';
        $params['description'] = $data['description'];
    }

    if (!empty($data['invoice_image'])) {
        $fields[] = 'invoice_image';
        $params['invoice_image'] = $data['invoice_image'];
    }

    $placeholders = implode(', ', array_map(fn($f) => ":$f", $fields));
    $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") 
            VALUES (" . $placeholders . ")";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute($params); // ✅ Số params khớp với placeholders
}
```

**Khác biệt quan trọng**:
- Kiểm tra `if (!empty(...))` **TRƯỚC** khi thêm field vào `$fields`
- Luôn có giá trị default cho các trường bắt buộc: `?? null` hoặc `?? 0`
- Đảm bảo: **Số $fields = Số $params**

### ✅ Cách 2: Sử dụng SQL tĩnh (an toàn nhất)

```php
public function create($data)
{
    $sql = "INSERT INTO tour_costs 
            (tour_id, cost_category_id, amount, date, created_by, description, invoice_image) 
            VALUES (:tour_id, :cost_category_id, :amount, :date, :created_by, :description, :invoice_image)";
    
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        'tour_id' => $data['tour_id'] ?? null,
        'cost_category_id' => $data['cost_category_id'] ?? null,
        'amount' => $data['amount'] ?? 0,
        'date' => $data['date'] ?? date('Y-m-d'),
        'created_by' => $data['created_by'] ?? null,
        'description' => $data['description'] ?? null,
        'invoice_image' => $data['invoice_image'] ?? null,
    ]);
}
```

## Quy tắc chung

1. **Đếm placeholders**: Số `?` hoặc `:name` trong SQL
2. **Đếm params**: Số key trong mảng `$params`
3. **Phải bằng nhau**: `count(placeholders) === count($params)`
4. **Tên phải khớp**: Nếu dùng named placeholders `:name`, key phải là `'name'`

## Cách phòng tránh

✅ **Tốt**:
- Kiểm tra `if (!empty())` **trước** khi thêm field
- Sử dụng named placeholders (`:param`) thay vì `?`
- Luôn cung cấp giá trị default: `?? null`
- Viết unit test cho các hàm database

❌ **Tránh**:
- Thêm field rồi mới kiểm tra có dữ liệu không
- Quên một field nhưng để nó trong SQL
- Dùng biến không defined: `$data['field']` mà không check exists

## Testing

Để test lỗi này:

```php
// Test: Tạo chi phí mà không có description
$costModel = new TourCostModel();
$data = [
    'tour_id' => 1,
    'cost_category_id' => 1,
    'amount' => 100000,
    'created_by' => 1
    // description không có
];
$costModel->create($data); // ✅ Giờ đây không bị lỗi
```

## Tài liệu tham khảo

- [PDO Prepared Statements](https://www.php.net/manual/en/pdo.prepared-statements.php)
- [Named Placeholders](https://www.php.net/manual/en/pdo.prepared-statements.php#example.pdo.prepared-statements.named-placeholders)
