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

### In `table()`:

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

* `getLabel()`, `getPluralLabel()`, and `getNavigationLabel()` are automatically generated.
* The trait converts model names into kebab-case and pluralizes them.
* Warnings will be logged if a relationship is passed without specifying a subfield (e.g., `owner` instead of `owner.name`).

---

🎯 This trait helps you keep your code clean, maintainable, and DRY.
