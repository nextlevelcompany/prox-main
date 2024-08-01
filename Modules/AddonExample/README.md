## Guía

* comando para crear un nuevo módulo
```
php artisan module:make AddonExample
```

* se creará el una nueva carpeta dentro de Modules
```
Modules\AddonExample
```

* para agregar un nuevo menu debe crear un archivo dentro de resources\views\tenant\layouts\partials\addons_menu, puede usar de ejemplo el archivo example.blade.php, debe tener el nombre del módulo en minusculas
```
addonexample.blade.php
```
> no eliminar el archivo example.blade.php

* se cuenta con una ruta inicial dentro del módulo en el archivo Routes/tenant.php, de esta forma estará disponible una vista básica en blanco y una ruta mediante el nombre del módulo /addonexample

* para personalizar el módulo debe conocer la estructura de trabajo de laravel, visite https://laravel.com/docs/9.x/readme para más información

* desde el módulo puede hacer uso de cualquier modelo del facturador, el archivo Modules\AddonExample\Http\Controllers\AddonExampleController.php muestra un ejemplo de ello

* en la vista puede trabajar con bootstrap y blade, Modules\AddonExample\Resources\views\index.blade.php le servirá de ejemplo

* para mayor información sobre el paquete de módulos visite https://nwidart.com/laravel-modules

* desarrollos con vue serán publicados próximamente