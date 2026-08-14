## Category 包（wsmallnews/category）

`wsmallnews/category` 是基于 `wsmallnews/filament-nestedset` 的分类管理插件，支持多层级分类、多租户和分类类型管理。命名空间根为 `Wsmallnews\Category`，Blade 视图前缀为 `sn-category`，配置文件为 `config/sn-category.php`。

### 核心架构

- 依赖 `wsmallnews/filament-nestedset`（`NestedsetPage` 基类）
- **Base**（`Wsmallnews\Category\Filament\Pages\Category\Base`）：继承 `Wsmallnews\FilamentNestedset\Filament\Pages\NestedsetPage` 的抽象页面类，负责配置、schema 定义、分类类型管理
- **CategoryPage**（`Wsmallnews\Category\Filament\Pages\Category\CategoryPage`）：继承 Base 的具体页面类，注册到 Filament 面板
- **Category Widget**（`Wsmallnews\Category\Filament\Pages\Category\Widgets\Category`）：Filament Widget 变体

### 分类类型（CategoryType）

每个分类页面绑定一个 `CategoryType`，定义分类的层级限制和作用域：

```php
// 自动创建分类类型（当 canManage = false 时）
$categoryType = CategoryType::create([
    'name' => Str::title($scopeType),
    'level' => $level,
    'status' => CategoryTypeStatus::Normal,
    'scope_type' => $scopeType,
    'scope_id' => $scopeId,
    'team_id' => $tenantId,
]);
```

### 创建分类页面

```bash
php artisan make:filament-nestedset-page
```

生成的页面类继承 `Base`，需设置 `$model` 和 `$scopeType`。

#### 静态属性

| 属性 | 类型 | 默认值 | 说明 |
|---|---|---|---|
| `$model` | `?string` | `null` | 分类模型类名，**必须设置** |
| `$scopeType` | `?string` | `null` | 作用域类型，**必须设置** |
| `$scopeId` | `int` | `0` | 作用域 ID（0 = 全局） |
| `$level` | `?int` | `null` | 嵌套层级限制 |
| `$canManage` | `bool` | `false` | 是否显示分类类型管理表单 |
| `$navigationIcon` | `string\|BackedEnum\|null` | `Heroicon::OutlinedBars3BottomLeft` | 导航图标 |
| `$navigationSort` | `?int` | `1` | 导航排序 |

#### 可覆盖方法

```php
// 自定义 schema（create 和 edit 共用）
public function schema(array $arguments): array { return []; }

// create 和 edit 分别定义
public function createSchema(array $arguments): array { return []; }
public function editSchema(array $arguments): array { return []; }

// Infolist 附加属性展示
public function infolistSchema(): array { return []; }

// 自定义节点标签
public function getRecordLabel(Model $record): HtmlString|string { ... }

// 自定义嵌套集查询条件
public function getEloquentQuery($query) { return $query; }

// 额外的 scope 参数
public function nestedScoped(): array { return []; }
```

### 关键可覆盖方法

Base 页面自动通过 `nestedScoped()` 将 `scope_type`、`scope_id`、`type_id` 注入 nestedset 查询，不要手动重复添加这些 scope。`$categoryType` 会自动从配置的 `scopeType` / `scopeId` 解析或创建。

Base 页面覆盖了 `getRecordLabel()`（返回 `$record->name_label`）和 `getHeaderActions()` / `getNestedsetActions()`（仅返回 createAction 和 fixNestedsetAction）。

### 模型 scope 要求

`Category` 模型的 `getScopeAttributes()` 返回 `['scope_type', 'scope_id', 'type_id']`，多租户时追加 `'team_id'`。不要将 `type_id` 忽略，否则 scoped 查询会遗漏分类类型过滤。

### 模型要求

模型必须 use `Kalnoy\Nestedset\NodeTrait`，并且实现 `getScopeAttributes()`：

```php
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;

    public function getScopeAttributes(): array
    {
        return ['team_id', 'scope_type', 'scope_id', 'type_id'];
    }
}
```

### CategoryType 资源

`CategoryTypeResource` 提供分类类型的 CRUD 管理，继承自 support 包的 Scopeable 体系：

```php
use Wsmallnews\Category\Filament\Resources\CategoryTypes\BaseResource;

// BaseResource 已提供：
// - use Scopeable（applyScopeableToQuery 自动过滤）
// - form() → CategoryTypeForm
// - table() → CategoryTypesTable
// - getWidgets() → CategoryWidget
// - getEloquentQuery() → 带 scope + 软删除
```

可配置的具体实现：

```php
use Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource;

// 在 PanelProvider 中注册
$panel->resources([CategoryTypeResource::class]);
```

### 分类模型

`Category` 继承 `SupportModel`，实现 `HasSnSubject` 接口和 `NodeTrait`：

```php
use Wsmallnews\Category\Models\Category;

// 核心特性：
// - use NodeTrait（嵌套集）
// - use HasActivityLog（活动日志）
// - use InteractsWithMedia（Spatie 媒体库）
// - implements HasSnSubject（preference 包集成）
// - getScopeAttributes() 返回 ['scope_type', 'scope_id', 'type_id', 'team_id']
```

### 辅助函数

| 函数 | 说明 |
|---|---|
| `has_category()` | 前端是否有当前分类（从 request attributes 读取） |
| `current_category()` | 前端当前分类 Model |

### 正确命名空间速查

| 类别 | 命名空间 |
|---|---|
| Page 基类 | `Wsmallnews\Category\Filament\Pages\Category\Base` |
| Page 实现 | `Wsmallnews\Category\Filament\Pages\Category\CategoryPage` |
| Widget | `Wsmallnews\Category\Filament\Pages\Category\Widgets\Category` |
| Schema Form | `Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryForm` |
| Schema Infolist | `Wsmallnews\Category\Filament\Pages\Category\Schemas\CategoryInfolist` |
| CategoryType Resource | `Wsmallnews\Category\Filament\Resources\CategoryTypes\CategoryTypeResource` |
| CategoryType BaseResource | `Wsmallnews\Category\Filament\Resources\CategoryTypes\BaseResource` |
| 模型 | `Wsmallnews\Category\Models\Category` |
| 分类类型模型 | `Wsmallnews\Category\Models\CategoryType` |
| CategoryPlugin | `Wsmallnews\Category\CategoryPlugin` |
| Utils | `Wsmallnews\Category\Support\Utils` |
| ServiceProvider | `Wsmallnews\Category\CategoryServiceProvider` |

### 常见错误

- **模型必须 use `NodeTrait`**，否则 `mount()` 抛出 `NestedsetException`。
- **`$level` 设置为 `1` 时只能有根节点**，至少 `2` 才能选择父级。
- **`$scopeType` 必须设置**，否则无法正确过滤分类数据。
- **多租户 scope 需要模型定义 `getScopeAttributes()`**，返回的字段必须包含 `team_id`。
