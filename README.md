# 🧩 HasTranslatedResourceLabels Trait

A smart and flexible trait for auto-labeling fields and columns in Filament Resource forms and tables, using translation files — eliminating repetitive `->label(__('...'))` calls.

---

## ✅ Step 1: Create the Trait

Place the trait in:

```
app/Traits/HasTranslatedResourceLabels.php
```

Or generate it manually:

```bash
mkdir -p app/Traits
```

---

## ✅ Step 2: Use the Trait in a Resource

```php
use App\Traits\HasTranslatedResourceLabels;

class ProductResource extends Resource
{
    use HasTranslatedResourceLabels;

    // ...
}
```

---

## ✅ Old vs New Style

### ⛔️ Traditional Way:

```php
TextInput::make('name')->required()->label(__('resources.products.fields.name'))
```

### ✅ With the Trait:

```php
static::__TextInput('name')->required()
```

---

## ✅ Full Example

### ⛔️ Before (without the trait):

```php
return $form
    ->schema([
        TextInput::make('name')->required()->label(__('resources.products.fields.name')),
        Textarea::make('description')->label(__('resources.products.fields.description')),
        TextInput::make('price')->numeric()->required()->label(__('resources.products.fields.price')),
        TextInput::make('stock')->numeric()->required()->label(__('resources.products.fields.stock')),
        FileUpload::make('image')->directory('resources.products-images')->label(__('resources.products.fields.image')),
        Select::make('owner_id')
            ->relationship('owner', 'name')
            ->searchable()
            ->required()
            ->label(__('resources.products.fields.owner_id')),
    ]);
```

### ✅ After (with the trait):

### In `form()`:

```php
return $form
    ->schema([
        static::__TextInput('name')->required(),
        static::__Textarea('description'),
        static::__TextInput('price')->numeric()->required(),
        static::__TextInput('stock')->numeric()->required(),
        static::__FileUpload('image')->directory('resources.products-images'),
        static::__Select('owner_id')
            ->relationship('owner', 'name')
            ->searchable()
            ->required(),
    ]);
```

### ⛔️ Before (without the trait):

```php
->columns([
    TextColumn::make('name')->searchable()->sortable()->label(__('resources.products.fields.name')),
    TextColumn::make('price')->money('OMR')->label(__('resources.products.fields.price')),
    TextColumn::make('stock')->label(__('resources.products.fields.stock')),
    TextColumn::make('owner.name')->searchable()->label(__('resources.products.fields.owner_id')),
    ImageColumn::make('image')->disk('public')->label(__('resources.products.fields.image')),
])
```

### ✅ After (with the trait):

```php
->columns([
    static::__TextColumn('name')->searchable()->sortable(),
    static::__TextColumn('price')->money('OMR'),
    static::__TextColumn('stock'),
    static::__TextColumn('owner.name')->searchable(),
    static::__ImageColumn('image')->disk('public'),
])
```

---

## ✅ Comparison Table

| Feature                            | Before                                               | After with Trait                                |
| ---------------------------------- | ---------------------------------------------------- | ----------------------------------------------- |
| Labeling Form Fields               | `->label(__('resources.model.fields.field'))`        | Automatically injected via `__TextInput(...)`   |
| Labeling Table Columns             | `->label(__('resources.model.fields.field'))`        | Automatically injected via `__TextColumn(...)`  |
| Resource Label                     | `getLabel()`                                         | Automatically handled                           |
| Plural Resource Label              | `getPluralLabel()`                                   | Automatically handled                           |
| Navigation Label                   | `getNavigationLabel()`                               | Automatically handled                           |
| Breadcrumb / Page Titles           | Manual per page                                      | Automatically resolved via translation keys     |
| Relationship Field Label           | `->label(__('resources.model.fields.owner_id'))`     | Automatically mapped via `__Select(...)`        |
| Works with Translatable Components | `TextInput`, `Textarea`, `Select`, `FileUpload`, etc | Same, but auto-labeled                          |
| Developer Effort                   | High (manual per field/column/page)                  | Very Low (just call `__ComponentName('field')`) |

---

## ✅ Supported Components

| Type         | Usage                     |
| ------------ | ------------------------- |
| TextInput    | `__TextInput('field')`    |
| Textarea     | `__Textarea('field')`     |
| Select       | `__Select('field')`       |
| FileUpload   | `__FileUpload('field')`   |
| DatePicker   | `__DatePicker('field')`   |
| Toggle       | `__Toggle('field')`       |
| TextColumn   | `__TextColumn('field')`   |
| SelectColumn | `__SelectColumn('field')` |
| BadgeColumn  | `__BadgeColumn('field')`  |
| ToggleColumn | `__ToggleColumn('field')` |
| ImageColumn  | `__ImageColumn('field')`  |

---

## ✅ Automatically Handles Resource Labels

Once the trait is used, you **no longer need to define** the following methods in your Resource:

```php
public static function getLabel(): string
{
    return __('resources.products.label');
}

public static function getPluralLabel(): string
{
    return __('resources.products.plural');
}

public static function getNavigationLabel(): string
{
    return __('resources.products.navigation.label');
}
```

These are automatically resolved by the trait based on the model name.

---

## ✅ Required Translation File

### Path:

```
resources/lang/ar/resources.php
```

### Example Structure:

```php
return [
    'products' => [
        'label' => 'Product',
        'plural' => 'Products',

        'navigation' => [
            'label' => 'Products',
            'group' => 'Management',
        ],

        'pages' => [
            'index' => ['title' => 'All Products'],
            'create' => ['title' => 'Create Product'],
            'edit' => ['title' => 'Edit Product'],
        ],

        'fields' => [
            'name' => 'Product Name',
            'description' => 'Description',
            'price' => 'Price',
            'stock' => 'Stock',
            'image' => 'Product Image',
            'owner_id' => 'Owner',
        ],
    ],
];
```

> For relationships, use the key name like `owner_id` — not `owner.name`

---

## ✅ Extra Features

* Automatically applies translations to fields, navigation menus, page titles, and breadcrumbs.
* Converts model class name to kebab-case + plural to resolve translation keys.
* Logs a warning if a relationship name is passed without specifying a subfield (e.g., `owner` instead of `owner.name`).

---

🎯 This trait helps you keep your code clean, maintainable, and DRY.
