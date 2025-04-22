### 整体功能概述
此插件为 WordPress 网站增添了自定义短代码功能，借助这些短代码，用户能够在页面或文章里轻松展示分类列表、日期归档列表以及标签列表，并且可以对列表样式进行统一设置。

### 具体功能介绍

#### 1. 自定义分类列表展示
- **短代码**：`[custom_categories]`
- **功能说明**：
    - **参数支持**：支持 `orderby`（排序依据，默认按名称排序）、`order`（排序顺序，默认升序）和 `hide_empty`（是否隐藏无文章的分类，默认显示）等参数。比如 `[custom_categories orderby="count" order="DESC"]` 可以按文章数量降序排列分类。
    - **样式统一**：生成的分类链接会被包裹在带有 `custom-category-links` 类的 `<span>` 标签内，每个链接都带有 `category-link` 类，便于统一设置样式。
    - **链接生成**：为每个分类生成对应的链接，点击链接可跳转到该分类的存档页面。

#### 2. 自定义日期归档列表展示
- **短代码**：`[custom_date_archive]`
- **功能说明**：
    - **参数支持**：支持 `type`（归档类型，默认按月归档）、`format`（日期格式，默认显示月份和年份）和 `show_post_count`（是否显示文章数量，默认显示）等参数。例如 `[custom_date_archive type="yearly" format="Y"]` 可按年归档并只显示年份。
    - **样式统一**：同分类列表一样，日期归档链接也采用 `custom-category-links` 和 `category-link` 类，保持样式一致。
    - **链接生成**：为每个日期归档生成对应的链接，点击可跳转到该日期范围内的文章存档页面。

#### 3. 自定义标签列表展示
- **短代码**：`[custom_tags]`
- **功能说明**：
    - **参数支持**：支持 `orderby`（排序依据，默认按名称排序）、`order`（排序顺序，默认升序）和 `hide_empty`（是否隐藏无文章的标签，默认显示）等参数。例如 `[custom_tags orderby="count" order="DESC"]` 可按文章数量降序排列标签。
    - **样式统一**：标签链接同样使用 `custom-category-links` 和 `category-link` 类进行样式控制。
    - **链接生成**：为每个标签生成对应的链接，点击链接可跳转到该标签的存档页面。

#### 4. 统一样式设置
- **样式特点**：
    - **去除下划线**：通过在页面头部添加 CSS 样式，取消了所有带有 `category-link` 类的 `<a>` 标签的下划线，使页面更加美观。
    - **间距设置**：设置了链接之间的右边距为 12 像素，让列表显示更加清晰。
    - **悬停效果**：添加了鼠标悬停效果，当鼠标悬停在链接上时，链接会显示下划线，提供交互反馈，增强用户体验。

#### 5. 创建“说说”文章
- **功能介绍**：在 WordPress 后台左侧菜单，点击“说说” - “新增说说”，填写标题、内容等信息后发布。
- **创建说说页面**：新建页面，在页面编辑界面的“页面属性” - “模板”处选择“说说/微语”模板，保存页面后访问该页面，可看到按特定样式展示的说说列表，包含作者头像、内容、发布时间和评论数量。 

#### 6. 使用火山引擎图片服务（ImageX）作为附件存储空间。
- 可配置是否上传缩略图和是否保留本地备份
- 本地删除可同步删除火山引擎图片服务 ImageX 中的文件
- 支持替换数据库中旧的资源链接地址
- 支持完整地域使用
- 支持同步历史附件到火山引擎图片服务 ImageX
- 支持火山引擎图片服务 ImageX 图片处理
- 支持自动重命名文件

#### 7. 评论扩展
- 评论支持emoji表情
- 评论支持markdown语法

#### 8. 股票查询
- 可以在文章或页面中使用 [stock_monitor] 短码来显示后台添加的已监控股票列表。
- 如果你需要自定义标题，可以使用 [stock_monitor title="自定义标题"] 短码，将 “自定义标题” 替换为你想要显示的标题。
- 如果你想指定显示的股票代码，可以使用 [stock_monitor codes="600001,000001"] 短码，将 “600001,000001” 替换为你要显示的股票代码，多个代码用逗号分隔。
- 你也可以同时使用标题和指定股票代码，例如 [stock_monitor title="我的股票列表" codes="600001,000001"]。

## 插件地址
插件下载：
[https://github.com/lifengdi/wp-dylan-custom-plugin/releases](https://github.com/lifengdi/wp-dylan-custom-plugin/releases "https://github.com/lifengdi/wp-dylan-custom-plugin/releases")

问题反馈：
[https://github.com/lifengdi/wp-dylan-custom-plugin/issues](https://github.com/lifengdi/wp-dylan-custom-plugin/issues "https://github.com/lifengdi/wp-dylan-custom-plugin/issues")


## 插件赞助
[https://www.lifengdi.com/support](https://www.lifengdi.com/support "https://www.lifengdi.com/support")

## 插件说明
[https://www.lifengdi.com/archives/article/4314](https://www.lifengdi.com/archives/article/4314 "https://www.lifengdi.com/archives/article/4314")

## 更新日志
[Dylan Custom Plugin 更新日志](https://www.lifengdi.com/archives/category/article/cha-jian "https://www.lifengdi.com/archives/category/article/cha-jian")

欢迎大家star。