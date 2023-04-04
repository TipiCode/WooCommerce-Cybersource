![Main Banner](https://tipi-pod.sfo3.cdn.digitaloceanspaces.com/github%2Fcybersource-banner.jpg)

![Contributors](https://img.shields.io/github/contributors/TipiCode/WooCommerce-Cybersource?color=%2349C8F1&label=Contribuidores&style=for-the-badge)
![Errores](https://img.shields.io/github/issues/TipiCode/WooCommerce-Cybersource?color=%23F99D25&style=for-the-badge)
![Licencia](https://img.shields.io/github/license/TipiCode/WooCommerce-Cybersource?color=%23A4CD39&label=Licencia&style=for-the-badge)
![Froks](https://img.shields.io/github/forks/TipiCode/WooCommerce-Cybersource?color=%2349C8F1&style=for-the-badge)
![Version](https://img.shields.io/github/v/release/TipiCode/WooCommerce-Cybersource?color=%23F99D25&label=Ultima%20versi%C3%B3n&style=for-the-badge)

Plugin para [Woocommerce](https://woocommerce.com/) que habilita la pasarela de pago de [Cybersource](https://www.cybersource.com/es-mx.html) como método de pago en el checkout de tú sitio web, implementar una pasarela de pago para realizar cobros en linea no tiene porque ser ciencia espacial.

![Maintnence](https://tipi-pod.sfo3.cdn.digitaloceanspaces.com/github%2Fplugin-maintnence.jpg)

¡Hola! Gracias por estar pendiente, tenemos nuevas mejoras para este plugin en nuestro pipeline, mantente al tanto de las actualizaciones futuras de este plugin. Estamos trabajando en solucionar todos los Issues abiertos y recomendaciones que recibimos.

# Primeros pasos
Te compartimos cuáles son los primeros pasos para poder adquirir el servicio por parte del [Cybersource](https://www.cybersource.com/es-mx.html)

### 📌 ¿Donde adquiero el servicio de la pasarela de pago de Cybersource?
El servicio de Cybersource requiere que tu comercio esté afiliado a [Visanet Guatemala](https://www.visanet.com.gt/), aqui podras encontrar [mas información acerca de la afiliación](https://www.visanet.com.gt/Comercios/RequisitosAfiliacion).

### 📃 ¿Cuales son los requisitos?
Para poder adquirir el servicio de la pasarela de pago necesitas lo siguiente: 
- Patente de comercio.
- Constancia de RTU.
- Fotocopia de DPI de ambos lados del propietario (Pasaporte para extranjeros).
- Recibo de servicios (agua, luz o teléfono).
- Cheque Anulado

### 💰 ¿Cual es el costo?
A continuación te desglosamos los costos que tiene esta pasarela para que te informes antes de adquirir el servicio.
- Pago por afiliación nueva <strong>$325.00 (Pago único)</strong>
- <strong>$0.35</strong> centavos por cada transacción.
- El porcentaje de comisión te lo brinda Visanet dependiendo de la categoría a la que pertenece el negocio.

### ℹ️ Información adicional
Deberás entregar a tu asesor los requisitos y llenar unos formularios que te brindarán para poder iniciar el proceso.

# Guía de uso
A continuacion encontraras como configurar el plugin dentro de tu sitio web de [Wordpress](https://wordpress.com/) y te contaremos un poco como es el proceso de integración con el personal técnico de [Cybersource](https://www.cybersource.com/es-mx.html).

### 🌐 Configuración del lado del portal brindado por Cybersource
Una vez te brinden el acceso a tú portal, ingresa al mismo desde el siguiente link.
- [Test](https://ubctest.cybersource.com/ebc2/)
- [Producción](https://visanetgt.ubc.cybersource.com/ebc2/)

** Recuerda que este plugin utiliza la modalidad de <strong>Secure Acceptance</strong> para operar, dentro del portal puedes customizar la apariencia de este sitio de cobro.

Una vez estés dentro del portal de [Cybersource](https://www.cybersource.com/es-mx.html) debes crear un perfil de <strong>Secure Acceptance</strong> para esto te dejamos el siguiente [tutorial](https://www.ryanplugins.com/how-to-setup-cybersource-secure-acceptance-profile/). Si no encuentras en tu usuario la opción para crear un perfil de <strong>Secure Acceptance</strong> por favor comunícate con tu asesor para que te lo habiliten.

### 💿 Instalación
Requisitos de instalacion
- Contar con [Woocommerce](https://woocommerce.com/) instalado dentro de tu sitio web.
- Haber completado el proceso de solicitud de servicio con el [Cybersource](https://www.cybersource.com/es-mx.html).

Simplemente clona el repositorio, genera un archivo .Zip y súbelo como plugin a tu sitio web de [Wordpress](https://wordpress.com/), recuerda que [Woocommerce](https://woocommerce.com/) debe de estar instalado en el sitio para poder habilitar el plugin.

### ⚙️ Configuración
Una vez instalado debes dirigirte al area de <strong>Woocommerce / Ajustes / Pagos</strong> , aqui podras encontrar tu forma de pago bajo el nombre de <strong>Cybersource Payment Gateway</strong> aqui podrás gestionar las opciones del plugin. 

<strong>Opciones de configuración</strong>
- <strong>Activar/Desactivar :</strong> Con esta opción puede rápidamente habilitar o deshabilitar la pasarela de pago sin desinstalar el plugin.
- <strong>Título :</strong> Nombre que se le mostrará al usuario al seleccionar la opción de pago.
- <strong>Descripción :</strong> Descripcion adicional que se le mostrara al usuario al seleccionar la opción de pago.
- <strong>Status of new order :</strong> Estado el cual [Woocommerce](https://woocommerce.com/) colocará cuando una orden es creada, este estado cambia a Completed cuando el checkout de cybersource regresa Success.
- <strong>ProfileID : </strong> Id del perfil creado de secure acceptance [Cybersource](https://visanetgt.ubc.cybersource.com/ebc2/).
- <strong>Acces Key : </strong> Key de acceso para el perfil de secure acceptance [Cybersource](https://visanetgt.ubc.cybersource.com/ebc2/).
- <strong>Secret Key : </strong> Key de acceso para el perfil de secure acceptance [Cybersource](https://visanetgt.ubc.cybersource.com/ebc2/).
- <strong>Debug Log : </strong> Habilita la opcion d poder guardar un log.
- <strong>Error message : </strong> Este es un mensaje personalizado que se le muestra al usuario al momento que ocurra un error.


# ¿Como contribuir?
Si buscas contribuir en alguno de nuestros proyectos lo puedes hacer de una manera muy fácil, únicamente necesitaras seguir estos 4 pasos.

1. Haz click en la opción de ¨Fork¨ , o bien puedes precionar ![Aquí](https://github.com/TipiCode/WooCommerce-Cybersource/fork)
2. Crea un nuevo branch en el area de branches de github.
3. Nombre tu nuevo branch con un nombre que refleje tu contribución ¨Super mega nueva funcionalidad 3000¨
4. Desarrolla tu cambio y al terminar crea un ¨pull request¨ para poder subir tu nueva funcionalidad, para eso preciona ![Aquí](https://github.com/TipiCode/WooCommerce-Cybersource/pulls)

Si no eres un desarrollador ¡No te preocupes! Aun puedes contribuir de diferentes maneras, puedes apoyarnos a hacer llegar estas librerías a muchas más personas no únicamente en el área de desarrollo, acá te dejamos las demás áreas donde puedes contribuir con este proyecto.

- Redacción.
- Moderador de contenido.
- Documentación de funcionalidades.
- Traducciones.
- Compartiendo el proyecto :)

Cada ayuda nos acerca mas a nuestra meta final, tener un proyecto que pueda ser de utilidad para todos.

# ¿Te fue útil este proyecto?
¡Nos encanta la idea de poder ayudar a crecer tu proyecto! Nuestro esfuerzo como parte de todos los proyectos Open Source con los que contamos tienen como meta ser de ayuda para quien lo necesite, sabemos que muchas veces se requiere una solución para problemas en común, ya sea si estas iniciando un negocio o tienes un proyecto personal y que mejor manera de solucionar ese problema en común que todos juntos.  Si te fue útil nuestro proyecto puedes apoyar a mantenerlo con un pequeño gesto en forma de un [café](https://app.recurrente.com/c/ch_qhynsca9rooyqfdy) para nuestros desarrolladores que contribuyen en este proyecto.

<a href="https://app.recurrente.com/c/ch_qhynsca9rooyqfdy">
<img src="https://tipi-pod.sfo3.cdn.digitaloceanspaces.com/github%2FBuy%20me%20a%20coffee.jpg">
</a>
