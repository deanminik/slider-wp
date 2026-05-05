# CCC Home Slider Plugin

Este es un plugin ligero de slider para WordPress basado en Splide.js, diseñado para ser rápido, accesible y altamente personalizable.

## Cómo usar

El slider se puede mostrar en cualquier lugar del sitio utilizando el siguiente shortcode:

`[ccc_slider]`

## Cómo agregar o modificar elementos (Guía para Desarrolladores)

El slider ha sido diseñado de forma modular, permitiéndote agregar nuevos campos como iconos, subtítulos, imágenes adicionales, etc.

A continuación, se explica el flujo completo para agregar un nuevo elemento, utilizando el ejemplo de un **"Icono de Slide"**:

### Paso 1: Agregar el campo en el panel de administración
Abre el archivo `ccc-home-slider.php`. Busca la función `ccc_slider_render_meta_box($post)`. Aquí es donde se define el HTML para los campos que ves al editar un slide.

```php
// 1. Obtenemos el valor guardado de la base de datos (si existe)
$slide_icon = get_post_meta($post->ID, '_ccc_slide_icon', true);
```

Luego, dentro de la tabla HTML (`<table class="form-table">`), agrega el nuevo campo de entrada:

```html
<tr>
    <th><label for="ccc_slide_icon"><?php esc_html_e('Icon Class (e.g. fa-solid fa-star)', 'ccc-home-slider'); ?></label></th>
    <td>
        <input type="text" id="ccc_slide_icon" name="ccc_slide_icon" value="<?php echo esc_attr($slide_icon); ?>" class="regular-text" />
        <p class="description">Agrega la clase del icono de FontAwesome.</p>
    </td>
</tr>
```

### Paso 2: Guardar la información en la base de datos
En el mismo archivo `ccc-home-slider.php`, busca la función `ccc_slider_save_meta_box_data($post_id)`. Aquí es donde nos aseguramos de que los datos ingresados por el usuario se guarden de manera segura.

Agrega la lógica para guardar tu nuevo campo (utilizando funciones de sanitización por seguridad):

```php
if (isset($_POST['ccc_slide_icon'])) {
    update_post_meta($post_id, '_ccc_slide_icon', sanitize_text_field($_POST['ccc_slide_icon']));
}
```

### Paso 3: Mostrar el elemento en el diseño del Slide (Frontend)
Busca la función `ccc_slider_shortcode($atts)` en el archivo `ccc-home-slider.php`. Esta función se encarga de imprimir el HTML del slider en la página web.

Dentro del loop del slider (`while ($query->have_posts()):`), obtén el valor guardado:

```php
$slide_icon = get_post_meta($post_id, '_ccc_slide_icon', true);
```

Luego, imprime este valor donde desees que aparezca dentro de la estructura HTML del slide (por ejemplo, antes del título):

```html
<?php if ($slide_icon): ?>
    <div class="ccc-slide-icon">
        <i class="<?php echo esc_attr($slide_icon); ?>"></i>
    </div>
<?php endif; ?>
```

### Paso 4: Dar estilo al nuevo elemento (CSS)
Abre el archivo `assets/css/style.css`. Al final del archivo o en la sección correspondiente, agrega las reglas de estilo para tu nuevo elemento usando la clase que definiste en el paso anterior (`.ccc-slide-icon`):

```css
.ccc-slide-icon {
    font-size: 40px;
    color: #82C7B7;
    margin-bottom: 15px;
}
```

Con estos 4 sencillos pasos, podrás agregar cualquier tipo de contenido (párrafos, títulos secundarios, opciones de color personalizadas, etc.) de manera segura y eficiente.
