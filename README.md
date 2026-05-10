# Hostinger WordPress WooCommerce Starter

这个仓库提供一套可以导入 WordPress、兼容 WooCommerce、并适合部署到 Hostinger 的建站起点：

- `wp-content/themes/hostinger-woo-starter`：轻量 WordPress 主题，包含 WooCommerce theme support、购物车入口、响应式页面结构。
- `wp-content/plugins/visual-feedback-overlay`：管理员前台视觉标注插件，可在预览页面上点击位置并留下修改意见。
- `.wp-env.json`：本地实时预览环境配置，会自动加载 WordPress、WooCommerce、主题与标注插件。
- `scripts/build-zips.sh`：生成可上传到 WordPress 后台或 Hostinger 文件管理器的主题/插件 ZIP。

## 推荐环境

为了减少 Hostinger 与 WooCommerce 插件兼容问题，建议：

- Hostinger hPanel 中将 PHP 版本设置为 `8.1` 或更高。
- WordPress 开启 HTTPS，保持核心、主题和插件更新。
- WooCommerce 通过 WordPress 后台插件市场安装或由 `.wp-env.json` 本地环境自动拉取。
- 产品图片尽量使用 WebP/JPEG 并压缩，避免共享主机资源被大图拖慢。

## 本地实时预览

> 需要本机安装 Docker、Node.js 和 npm。

```bash
npm install
npm run wp:start
```

启动后访问：

- 前台预览：<http://localhost:8888>
- WordPress 后台：<http://localhost:8888/wp-admin>
- 默认账号：`admin`
- 默认密码：`password`

首次启动后建议执行：

```bash
npm run wp:cli -- theme activate hostinger-woo-starter
npm run wp:cli -- plugin activate woocommerce visual-feedback-overlay
```

如果你修改主题 PHP、CSS 或 JS，刷新浏览器即可看到变化；`.wp-env.json` 会把本仓库主题和插件映射到 WordPress 容器中。

## 画面标注工作流

1. 登录 WordPress 后台，并使用管理员账号访问前台页面。
2. 右下角会出现 `标注模式 / 导出 / 清空本页` 工具条。
3. 点击 `标注模式` 后，在页面任意位置点击并输入修改意见。
4. 鼠标悬停红色编号标记可查看意见。
5. 点击 `导出` 可下载当前页面的 JSON 标注清单，方便发给设计或开发人员。
6. 点击 `清空本页` 只会清除当前 URL 路径下的标注。

> 安全说明：标注层只会对具备 `edit_theme_options` 权限的登录用户加载，普通访客不会看到。

## 生成 WordPress 可导入 ZIP

```bash
npm run build:zip
```

生成文件：

- `build/hostinger-woo-starter.zip`
- `build/visual-feedback-overlay.zip`

## 导入 WordPress / Hostinger

### 方式 A：WordPress 后台上传

1. 进入 `外观 → 主题 → 添加新主题 → 上传主题`。
2. 上传 `build/hostinger-woo-starter.zip` 并启用。
3. 进入 `插件 → 添加新插件 → 上传插件`。
4. 上传 `build/visual-feedback-overlay.zip` 并启用。
5. 安装并启用 WooCommerce，然后完成商店设置向导。

### 方式 B：Hostinger 文件管理器上传

1. 在 Hostinger hPanel 打开网站的文件管理器。
2. 将主题 ZIP 解压到 `public_html/wp-content/themes/hostinger-woo-starter`。
3. 将插件 ZIP 解压到 `public_html/wp-content/plugins/visual-feedback-overlay`。
4. 回到 WordPress 后台启用主题和插件。

## WooCommerce 适配点

主题已包含：

- `add_theme_support( 'woocommerce' )` 与商品网格默认配置。
- 商品图缩放、灯箱、轮播支持。
- WooCommerce 独立模板入口 `woocommerce.php`。
- 主菜单自动追加购物车链接和商品数量。
- 商品列表与单品页基础响应式样式。

## 后续可扩展事项

- 增加品牌配色、首页区块与产品分类区块。
- 增加 WooCommerce 结账页、账户页的更细样式。
- 增加 Hostinger 上线清单，例如缓存、CDN、备份、邮件 SMTP、支付网关测试。
- 若需要更强的多人协作标注，可以把当前插件替换为 Marker.io、BugHerd 或 Atarim 等商业工具。
