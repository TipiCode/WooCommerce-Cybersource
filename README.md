<h1 align="center">
  <img src="https://skyloft.sfo3.cdn.digitaloceanspaces.com/Repos/woo-cyber.jpg" alt="Cybersource">
</h1>

[![GitHub license](https://img.shields.io/github/license/TipiCode/woocommerce-gateway-cybersource.svg)](https://github.com/TipiCode/woocommerce-gateway-cybersource/blob/master/LICENSE)
[![GitHub release](https://img.shields.io/github/v/release/TipiCode/woocommerce-gateway-cybersource.svg)](https://github.com/TipiCode/woocommerce-gateway-cybersource/releases)
[![Github all releases](https://img.shields.io/github/downloads/TipiCode/woocommerce-gateway-cybersource/total.svg)](https://GitHub.com/TipiCode/woocommerce-gateway-cybersource/releases/)
[![Generic badge](https://img.shields.io/badge/Woocommerce-6.1.0-96588a.svg)](https://woocommerce.com/)
[![Generic badge](https://img.shields.io/badge/Wordpress-5.9.0-21759b.svg)](https://wordpress.com/)

Plugin para [Woocommerce](https://woocommerce.com/) que habilita la pasarela de pago de [Cybersource](https://www.cybersource.com/es-mx.html) como método de pago en el checkout de tú sitio web, implementar una pasarela de pago para realizar cobros en linea no tiene porque ser ciencia espacial.

## Primeros pasos
Te compartimos cuáles son los primeros pasos para poder adquirir el servicio por parte del [Cybersource](https://www.cybersource.com/es-mx.html)

## Guía de uso
A continuacion encontraras como configurar el plugin dentro de tu sitio web de [Wordpress](https://wordpress.com/) y te contaremos un poco como es el proceso de integración con el personal técnico de [Cybersource](https://www.cybersource.com/es-mx.html).

### 🌐 Configuración del lado del portal brindado por Cybersource

** Recuerda que este plugin utiliza la modalidad de <strong>Secure Acceptance</strong> para operar, dentro del portal puedes customizar la apariencia de este sitio de cobro.

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

## ¿Como contribuir?
¡Nos encantaría que puedas formar parte de esta comunidad, si deseas contribuir eres libre de hacerlo! te dejamos a continuación documentación oficial de las integraciones con  [Cybersource](https://www.cybersource.com/es-mx.html) para que puedas hecharle un vistazo.
- [Developer Center](https://developer.cybersource.com/api/developer-guides.html)
