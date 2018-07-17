# Php Hava Durumu Class
---
- [Kurulum](#kurulum)
- [Bilgilendirme](#bilgilendirme)
- [Ornek](#ornek)
---
### Kurulum
    require_once("class.php");
    require 'class.php';


### Bilgilendirme

Class 5.3, 5.4, 5.5, 5.6 sürümlerinde çalışmaktadır.

### Ornek
```php
// Kurulum
require_once("class.php");
$ep= new epclass();

// İl

$iladi="Ankara";
$il= $ep->il($iladi);

// İl ve ilçe

$iladi="Ankara";
$ilceadi="Keçiören";
$ililce= $ep->ililce($iladi,$ilceadi);

```

Geliştirci: &copy; ErenKrt