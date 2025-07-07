# Language
The Language class provides a comprehensive representation of a language using its ISO 639-1 two-letter code. It encapsulates language metadata such as the language name, flag emoji, and text direction (left-to-right or right-to-left). This class is designed for use in multimedia and streaming applications, especially those working with M3U8 playlists, where language codes and their properties are essential for track selection, display, and localization. The class also supports serialization, comparison, and conversion to various formats.

---

## Properties
### `public string $shortCode`
Stores the two-letter ISO 639-1 language code (e.g., `'en'` for English, `'fr'` for French).

**Example:**
```php
echo ( new Language( 'EN' ))->shortCode;
// en
```
**Best Practice:**  
- Always use valid ISO 639-1 codes. The constructor normalizes the code to lowercase.

---

### `protected static array $rtlLanguages`
A static array of language codes that are written right-to-left (RTL), such as Arabic (`'ar'`), Hebrew (`'he'`), Persian (`'fa'`), and Urdu (`'ur'`).

**Example:**  
_Internal use only; not accessed directly._

**Edge Case:**  
If a right-to-left language is missing from this list, `getDirection()` may return `'ltr'` incorrectly.

---

### `protected static array $languageMap`
A static associative array mapping language codes to an array containing the full language name and a flag emoji.

**Example:**  
_Internal use only; not accessed directly._

**Edge Case:**  
If a code is not present in this map, methods like `getName()` and `getFlagEmoji()` will return `null`.

---

## Methods
### `__construct(string $shortCode)`
Creates a new Language object with the given language code. The code is normalized to lowercase.

**Example:**
```php
// Spanish
echo ( new Language( 'ES' ))->shortCode;
// es
```

**Best Practice:**  
Pass only valid ISO 639-1 codes.

---

### `getShortCode(): string`
Returns the normalized two-letter language code.

**Example:**
```php
echo ( new Language( 'DE' ))->getShortCode();
// de
```

---

### `getName(): ?string`
Returns the full language name if the code is valid, or `null` otherwise.

**Example:**
```php
echo ( new Language( 'fr' ))->getName();
// French
```
**Edge Case:**  
Returns `null` for unknown codes.

---

### `getDirection(): string`
Returns `'rtl'` if the language is right-to-left, `'ltr'` otherwise.

**Example:**
```php
echo ( new Language( 'ar' ))->getDirection();
// rtl
```
**Edge Case:**  
If a language is missing from `$rtlLanguages`, it will default to `'ltr'`.

---

### `isRightToLeft(): bool`
Checks if the language is right-to-left.

**Example:**
```php
if(( new Language('he'))->isRightToLeft())
{
    // Handle RTL layout
}
```

---

### `getFlagEmoji(): ?string`
Returns the flag emoji for the language code, or `null` if not found.

**Example:**
```php
echo ( new Language( 'it' ))->getFlagEmoji();
// 🇮🇹
```
**Edge Case:**  
Returns `null` for unknown codes.

---

### `isValid(): bool`
Checks if the language code exists in the language map.

**Example:**
```php
if( ! ( new Language( 'xx' ))->isValid())
{
    echo "Unknown language code";
}
```

---

### `equals(Language $other): bool`
Compares two Language objects for equality based on their codes.

**Example:**
```php
$lang1 = new Language( 'en' );
$lang2 = new Language( 'EN' );

if( $lang1->equals( $lang2 ))
{
    echo "Same language";
}
```

---

### `toArray(): array`
Converts the language object to an associative array with keys: `name`, `shortCode`, `direction`, and `emoji`.

**Example:**
```php
print_r(( new Language( 'ja' ))->toArray());
```

**Output:**
```php
Array
(
    [name] => Japanese
    [shortCode] => ja
    [direction] => ltr
    [emoji] => 🇯🇵
)
```

---

## Serialization
### M3U8 Serialization
Returns the language code formatted for M3U8 playlists.

**Example:**
```php
echo ( new Language( 'de' ))->toM3U8();
// LANGUAGE="de"
```

---

### JSON Serialization
Serializes the language code for use with `json_encode()`.

**Example:**
```php
echo json_encode(
[
	'lang' => new Language( 'ua' )
]);
```

**Output:**
```json
{
	"lang": "ua"
}
```

---

### String Conversion
Returns the language code as a string.

**Example:**
```php
echo new Language( 'ko' );
// ko
```

---

## Best Practices & Edge Cases

- Always check `isValid()` before using `getName()` or `getFlagEmoji()` to avoid `null` values.
- Use lowercase codes for consistency; the constructor handles this automatically.
- When supporting new languages, update both `$languageMap` and `$rtlLanguages` as needed.
- For unknown or custom codes, be prepared for `null` returns from name and emoji methods.

---

This class is ideal for any application needing robust, extensible language metadata handling, especially in the context of playlist or media track management.
