<?php

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new TourCategoryModel();
    }
    public function index()
    {
        requireAdmin();
        
        $categories = $this->categoryModel->getAll();
        
        $title = 'Quản lý Danh mục Tour';
        $view = 'categories/index';
        require_once PATH_VIEW_MAIN;
    }
    public function create()
    {
        requireAdmin();
        
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => $_POST['status'] ?? 'active'
            ];

            if (empty($data['name'])) {
                $error = 'Vui lòng nhập tên danh mục';
            } else {
                if ($this->categoryModel->create($data)) {
                    $success = 'Tạo danh mục thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=categories/index');
                } else {
                    $error = 'Có lỗi xảy ra khi tạo danh mục';
                }
            }
        }
        
        $title = 'Tạo danh mục mới';
        $view = 'categories/create';
        require_once PATH_VIEW_MAIN;
    }
    public function edit()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        $category = $this->categoryModel->getById($id);
        
        if (!$category) {
            header('Location: ' . BASE_URL . '?action=categories/index');
            exit;
        }

        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'description' => $_POST['description'] ?? '',
                'status' => $_POST['status'] ?? 'active'
            ];

            if (empty($data['name'])) {
                $error = 'Vui lòng nhập tên danh mục';
            } else {
                if ($this->categoryModel->update($id, $data)) {
                    $success = 'Cập nhật danh mục thành công!';
                    header('refresh:2;url=' . BASE_URL . '?action=categories/index');
                } else {
                    $error = 'Có lỗi xảy ra khi cập nhật';
                }
            }
        }
        
        $title = 'Chỉnh sửa danh mục';
        $view = 'categories/edit';
        require_once PATH_VIEW_MAIN;
    }
    public function delete()
    {
        requireAdmin();
        
        $id = $_GET['id'] ?? 0;
        
        if ($this->categoryModel->delete($id)) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa danh mục';
        }
        
        header('Location: ' . BASE_URL . '?action=categories/index');
        exit;
    }
}

