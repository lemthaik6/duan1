Truy cập: http://localhost/duan1/database/add_expected_guests_column.php@echo off
REM ==========================================
REM QUICK TEST SECURITY IMPROVEMENTS
REM ==========================================
REM Usage: Run this batch file to test security fixes

echo.
echo ========================================
echo SECURITY IMPROVEMENTS TEST
echo ========================================
echo.

REM Test 1: Check helper functions
echo [1] Checking helper functions...
php -r "require_once 'configs/helper.php'; echo 'Helpers loaded successfully'; echo PHP_EOL;"

if %ERRORLEVEL% NEQ 0 (
    echo ERROR: Failed to load helpers!
    exit /b 1
)

REM Test 2: Check CSRF functions
echo [2] Testing CSRF functions...
php -r "
require_once 'configs/env.php';
require_once 'configs/helper.php';
session_start();
\$token = generateCSRFToken();
echo 'CSRF Token generated: ' . substr(\$token, 0, 10) . '...';
echo PHP_EOL;
"

REM Test 3: Check password validation
echo [3] Testing password validation...
php -r "
require_once 'configs/helper.php';
\$errors = validatePasswordStrength('weak');
echo 'Password validation errors: ' . count(\$errors);
echo PHP_EOL;
echo 'First error: ' . \$errors[0];
echo PHP_EOL;
"

REM Test 4: Check file upload validation
echo [4] Testing file upload setup...
php -r "
require_once 'configs/env.php';
require_once 'configs/helper.php';
echo 'File upload function available: ' . (function_exists('upload_file') ? 'YES' : 'NO');
echo PHP_EOL;
"

REM Test 5: Check XSS protection
echo [5] Testing XSS escape function...
php -r "
require_once 'configs/helper.php';
\$xss = '<script>alert(1)</script>';
\$escaped = e(\$xss);
echo 'XSS Test: ';
echo (strpos(\$escaped, 'script') === false) ? 'SAFE' : 'UNSAFE';
echo PHP_EOL;
"

echo.
echo ========================================
echo ALL TESTS COMPLETED!
echo ========================================
echo.
echo Next steps:
echo 1. Open QUICK_START.md for next steps
echo 2. Add CSRF tokens to all forms
echo 3. Validate CSRF in controllers
echo 4. Test with browser (localhost/duan1)
echo.

pause
