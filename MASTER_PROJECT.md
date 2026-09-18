# MASTER PROJECT

## Marketplace Geolocalizado de Productos y Servicios

**Versión:** 1.0
**Estado:** Definición inicial / MVP
**Tipo:** Marketplace geolocalizado
**Backend:** Laravel
**Frontend:** React + TypeScript
**Base de datos:** PostgreSQL + PostGIS

---

# 1. Propósito del proyecto

Construir un marketplace geolocalizado donde cualquier persona pueda descubrir **productos y servicios disponibles cerca de su ubicación**, así como proveedores que trabajan de forma remota.

El usuario debe poder responder fácilmente a una pregunta:

> **¿Qué puedo comprar o contratar cerca de mí?**

El sistema permitirá descubrir, buscar y contactar proveedores de:

* productos físicos;
* servicios profesionales;
* comercios locales;
* profesionales independientes;
* servicios a domicilio;
* servicios presenciales;
* servicios remotos.

Ejemplos:

* un mecánico;
* una persona que vende postres;
* un programador;
* un profesor particular;
* una persona de limpieza;
* un carpintero;
* una peluquería;
* una tienda;
* un fotógrafo;
* un técnico;
* un diseñador;
* un negocio que realiza entregas.

El objetivo inicial no es construir una plataforma financiera ni un sistema complejo de comercio electrónico.

El objetivo del MVP es validar:

1. que los usuarios quieran descubrir productos y servicios mediante ubicación;
2. que los proveedores quieran publicar sus productos y servicios;
3. que la búsqueda geolocalizada sea útil;
4. que exista interacción entre compradores y proveedores.

---

# 2. Principios fundamentales

## 2.1. Geolocalización como característica principal

La ubicación es una parte fundamental del marketplace.

Los resultados deben poder ordenarse y filtrarse según:

* distancia;
* categoría;
* tipo de producto o servicio;
* modalidad;
* precio;
* disponibilidad futura.

La aplicación debe soportar tanto:

* proveedores locales;
* proveedores remotos.

---

## 2.2. Un usuario puede tener varios roles

No se deben crear cuentas independientes para compradores y proveedores.

Un mismo usuario debe poder:

* buscar productos;
* contratar servicios;
* publicar productos;
* ofrecer servicios.

Inicialmente el onboarding preguntará al usuario qué quiere hacer, pero esta elección **no debe limitar permanentemente su cuenta**.

Un usuario que inicialmente entra como comprador podrá convertirse posteriormente en proveedor.

---

## 2.3. El proveedor puede ofrecer múltiples cosas

Un proveedor no debe quedar limitado a una única categoría.

Ejemplo:

Un profesional podría ofrecer:

* desarrollo web;
* mantenimiento de ordenadores;
* clases de programación.

Un negocio podría vender:

* postres;
* tartas;
* desayunos.

La categoría seleccionada durante el onboarding del proveedor representa inicialmente su actividad principal o preferencia inicial.

Posteriormente podrá crear múltiples publicaciones.

---

## 2.4. MVP simple

El MVP debe evitar funcionalidades que aumenten innecesariamente la complejidad.

Inicialmente NO se implementarán:

* criptomonedas;
* tokens propios;
* wallets;
* pagos internos;
* escrow;
* sistemas financieros;
* préstamos;
* marketplace financiero;
* sistemas complejos de delivery;
* chat en tiempo real;
* suscripciones;
* microservicios;
* inteligencia artificial como componente obligatorio.

Estas funcionalidades podrán evaluarse posteriormente si el producto demuestra demanda.

---

# 3. Usuarios

Existen tres conceptos principales de usuario dentro del sistema.

## 3.1. Usuario normal

Puede:

* registrarse;
* completar su perfil;
* buscar productos;
* buscar servicios;
* utilizar el mapa;
* filtrar resultados;
* consultar publicaciones;
* consultar proveedores;
* contactar proveedores.

---

## 3.2. Proveedor

Puede:

* crear un perfil profesional;
* definir información comercial;
* crear publicaciones;
* publicar productos;
* publicar servicios;
* definir modalidades de atención;
* establecer ubicación;
* definir radio de servicio;
* administrar sus publicaciones.

Un proveedor puede ser:

* persona independiente;
* profesional;
* negocio;
* comercio.

---

## 3.3. Administrador

El administrador tendrá capacidades administrativas.

En el MVP inicial serán mínimas y podrán ampliarse posteriormente.

Posibles capacidades:

* administrar usuarios;
* administrar categorías;
* revisar publicaciones;
* desactivar contenido;
* gestionar incidencias.

---

# 4. Autenticación

La autenticación inicial será exclusivamente mediante proveedores OAuth.

Proveedores iniciales:

* Google;
* Facebook.

No se utilizarán contraseñas tradicionales durante el MVP inicial.

La autenticación se implementará utilizando:

* Laravel Socialite;
* sesiones/tokens adecuados para la arquitectura;
* `social_accounts` para relacionar cuentas externas con usuarios internos.

El sistema debe permitir que un usuario pueda tener más de una cuenta social asociada en el futuro.

---

# 5. Registro y onboarding

Después del primer login mediante Google o Facebook, el sistema comprobará si el usuario ha completado su configuración inicial.

Si:

```text
onboarding_completed = false
```

el usuario será enviado a:

```text
/onboarding
```

---

## 5.1. Primer paso

El usuario seleccionará:

### Opción A

> Estoy buscando un producto o servicio

### Opción B

> Quiero ofrecer productos o servicios

Esta elección se utilizará para personalizar el onboarding inicial.

No debe convertirse en una restricción permanente del usuario.

---

# 6. Onboarding del comprador

El onboarding del comprador debe ser mínimo.

Información inicial:

* nombre;
* apellido;
* teléfono opcional;
* ubicación.

El email se obtiene del proveedor OAuth cuando esté disponible.

Después de completar estos datos:

```text
onboarding_completed = true
```

el usuario podrá acceder directamente al marketplace.

---

# 7. Onboarding del proveedor

El proveedor proporcionará información básica adicional.

Datos iniciales:

* nombre;
* apellido o nombre comercial;
* teléfono;
* tipo de proveedor;
* categoría principal;
* tipo de producto o servicio;
* ubicación.

Tipo de proveedor:

```text
INDIVIDUAL
BUSINESS
```

La categoría principal se utilizará para personalizar inicialmente la experiencia.

El proveedor podrá posteriormente crear múltiples publicaciones en diferentes categorías.

---

# 8. Categorías

Las categorías serán dinámicas y estarán almacenadas en la base de datos.

No se deben hardcodear categorías principales en React.

Ejemplo de estructura:

```text
Servicios
├── Reparaciones
├── Limpieza
├── Educación
├── Tecnología
├── Diseño
└── Belleza

Productos
├── Alimentación
├── Hogar
├── Electrónica
├── Ropa
└── Otros
```

La estructura definitiva será configurable.

Las categorías deberán soportar jerarquía:

```text
category
    └── parent_category
```

Esto permitirá categorías y subcategorías.

---

# 9. Publicaciones / Listings

La unidad principal del marketplace será una publicación.

Una publicación puede representar:

* producto;
* servicio.

Cada publicación tendrá inicialmente:

* título;
* descripción;
* tipo;
* categoría;
* proveedor;
* precio;
* moneda;
* tipo de precio;
* estado;
* modalidad;
* imágenes;
* ubicación cuando corresponda.

---

## 9.1. Tipos

```text
PRODUCT
SERVICE
```

---

## 9.2. Estados

Inicialmente:

```text
DRAFT
PUBLISHED
PAUSED
ARCHIVED
```

---

## 9.3. Tipo de precio

El sistema debe permitir diferentes modelos.

Ejemplos:

```text
FIXED
FROM
NEGOTIABLE
CONTACT
```

El precio no debe asumir que todos los servicios tienen un precio fijo.

---

# 10. Modalidades

Las publicaciones podrán tener diferentes modalidades.

Valores iniciales:

```text
REMOTE
ONSITE
AT_PROVIDER
DELIVERY
PICKUP
```

### REMOTE

El servicio se realiza completamente a distancia.

### ONSITE

El proveedor se desplaza hasta el cliente.

### AT_PROVIDER

El cliente se desplaza hasta la ubicación del proveedor.

### DELIVERY

El proveedor entrega el producto al cliente.

### PICKUP

El cliente recoge el producto.

Una publicación puede soportar más de una modalidad cuando tenga sentido.

---

# 11. Geolocalización

La geolocalización será una característica central.

La base de datos utilizará:

```text
PostgreSQL + PostGIS
```

Las coordenadas se almacenarán utilizando:

```text
GEOGRAPHY(POINT, 4326)
```

Las operaciones geográficas deberán ejecutarse en la base de datos siempre que sea posible.

No se deben realizar cálculos geográficos masivos en PHP.

---

# 12. Ubicación de las publicaciones

Una publicación puede tener:

* ubicación física;
* radio de servicio;
* modalidad remota;
* múltiples modalidades.

Ejemplo:

```text
Proveedor:
Madrid

Servicio:
Reparación de ordenadores

Modalidad:
ONSITE

Radio:
15 km
```

El marketplace podrá encontrar ese servicio cuando el usuario esté dentro del radio configurado.

---

# 13. Marketplace

La pantalla principal será el centro del producto.

Debe combinar:

* búsqueda;
* categorías;
* filtros;
* mapa;
* listado de resultados.

En escritorio:

```text
┌──────────────────────────────────────────────┐
│                    Search                    │
├──────────────────────┬───────────────────────┤
│                      │                       │
│      Resultados      │         Mapa          │
│                      │                       │
│      Listing         │       ●       ●       │
│      Listing         │           ●           │
│      Listing         │    ●                  │
│                      │                       │
└──────────────────────┴───────────────────────┘
```

En dispositivos móviles la interfaz podrá alternar entre:

* mapa;
* resultados.

---

# 14. Búsqueda

El marketplace debe permitir buscar mediante:

* texto;
* categoría;
* ubicación;
* distancia;
* tipo;
* modalidad;
* precio.

Ejemplo conceptual:

```http
GET /api/listings
```

Parámetros:

```text
lat
lng
radius
category
type
delivery_type
search
price_min
price_max
```

El backend será responsable de aplicar la lógica de búsqueda y geolocalización.

---

# 15. API

El backend Laravel expondrá una API REST para el frontend.

El frontend no accederá directamente a la base de datos.

Arquitectura:

```text
React
   ↓
REST API
   ↓
Laravel
   ↓
PostgreSQL / PostGIS
```

La API debe ser considerada un contrato entre frontend y backend.

Los cambios en endpoints, parámetros o estructuras de respuesta deben tratarse como cambios de contrato.

---

# 16. Perfil del proveedor

Cada proveedor tendrá un perfil público.

Información inicial:

* nombre;
* descripción;
* tipo;
* categorías;
* ubicación;
* publicaciones;
* modalidades;
* información de contacto disponible.

En fases posteriores podrá incluir:

* valoración;
* reseñas;
* experiencia;
* horarios;
* estadísticas;
* verificación.

---

# 17. Contacto

El MVP no necesita implementar un sistema de chat complejo.

Inicialmente el contacto puede realizarse mediante:

* teléfono;
* email;
* enlace externo;
* WhatsApp.

El sistema podrá evolucionar posteriormente hacia:

```text
Marketplace
    ↓
Chat interno
    ↓
Negociación
    ↓
Reserva
    ↓
Pago
```

Pero este flujo está fuera del MVP inicial.

---

# 18. Imágenes

Las publicaciones podrán tener imágenes.

El sistema debe abstraer el almacenamiento para poder utilizar posteriormente:

* almacenamiento local durante desarrollo;
* almacenamiento compatible con S3;
* otros proveedores de almacenamiento.

No se deben acoplar las reglas de negocio a un proveedor específico.

Las imágenes deberán:

* validar formato;
* validar tamaño;
* almacenarse fuera de la base de datos;
* guardar únicamente sus referencias/metadatos en PostgreSQL.

---

# 19. Arquitectura técnica

## Backend

```text
Laravel
PHP
Laravel Socialite
Laravel Sanctum
PostgreSQL
PostGIS
```

Responsabilidades:

* autenticación;
* autorización;
* usuarios;
* proveedores;
* categorías;
* publicaciones;
* búsqueda;
* geolocalización;
* imágenes;
* API;
* validación;
* seguridad.

---

## Frontend

```text
React
TypeScript
Vite
Tailwind CSS
React Router
TanStack Query
MapLibre GL JS
```

Responsabilidades:

* interfaz;
* navegación;
* onboarding;
* marketplace;
* mapa;
* filtros;
* formularios;
* perfiles;
* consumo de API.

---

# 20. Arquitectura general

El proyecto comenzará como un **monolito modular**, no como microservicios.

Estructura conceptual:

```text
                  ┌───────────────────┐
                  │      Usuario      │
                  └─────────┬─────────┘
                            │
                            ▼
                  ┌───────────────────┐
                  │ React + TypeScript│
                  └─────────┬─────────┘
                            │
                         REST API
                            │
                            ▼
                  ┌───────────────────┐
                  │      Laravel      │
                  │                   │
                  │ Auth              │
                  │ Users             │
                  │ Providers         │
                  │ Listings          │
                  │ Categories        │
                  │ Search            │
                  └─────────┬─────────┘
                            │
                            ▼
                  ┌───────────────────┐
                  │ PostgreSQL        │
                  │ + PostGIS         │
                  └───────────────────┘
```

No se introducirán microservicios salvo que exista una necesidad demostrada.

---

# 21. Modelo de datos inicial

Las entidades principales serán:

```text
users
social_accounts
provider_profiles
categories
listings
listing_locations
listing_images
```

Relaciones principales:

```text
users
 ├── social_accounts
 └── provider_profiles
       └── listings
             ├── categories
             ├── listing_locations
             └── listing_images
```

El modelo podrá evolucionar.

No se debe diseñar una base de datos excesivamente compleja antes de validar el producto.

---

# 22. Usuarios y proveedores

Conceptualmente:

```text
User
 ├── Customer capabilities
 └── Provider capabilities
        └── ProviderProfile
```

No debe existir:

```text
CustomerAccount
ProviderAccount
```

como dos cuentas independientes.

La misma cuenta debe poder utilizar ambas capacidades.

---

# 23. Roles y autorización

Roles iniciales:

```text
USER
PROVIDER
ADMIN
```

Sin embargo, `PROVIDER` representa una capacidad/perfil dentro del usuario y no debe obligar a crear otra cuenta.

La autorización debe realizarse principalmente mediante:

* Policies;
* Gates;
* permisos explícitos.

Nunca se debe confiar únicamente en controles realizados por React.

El backend siempre debe validar autorización.

---

# 24. Seguridad

El proyecto debe seguir prácticas básicas de seguridad desde el principio.

Entre ellas:

* secretos únicamente mediante variables de entorno;
* nunca almacenar OAuth secrets en Git;
* validación de inputs;
* autorización en backend;
* protección de endpoints;
* rate limiting donde sea necesario;
* validación de archivos;
* sanitización de datos;
* evitar información sensible en logs;
* HTTPS en producción;
* configuración adecuada de CORS;
* protección de sesiones/tokens;
* políticas de acceso para recursos.

---

# 25. Coste y estrategia de infraestructura

El MVP debe diseñarse para minimizar costes.

Principios:

1. utilizar software open source cuando sea razonable;
2. evitar microservicios;
3. evitar infraestructura innecesaria;
4. utilizar PostgreSQL;
5. utilizar almacenamiento económico y compatible con S3;
6. mantener los servicios desacoplados mediante interfaces simples;
7. evitar depender de servicios externos caros sin necesidad;
8. revisar precios y condiciones de los proveedores antes del despliegue en producción.

Durante desarrollo debe ser posible ejecutar la mayor parte del sistema localmente.

---

# 26. Mapas

La interfaz utilizará:

```text
MapLibre GL JS
```

La aplicación no debe acoplar la lógica del producto a un proveedor concreto de mapas.

Los proveedores de tiles/geocodificación podrán cambiar según:

* precio;
* límites;
* disponibilidad;
* licencia;
* necesidades de producción.

La selección definitiva del proveedor debe evaluarse antes de producción.

---

# 27. Arquitectura de frontend

La aplicación React deberá organizarse de forma modular.

Conceptualmente:

```text
frontend/
├── src/
│   ├── app/
│   ├── components/
│   ├── features/
│   │   ├── auth/
│   │   ├── onboarding/
│   │   ├── marketplace/
│   │   ├── listings/
│   │   ├── providers/
│   │   └── profile/
│   ├── hooks/
│   ├── lib/
│   ├── services/
│   ├── types/
│   └── routes/
```

La estructura definitiva puede evolucionar mientras mantenga separación clara de responsabilidades.

---

# 28. Arquitectura de backend

Laravel deberá mantener separación razonable entre:

* HTTP/controllers;
* validación;
* autorización;
* modelos;
* lógica de negocio;
* recursos API;
* servicios externos.

No se debe implementar una arquitectura excesivamente abstracta únicamente por seguir patrones.

Se priorizará:

> código sencillo, mantenible y fácil de modificar.

---

# 29. Testing

El proyecto debe incluir pruebas desde el MVP.

Backend:

* autenticación;
* autorización;
* onboarding;
* creación de publicaciones;
* actualización de publicaciones;
* búsqueda;
* filtros;
* geolocalización;
* permisos.

Frontend:

* componentes críticos;
* navegación;
* formularios;
* estados de error;
* integración con API cuando sea necesario.

Cada nueva funcionalidad importante debe incluir las pruebas correspondientes.

---

# 30. Roadmap

## Fase 0 — Base técnica

* estructura del repositorio;
* Laravel;
* React;
* TypeScript;
* PostgreSQL;
* PostGIS;
* configuración local;
* testing;
* CI básica.

---

## Fase 1 — Autenticación

* Google OAuth;
* Facebook OAuth;
* usuarios;
* social accounts;
* sesión/autorización.

---

## Fase 2 — Onboarding

* detección de primer acceso;
* selección de intención;
* onboarding de comprador;
* onboarding de proveedor;
* ubicación.

---

## Fase 3 — Categorías

* categorías;
* subcategorías;
* administración básica;
* API de categorías.

---

## Fase 4 — Proveedores

* provider profiles;
* información profesional;
* categorías;
* modalidades.

---

## Fase 5 — Listings

* crear;
* editar;
* publicar;
* pausar;
* archivar;
* imágenes;
* precios;
* modalidades.

---

## Fase 6 — Geolocalización

* PostGIS;
* puntos geográficos;
* radios;
* búsqueda por distancia;
* filtros geográficos.

---

## Fase 7 — Marketplace

* búsqueda;
* filtros;
* resultados;
* mapa;
* integración mapa/listado;
* vista móvil.

---

## Fase 8 — Perfiles y contacto

* perfil público;
* publicaciones del proveedor;
* contacto;
* enlaces externos.

---

## Fase 9 — Testing y estabilización

* pruebas;
* seguridad;
* UX;
* rendimiento;
* errores;
* validación del MVP.

---

## Fase 10 — Deploy

* producción;
* dominio;
* HTTPS;
* base de datos;
* almacenamiento;
* mapas;
* monitorización básica.

---

# 31. Fuera del MVP

Las siguientes funcionalidades quedan explícitamente fuera del MVP salvo decisión posterior:

### Pagos

* pagos internos;
* checkout;
* wallets;
* escrow;
* pagos recurrentes.

### Finanzas

* monedas propias;
* tokens;
* criptomonedas;
* stablecoins;
* conversión monetaria;
* préstamos.

### Comunicación

* chat realtime;
* videollamadas;
* llamadas internas.

### Delivery

* flota propia;
* tracking avanzado;
* optimización de rutas;
* asignación automática de repartidores.

### Reputación

* sistema avanzado de reviews;
* reputación algorítmica;
* verificación avanzada.

### Inteligencia artificial

* recomendaciones;
* búsqueda semántica;
* generación automática de publicaciones;
* asistentes.

Estas funcionalidades podrán evaluarse después de validar el núcleo del marketplace.

---

# 32. Principios de desarrollo

Todos los agentes y desarrolladores deben seguir estas reglas:

### 1. No cambiar la arquitectura silenciosamente

Si una decisión técnica importante requiere modificar la arquitectura, debe documentarse y revisarse.

### 2. No añadir dependencias innecesarias

Antes de introducir una nueva librería se debe comprobar si la funcionalidad puede resolverse razonablemente con las herramientas existentes.

### 3. No hacer scope creep

Una tarea debe implementar únicamente lo necesario para cumplir su objetivo.

### 4. No duplicar lógica

La lógica debe existir en un único lugar cuando sea razonable.

### 5. El backend es la autoridad

La validación, autorización y reglas de negocio importantes deben existir en backend.

### 6. La API es un contrato

Frontend y backend deben respetar contratos claros.

### 7. Las migraciones deben ser reversibles cuando sea razonable

Los cambios de base de datos deben realizarse mediante migraciones versionadas.

### 8. Las funcionalidades importantes requieren tests

No se considera terminada una funcionalidad crítica sin pruebas adecuadas.

---

# 33. Trabajo con agentes

El proyecto utilizará inicialmente dos agentes principales:

## Backend Agent

Responsable de:

* Laravel;
* PHP;
* PostgreSQL;
* PostGIS;
* autenticación;
* API;
* modelos;
* migraciones;
* autorización;
* validación;
* testing backend.

---

## Frontend Agent

Responsable de:

* React;
* TypeScript;
* Tailwind;
* navegación;
* componentes;
* formularios;
* mapas;
* experiencia de usuario;
* consumo de API;
* testing frontend.

---

## Regla de colaboración

El Backend Agent es responsable de definir y mantener el contrato de la API.

El Frontend Agent consume dicho contrato.

Si una funcionalidad requiere cambios coordinados:

```text
Backend
   ↓
API Contract
   ↓
Frontend
```

Los agentes no deben modificar arbitrariamente el trabajo del otro.

---

# 34. Git

El proyecto debe utilizar Git desde el inicio.

Ramas recomendadas:

```text
main
develop
feature/backend-*
feature/frontend-*
fix/*
chore/*
```

Los cambios importantes deben realizarse mediante ramas.

Los commits deben ser pequeños y descriptivos.

Formato recomendado:

```text
feat:
fix:
refactor:
test:
docs:
chore:
```

No se deben introducir cambios no relacionados dentro de una misma tarea.

---

# 35. Documentación

La documentación del proyecto estará organizada progresivamente.

Estructura prevista:

```text
MASTER_PROJECT.md
AGENTS.md

docs/
├── PRODUCT.md
├── ARCHITECTURE.md
├── API.md
├── DATABASE.md
├── UX.md
├── ROADMAP.md
└── DECISIONS.md
```

`MASTER_PROJECT.md` contiene la visión y las reglas fundamentales.

Los documentos dentro de `docs/` desarrollarán cada área con mayor detalle.

---

# 36. Fuente de verdad

En caso de contradicción entre documentos:

1. decisiones explícitas tomadas posteriormente;
2. `MASTER_PROJECT.md`;
3. documentación específica;
4. código existente.

Si un agente detecta una contradicción importante, debe señalarla antes de realizar cambios que puedan afectar la arquitectura.

---

# 37. Criterios de aceptación del MVP

El MVP se considera técnicamente funcional cuando un usuario puede:

1. registrarse mediante Google o Facebook;
2. completar su onboarding;
3. elegir inicialmente si busca u ofrece;
4. explorar categorías;
5. buscar productos y servicios;
6. utilizar su ubicación;
7. visualizar resultados cercanos;
8. visualizar resultados en un mapa;
9. aplicar filtros;
10. consultar una publicación;
11. consultar un proveedor;
12. contactar al proveedor mediante los mecanismos disponibles.

Un proveedor debe poder:

1. registrarse;
2. completar su perfil;
3. seleccionar su categoría principal;
4. crear publicaciones;
5. definir producto o servicio;
6. establecer precio;
7. establecer modalidad;
8. establecer ubicación;
9. establecer radio de servicio cuando corresponda;
10. publicar y administrar sus publicaciones.

---

# 38. Métrica principal del MVP

El objetivo inicial no es maximizar funcionalidades.

Las primeras métricas relevantes deberán responder preguntas como:

* ¿Los usuarios realizan búsquedas?
* ¿Encuentran resultados relevantes?
* ¿Los resultados cercanos son útiles?
* ¿Los proveedores publican productos/servicios?
* ¿Los usuarios contactan proveedores?
* ¿Los proveedores reciben contactos?
* ¿Qué categorías tienen mayor actividad?
* ¿Qué zonas tienen mayor oferta/demanda?

Estas métricas ayudarán a decidir qué funcionalidades construir posteriormente.

---

# 39. Filosofía del proyecto

El proyecto debe seguir una filosofía:

> **Construir primero el marketplace que conecta oferta y demanda.**

Antes de añadir:

* pagos;
* inteligencia artificial;
* chat;
* criptomonedas;
* delivery;
* sistemas complejos de reputación;

debemos demostrar que existe valor en:

```text
Usuario
   ↓
Busca algo
   ↓
Encuentra oferta
   ↓
Evalúa opciones
   ↓
Contacta proveedor
   ↓
Se produce una interacción comercial
```

Ese flujo constituye el núcleo del producto.

---

# 40. Estado actual

El proyecto se encuentra en:

```text
DEFINITION / MVP PLANNING
```

La siguiente etapa es construir la infraestructura inicial del proyecto y comenzar la implementación por fases.

No se deben implementar funcionalidades fuera del roadmap sin una decisión explícita.

---

# 41. Regla final

Ante cualquier decisión técnica o de producto, priorizar en este orden:

1. simplicidad;
2. coste bajo;
3. mantenibilidad;
4. seguridad;
5. experiencia de usuario;
6. capacidad de evolución.

El objetivo no es construir la arquitectura más sofisticada.

El objetivo es construir **la versión más sencilla del producto capaz de validar la idea**.
