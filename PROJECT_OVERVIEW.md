# DemoQC 项目概览文档

> **最后更新**: 2026-07-09  
> **用途**: 让 AI/开发者快速理解整个项目结构，加速后续开发任务

---

## 📋 目录
1. [项目基本信息](#1-项目基本信息)
2. [技术栈](#2-技术栈)
3. [环境配置](#3-环境配置)
4. [目录结构](#4-目录结构)
5. [认证系统 (Guards)](#5-认证系统-guards)
6. [数据库与模型](#6-数据库与模型)
7. [路由结构](#7-路由结构)
8. [控制器](#8-控制器)
9. [视图结构](#9-视图结构)
10. [中间件](#10-中间件)
11. [全局共享数据 (ViewComposer)](#11-全局共享数据-viewcomposer)
12. [支付系统](#12-支付系统)
13. [邮件系统](#13-邮件系统)
14. [功能模块总结](#14-功能模块总结)
15. [常见开发注意事项](#15-常见开发注意事项)

---

## 1. 项目基本信息

| 项目 | 详情 |
|------|------|
| 框架 | Laravel 9.x |
| PHP版本 | ^8.0 |
| 项目名称 | DemoQC (Laravel 电商 + MLM 多层次营销系统) |
| 本地URL | `http://localhost` |
| 资产URL | `https://vs3mg.com/demoqc/public/` |
| 数据库 | `localdemoqc` (MySQL, 127.0.0.1:3306) |
| 本地路径 | `c:\xampp\htdocs\DemoQC\demoqc` |
| XAMPP 环境 | 是 (Windows XAMPP) |

---

## 2. 技术栈

### PHP 依赖 (composer.json)

| 包 | 版本 | 用途 |
|----|------|------|
| `laravel/framework` | ^9.0 | 核心框架 |
| `laravel/socialite` | 5.5.2 | Google 社交登录 |
| `laravel/tinker` | 2.7.2 | Artisan REPL |
| `maatwebsite/excel` | ^3.1 | Excel 导入/导出 |
| `barryvdh/laravel-dompdf` | ^2.0.0 | PDF 生成（发票、报告） |
| `intervention/image` | ^2.7 | 图片处理（上传/裁剪） |
| `simplesoftwareio/simple-qrcode` | ^2.0 | QR Code 生成 |
| `alimranahmed/laraocr` | ^1.2 | OCR 文字识别 |
| `thiagoalessio/tesseract_ocr` | ^2.12 | Tesseract OCR 引擎 |
| `twilio/sdk` | ^6.0 | Twilio SMS 发送 |
| `yoeunes/toastr` | ^1.2 | Toast 通知 |
| `mike42/escpos-php` | ^2.2 | ESC/POS 打印机支持 |

### 前端依赖 (package.json)

| 包 | 用途 |
|----|------|
| `laravel-mix` | Webpack 资产编译 |
| `bootstrap` | ^4.0.0 CSS 框架 |
| `jquery` | ^3.2 |
| `vue` | ^2.5.17 (Vue 2) |
| `axios` | ^0.19 HTTP 客户端 |
| `sass` / `sass-loader` | SCSS 编译 |

---

## 3. 环境配置

### `.env` 关键配置

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
ASSET_URL=https://vs3mg.com/demoqc/public/

# 数据库
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=localdemoqc
DB_USERNAME=root
DB_PASSWORD=

# 邮件 (SMTP)
MAIL_DRIVER=smtp
MAIL_HOST=kimcafe.com.my
MAIL_PORT=587
MAIL_USERNAME=support@kimebiz.com
MAIL_ENCRYPTION=tls

# Twilio SMS
TWILIO_SID=ACdd413f44d1239d171f81d1ae89807919
TWILIO_NUMBER=+13109353475
```

### 常用 Artisan 命令
```bash
# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 数据库
php artisan migrate
php artisan db:seed

# 前端资产编译
npm run dev    # 开发模式
npm run watch  # 监听变化
npm run prod   # 生产模式
```

---

## 4. 目录结构

```
demoqc/
├── app/                        # 应用核心
│   ├── Http/
│   │   ├── Controllers/        # 控制器
│   │   │   ├── HomeController.php        # 前台主控制器 (477KB!)
│   │   │   ├── HomeController-1.php      # 前台备用控制器 (396KB)
│   │   │   ├── GlobalController.php      # 全局工具方法 (235KB)
│   │   │   ├── AjaxController.php        # 前台 AJAX (133KB)
│   │   │   ├── APIController.php         # API 接口 (41KB)
│   │   │   ├── UserShippingAddressController.php
│   │   │   ├── GoogleSocialController.php
│   │   │   └── Backend/                  # 后台控制器 (31个)
│   │   ├── Middleware/                   # 中间件 (8个)
│   │   └── Kernel.php
│   ├── Providers/
│   │   ├── AuthServiceProvider.php       # 全局 ViewComposer 数据
│   │   └── RouteServiceProvider.php
│   ├── Mail/                             # 邮件模板类 (18个)
│   ├── Exports/                          # Excel 导出 (19个)
│   ├── Imports/                          # Excel 导入 (1个)
│   ├── Services/                         # 服务层
│   ├── Console/                          # Console 命令
│   └── [Models].php                      # 所有 Eloquent 模型 (~150个文件)
├── config/                     # 配置文件 (15个)
├── database/
│   ├── migrations/             # 数据库迁移 (21个)
│   ├── seeds/                  # 数据填充 (12个)
│   └── factories/
├── resources/
│   ├── views/                  # Blade 模板
│   │   ├── layouts/            # 布局模板
│   │   │   ├── app.blade.php         # 前台布局 (95KB)
│   │   │   └── admin_app.blade.php   # 后台布局 (171KB)
│   │   ├── frontend/           # 前台页面 (96个文件)
│   │   ├── backend/            # 后台页面 (261个文件)
│   │   ├── emails/             # 邮件模板 (18个)
│   │   ├── auth/               # 认证页面
│   │   ├── errors/             # 错误页面
│   │   └── language/           # 语言文件
│   ├── lang/                   # 语言翻译
│   ├── js/                     # JavaScript 源文件
│   └── sass/                   # SCSS 样式
├── routes/
│   ├── web.php                 # Web 路由 (872行, 66KB!)
│   └── api.php                 # API 路由
├── public/                     # 公开资产 (6966文件)
├── storage/                    # 存储文件
└── vendor/                     # Composer 依赖
```

---

## 5. 认证系统 (Guards)

系统使用**多 Guard 认证**，共有 **6 种用户角色**：

| Guard | 模型 | 表名 | 描述 |
|-------|------|------|------|
| `web` | `App\User` | `users` | 普通会员/客户 |
| `agent` | `App\Agent` | `agents` | 代理商 |
| `merchant` | `App\Merchant` | `merchants` | 商家/经销商 |
| `admin` | `App\Admin` | `admins` | 后台管理员 |
| `staff` | `App\Staff` | `staffs` | 员工 |
| `corporate` | `App\Corporate` | `corporates` | 企业客户 |

### Guard 使用方式
```php
// 检查 Guard
Auth::guard('merchant')->check()
Auth::guard('admin')->user()

// 路由中间件（允许多个角色）
Route::group(['middleware' => 'auth:web,merchant,agent,admin,corporate'], function () {
    // 已登录用户才能访问的路由
});

Route::group(['middleware' => 'auth:admin,merchant,staff'], function () {
    // 后台路由（admin + merchant + staff）
});
```

### 登录页面
- **会员**: `/login` (Auth::routes())
- **商家/代理**: `/merchant_login`
- **管理员**: `/admin_login`

---

## 6. 数据库与模型

### 核心数据表

#### 用户相关
| 模型 | 表 | 主要字段 |
|------|-----|---------|
| `User` | `users` | code(唯一标识), f_name, l_name, email, phone, lvl, status, master_id, dual_master_id |
| `Agent` | `agents` | code, email, lvl, agent_type, status, dual_master_id, permission_lvl |
| `Merchant` | `merchants` | code, email, lvl, status, verify_status |
| `Admin` | `admins` | code, email, lvl |
| `Staff` | `staffs` | email, permission_lvl |
| `Corporate` | `corporates` | code, email |

> **重要**: 用户标识使用 `code` 字段（非 `id`），且在 User/Agent/Merchant 模型的关联中使用 `code` 作为外键

#### 产品相关
| 模型 | 表 | 主要字段 |
|------|-----|---------|
| `Product` | `products` | item_code, product_code, price, agent_price, variation_enable, status, mall |
| `ProductVariation` | `product_variations` | variation_price, variation_special_price, variation_agent_price |
| `ProductSecondVariation` | `product_second_variations` | 二级变体 |
| `ProductImage` | `product_images` | sort_level 控制排序 |
| `Category` | `categories` | status |
| `SubCategory` | `sub_categories` | category_id |
| `Brand` | `brands` | |
| `Stock` | `stocks` | product_id, qty |
| `AgentPrice` | `agent_prices` | product_id, agent_lvl_id, price, special_price |

#### 订单/交易
| 模型 | 表 | 主要字段 |
|------|-----|---------|
| `Transaction` | `transactions` | transaction_no, user_id(=code), grand_total, status, mall |
| `TransactionDetail` | `transaction_details` | transaction_id, product_id, merchant_id |
| `WithdrawalTransaction` | `withdrawal_transactions` | status: 99=待审核, 1=完成 |
| `TopupTransaction` | `topup_transactions` | status: 99=待审核 |

#### 推广/奖金
| 模型 | 表 | 描述 |
|------|-----|------|
| `Affiliate` | `affiliates` | 推荐关系记录 |
| `AffiliateCommission` | `affiliate_commissions` | 佣金记录 |
| `Promotion` | `promotions` | 促销/优惠码 |
| `AppliedPromotion` | `applied_promotions` | status: 99=已保存, 1=已使用 |
| `FlashSale` | `flash_sales` | 限时特卖 |
| `AddOnDeal` | `add_on_deals` | 加购优惠 |
| `AssignedVoucher` | `assigned_vouchers` | 已分配优惠券 |

#### 钱包系统
| 模型 | 表 | 描述 |
|------|-----|------|
| `AdjustCashWallet` | `adjust_cash_wallets` | 现金钱包调整记录 |
| `AdjustPointWallet` | `adjust_point_wallets` | 积分钱包调整记录 |
| `AdjustTopupWallet` | `adjust_topup_wallets` | 充值钱包调整记录 |
| `AdjustVoucher` | `adjust_vouchers` | |

#### 设置相关 (Setting* 模型)
| 模型 | 描述 |
|------|------|
| `WebsiteSetting` | 网站全局设置 (find(1)) |
| `SettingPaymentGateway` | 支付网关配置 (id=1: SenangPay, 2: RevPay, 3: SurePay, 4: GKash) |
| `SettingBanner` | 轮播图 |
| `SettingShippingFee` | 运费设置 |
| `SettingAgentLevel` | 代理等级 |
| `SettingCommission` | 佣金设置 |
| `SettingColour` | 网站颜色主题 |
| `SettingHeader` | 顶部公告 |
| `SettingWebsiteMessage` | 网站消息 |
| `SettingEinvoice` | 电子发票 |

### 状态码规范 (status 字段)
```
0  = 停用/草稿
1  = 启用/正常/完成
98 = 待确认 (如交易银行转账待上传)
99 = 待审核/Pending
```

### 模型关联注意
- 用户外键通常是 `user_id = users.code`（非 `users.id`）
- 产品 `mall=1` 为积分商城产品，默认 NULL 为普通商品
- `dual_master_id` = 用户的上级商家 code

---

## 7. 路由结构

**路由文件**: `routes/web.php` (872行)

### 公开路由（无需登录）
```
GET  /                          首页 (HomeController@index)
GET  /ECommerce                 产品列表
GET  /PointMall                 积分商城
GET  /Details/{id}              产品详情
GET  /Promotion_Listing         促销列表
GET  /Blog                      博客列表
GET  /faqs                      常见问题
GET  /Contact                   联系我们
GET  /admin_login               管理员登录
GET  /merchant_login            商家登录
POST /placeOrder                下单
GET  /Checkout                  结账页面
```

### 已登录用户路由 (middleware: auth:web,merchant,agent,admin,corporate)
```
GET  /Cart                      购物车
GET  /Profile                   个人资料
GET  /MyWallet                  我的钱包
GET  /MyOrder                   我的订单
GET  /MyVoucher                 我的优惠券
GET  /BankAccount               银行账户
GET  /PendingOrder              待付款订单
GET  /CompletedOrder            已完成订单
GET  /MySetting                 我的设置
GET  /MyQRcode                  我的 QR 码
GET  /Material                  营销资料下载
GET  /MyStocks                  我的库存
GET  /MyAffiliate/{code}        我的下线
GET  /MyCustomer/{code}         我的客户
```

### 后台路由 (middleware: auth:admin,merchant,staff)
```
Resource 路由 (CRUD):
  dashboards, admins, agents, merchants, staffs
  products, point_malls, categories, brands, promotions
  transactions, members, corporates, bundles, banks
  flash_sales, cart_links, quizs, blogs, setting_all_faqs

报告:
  GET  sales_report, order_report, commission_report
  GET  agent_sales_report, stock_report, point_report
  GET  cash_wallet_report, topup_wallet_report
  GET  withdrawal_list, topup_list, join_list

设置:
  GET/POST  setting_agent_level, setting_commission
  GET/POST  setting_shipping_fee, setting_banner
  GET/POST  setting_payment_gateway, website_setting
  GET/POST  setting_einvoice, setting_home_page
  ... (共约 40+ 设置路由)

Cashier/POS:
  GET  /cashier_screen           收银台
  POST /cashier_checkout         收银结账
  POST /cashier_pay              收银付款

导出 Excel:
  GET  exportSales, exportOrder, exportTransaction
  GET  exportCommissionReport, exportWithdrawalReport
  GET  exportAgentList, ExportMerchant, ...
```

### API 路由
- 文件: `routes/api.php`
- 控制器: `APIController.php` (41KB)

---

## 8. 控制器

### 前台控制器

| 控制器 | 大小 | 主要功能 |
|--------|------|---------|
| `HomeController.php` | 477KB | **主前台控制器**，包含几乎所有前台页面逻辑 |
| `HomeController-1.php` | 396KB | 备用/旧版控制器（部分功能可能已迁移） |
| `GlobalController.php` | 235KB | **全局工具方法**，被其他控制器频繁调用 |
| `AjaxController.php` | 133KB | 前台 AJAX 请求处理 |
| `APIController.php` | 41KB | 外部 API 接口 |
| `UserShippingAddressController.php` | 21KB | 收货地址 CRUD |
| `GoogleSocialController.php` | 1KB | Google OAuth 登录 |

### 后台控制器 (Backend/)

| 控制器 | 大小 | 功能 |
|--------|------|------|
| `AjaxController.php` | **314KB** | 后台所有 AJAX 操作（最大文件！） |
| `ReportController.php` | 308KB | 各类报告生成 |
| `ProductController.php` | 166KB | 产品 CRUD 及变体管理 |
| `SettingController.php` | 135KB | 所有系统设置 |
| `TransactionController.php` | 97KB | 订单/交易管理 |
| `AgentController.php` | 61KB | 代理管理 |
| `PointMallController.php` | 66KB | 积分商城管理 |
| `MemberController.php` | 42KB | 会员管理 |
| `DashboardController.php` | 40KB | 后台仪表盘 |
| `PromotionController.php` | 51KB | 促销管理 |
| `MerchantController.php` | 20KB | 商家管理 |
| `BrandController.php` | 17KB | 品牌管理 |
| `StaffController.php` | 28KB | 员工管理 |
| `CafeController.php` | 15KB | 咖啡厅/餐厅 POS 功能 |
| `CartLinkController.php` | 14KB | 购物车链接 |
| `CorporateController.php` | 15KB | 企业客户管理 |
| `NewsletterController.php` | 8KB | 电子邮件营销 |

---

## 9. 视图结构

### 布局模板
- **前台**: `resources/views/layouts/app.blade.php` (95KB) - 所有前台页面继承
- **后台**: `resources/views/layouts/admin_app.blade.php` (171KB) - 所有后台页面继承

### 前台视图 (`resources/views/frontend/`)

| 视图 | 描述 |
|------|------|
| `home.blade.php` | 首页 (47KB) |
| `checkout.blade.php` | 结账页 (83KB) |
| `checkout_mall.blade.php` | 积分商城结账 (68KB) |
| `wallet.blade.php` | 钱包页面 (88KB) |
| `details.blade.php` | 产品详情 (44KB) |
| `listing.blade.php` | 产品列表 (32KB) |
| `cart.blade.php` | 购物车 (35KB) |
| `pending_shipping_order.blade.php` | 待发货订单 (28KB) |
| `promotion.blade.php` | 促销页 (26KB) |
| `my_voucher.blade.php` | 我的优惠券 (23KB) |
| `promo_listing.blade.php` | 促销列表 (22KB) |
| `my_stock.blade.php` | 我的库存 (19KB) |
| `profile.blade.php` | 个人资料 (19KB) |
| `pending_order.blade.php` | 待付款 (27KB) |
| `wallet.blade.php` | 钱包 (88KB) |

### 后台视图 (`resources/views/backend/`)
共 29 个子目录，包括：
`products/`, `transactions/`, `reports/`, `settings/`, `agents/`, `members/`, `merchants/`, `promotions/` 等

### 邮件模板 (`resources/views/emails/`)
- 注册通知、密码重置、提款通知、账户升级、会员审批等

### 语言支持
- 支持中英双语 (`global_language` cookie: 1=中文, 0=英文)
- 语言文件位于 `resources/views/language/language.blade.php`
- 后台也有独立语言切换 (`backend_global_language` cookie)

---

## 10. 中间件

| 中间件 | 描述 |
|--------|------|
| `EnsureAccountIsActive` | **关键**: 检查所有 guard 的账户是否 status=1，否则强制登出 |
| `Authenticate` | 标准认证 |
| `RedirectIfAuthenticated` | 已登录时重定向 |
| `VerifyCsrfToken` | CSRF 保护 |
| `CheckForMaintenanceMode` | 维护模式检查 |
| `TrustProxies` | 代理信任 |
| `EncryptCookies` | Cookie 加密 |
| `TrimStrings` | 字符串裁剪 |

### 账户状态检查逻辑
```php
// EnsureAccountIsActive - 检查所有 guard
$guards = ['web', 'admin', 'merchant', 'agent', 'staff', 'corporate'];
if ($user && (string) $user->status !== '1') {
    // 强制登出并重定向到对应登录页
    Auth::guard($guard)->logout();
    // admin -> admin_login
    // merchant -> merchant_login
    // 其他 -> login
}
```

---

## 11. 全局共享数据 (ViewComposer)

**位置**: `app/Providers/AuthServiceProvider.php`

所有视图都通过 `view()->share('data', $data)` 获得以下变量：

```php
// 通过 $data['key'] 或直接 $key 访问
$data = [
    // 购物车
    'totalCart'            => 购物车商品数 (非积分商城),
    'totalCartMall'        => 积分商城购物车数,

    // 用户信息
    'userGuardRole'        => 当前 guard 名 (web/merchant/agent/admin/corporate/""),
    'HeaderBuyerCode'      => 当前用户 code (买家),
    'getUserDetails'       => 完整用户详情对象,
    'new_guest'            => 游客 cookie ID,

    // 网站设置
    'website_logo'         => 网站 Logo,
    'website_name'         => 网站名称,
    'web_setting'          => WebsiteSetting 对象,
    'website_setting'      => WebsiteSetting::find(1),
    'setting_header'       => SettingHeader::find(1),
    'website_messages'     => 滚动消息列表,
    'categories_home'      => 首页分类（带图片和子分类）,

    // 颜色主题
    'button_colour'        => 按钮颜色,
    'text_colour'          => 文字颜色,
    'hover_colour'         => Hover 颜色,
    'header_background_colour' => 导航背景色,
    'footer_background_colour' => 页脚背景色,
    // ... 更多颜色变量

    // 支付网关
    'senangpay_setting'    => SettingPaymentGateway::find(1),
    'revpay_setting'       => SettingPaymentGateway::find(2),
    'surepay_setting'      => SettingPaymentGateway::find(3),
    'gkash_setting'        => SettingPaymentGateway::find(4),

    // 后台通知数量
    'total_pending'        => 待审核代理数,
    'total_member_pending' => 待审核会员数,
    'total_merchant_pending' => 待审核商家数,
    'allPendingTopup'      => 待审核充值数,
    'allPendingTrans'      => 待审核交易数,
    'allPendingWith'       => 待审核提款数,
    'totalPendingTrans'    => 总待处理数,

    // 其他
    'lang'                 => 语言数组 (前台),
    'backendlang'          => 语言数组 (后台),
    'currency_code'        => "RM",
    'banks'                => PaymentBank 可用列表,
    'admin'                => Admin::where('id', '1'),
    'permission'           => 权限矩阵数组 [lvl][page],
    'productionURL'        => 生产环境 URL,
    'bank_required'        => 是否需要填写银行 (1/0),
];
```

> **重要**: 视图中使用 `$data['totalCart']` 或解构后用 `$totalCart`

---

## 12. 支付系统

系统支持 **4 种支付网关** + **银行转账** + **QR Pay**：

| 支付方式 | ID | 处理控制器 |
|---------|----|-----------|
| SenangPay | 1 | `HomeController@SenangPay_PaymentProcess` |
| RevPay | 2 | `HomeController@RevPay_PaymentProcess` |
| SurePay | 3 | `HomeController@SurePay_PaymentProcess` |
| GKash | 4 | `HomeController@GKash_PaymentProcess` |
| 银行转账 | - | 上传 bank slip 后人工审核 |
| QR Pay | - | `HomeController@QrPayment` / `QrPaymentSubmit` |
| 钱包支付 | - | `HomeController@save_wallet` |
| Topup 充值 | - | `HomeController@TopupPaymentProcess` |

### 交易状态码
```
98  = 等待银行 Slip 上传（银行转账待确认）
99  = 等待管理员审核
1   = 已完成/已付款
0   = 取消/失败
```

---

## 13. 邮件系统

**SMTP 配置**: `kimcafe.com.my:587` TLS

| 邮件类 | 触发场景 |
|--------|---------|
| `RegisterNotify` | 新用户注册 |
| `OrderNotification` | 新订单通知（给管理员） |
| `NewOrderNotification` | 新订单通知（给买家） |
| `NotifyWithdrawal` / `EN` | 提款审批通知 (中/英双版本) |
| `NotifyBank` / `EN` | 银行信息验证 |
| `NotifyNewPassword` / `EN` | 新密码通知 |
| `NotifyAccUpgrade` / `EN` | 账户升级通知 |
| `NotifyUpdateBank` / `EN` | 银行更新通知 |
| `NotifyIC` | IC 文件通知 |
| `JoinEnquiry` | 加盟咨询 |
| `WebsiteEnquiry` | 网站联系表单 |
| `SendNewsletter` | 群发电子报 |
| `HDTutoMail` | 教程邮件 |

---

## 14. 功能模块总结

### 电商功能
- ✅ 产品管理（单品/变体/二级变体）
- ✅ 积分商城 (Point Mall)
- ✅ 购物车（支持游客 + 登录用户）
- ✅ 结账（多种支付方式）
- ✅ 促销/优惠码系统
- ✅ 闪购 (Flash Sale)
- ✅ 加购优惠 (Add-On Deal)
- ✅ 购物车链接 (Cart Link 分享)
- ✅ 捆绑产品 (Bundle)
- ✅ 产品评价/评分

### MLM / 多层次营销
- ✅ 代理等级系统 (AgentLevel)
- ✅ 推荐关系树 (Affiliate)
- ✅ 佣金计算 (commission)
- ✅ 钱包系统 (现金/积分/Topup)
- ✅ LOO/LOL 奖金
- ✅ 团队业绩奖 (Team Dividend)
- ✅ 业绩分红 (Performance Dividend)
- ✅ 奖池 (Prize Pool)
- ✅ 代理加盟费
- ✅ 提款系统 (Withdrawal)
- ✅ 代理库存管理 (Stock/WithdrawalStock)
- ✅ 上下线树状图 (tree view)

### 用户功能
- ✅ 多角色认证（会员/代理/商家/管理员/员工/企业）
- ✅ Google 社交登录
- ✅ 会员等级 (CustomerLevel)
- ✅ 企业账户 (Corporate)
- ✅ 地址管理 (UserShippingAddress)
- ✅ 优惠券 (Voucher)

### 内容管理
- ✅ Banner 管理
- ✅ 博客系统 (Blog)
- ✅ FAQ 管理
- ✅ 营销资料下载 (Material)
- ✅ Quiz 系统
- ✅ 证书下载 (Certificate)
- ✅ 排行榜 (Ranking)
- ✅ 见证 (Testimonial)

### 后台功能
- ✅ 仪表盘 (Dashboard)
- ✅ 多种报告（销售/代理/佣金/提款等）
- ✅ Excel 导入导出
- ✅ PDF 发票生成（DomPDF）
- ✅ 员工权限管理 (Permission)
- ✅ 网站颜色主题设置
- ✅ 电子发票 (e-Invoice)
- ✅ POS 收银台（咖啡厅/餐厅功能）
- ✅ OCR 文件识别
- ✅ E-Commerce + Point Mall 双系统管理

### 通知服务
- ✅ 电子邮件 (SMTP)
- ✅ SMS (Twilio)
- ✅ QR Code 生成

---

## 15. 常见开发注意事项

### ⚠️ 重要约定

1. **用户 code 而非 id**
   - 用户标识用 `code` 字段，如 `Cart::where('user_id', $user->code)`
   - 关联关系也是 `hasMany(X::class, 'user_id', 'code')`

2. **多 Guard 检查**
   - 一个功能可能涉及多个 guard
   - 在控制器中常见 `Auth::guard('merchant')->check()` 等多重检查

3. **mall 字段**
   - `mall = 1` = 积分商城产品/订单
   - `mall = NULL` = 普通电商产品/订单

4. **语言变量**
   - 视图中通过 `$data['lang']['key']` 访问语言字符串
   - 支持切换语言（中/英）

5. **`vmerchant` Cookie**
   - 当管理员查看特定商家时设置此 cookie
   - 影响全局视图中的多个数据过滤

6. **GlobalController 工具方法**
   - `GlobalController::getUserDetails($code)` 获取用户详情
   - `GlobalController::check_authorize()` 检查授权
   - `GlobalController::loop_start_dates()` 获取周期日期
   - `GlobalController::loop_monthly()` 获取月度列表

7. **backend/AjaxController 体积巨大**
   - 314KB，所有后台 AJAX 都在这里，查找时用搜索

8. **HomeController 体积巨大**
   - 477KB！添加新前台方法前确认是否在正确文件

9. **状态码统一**
   - `status = 1` = 正常启用
   - `status = 0` = 停用
   - `status = 99` = 待审核
   - `status = 98` = 等待操作

10. **AssetsURL**
    - 本地开发用 `ASSET_URL=https://vs3mg.com/demoqc/public/`，上传图片时注意路径

11. **双语邮件**
    - 很多邮件类都有 `EN` 后缀版本（英文），记得同步修改

### 🔧 快速查找方法

```bash
# 搜索路由名称
grep -r "route_name" routes/

# 搜索控制器方法
grep -r "function methodName" app/Http/Controllers/

# 搜索视图
grep -r "blade_file" resources/views/

# 搜索模型字段
grep -r "field_name" app/

# 搜索 AJAX 端点
grep -r "Route::" routes/web.php | grep "AjaxController"
```

### 📁 新功能开发流程

1. 在 `routes/web.php` 添加路由
2. 在对应 Controller 添加方法（前台→HomeController，后台→Backend/相关Controller）
3. 创建 `resources/views/frontend/` 或 `resources/views/backend/` 视图
4. 若需要 AJAX，在 `AjaxController.php` 添加方法
5. 若需要 Excel 导出，在 `app/Exports/` 创建 Export 类
6. 若需要邮件通知，在 `app/Mail/` 创建 Mail 类

---

## 附录：后台 URL 前缀

所有后台路由均通过 `auth:admin,merchant,staff` 中间件保护，无统一 URL 前缀（直接在根路径下）。

例如：
- 产品列表: `/products`
- 代理列表: `/agents`
- 报告: `/sales_report`
- 设置: `/setting_banner`

---

*文档由 AI 自动生成，基于 2026-07-09 项目代码状态*
