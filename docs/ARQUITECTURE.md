# ARCHITECTURE.md

# Project Architecture

Este documento define la arquitectura técnica inicial del marketplace.

Para la visión general del producto consultar:

```text
MASTER_PROJECT.md
```

Para las reglas de trabajo de los agentes consultar:

```text
AGENTS.md
```

---

# 1. Architectural Goal

Construir una aplicación web modular, sencilla y económica que pueda evolucionar progresivamente sin introducir complejidad innecesaria.

La arquitectura inicial será un **monolito modular** compuesto por:

```text
React + TypeScript
        │
        │ REST API
        ▼
Laravel
        │
        ▼
PostgreSQL + PostGIS
```

No se utilizarán microservicios en el MVP.

---

# 2. Repository Structure

La estructura inicial será:

```text
/
├── MASTER_PROJECT.md
├── AGENTS.md
│
├── docs/
│   ├── ARCHITECTURE.md
│   └── DECISIONS.md
│
├── backend/
│   └── Laravel application
│
└── frontend/
    └── React application
```

La estructura podrá evolucionar cuando el proyecto crezca.

---

# 3. Backend

Tecnología:

```text
Laravel
PHP
PostgreSQL
PostGIS
```

El backend será responsable de:

* autenticación;
* usuarios;
* perfiles de proveedores;
* categorías;
* publicaciones;
* imágenes;
* búsqueda;
* geolocalización;
* autorización;
* validación;
* API REST.

El frontend nunca accederá directamente a PostgreSQL.

---

# 4. Frontend

Tecnología:

```text
React
TypeScript
Vite
Tailwind CSS
React Router
TanStack Query
MapLibre GL JS
```

El frontend será responsable de:

* interfaz;
* navegación;
* onboarding;
* marketplace;
* búsqueda;
* filtros;
* mapa;
* perfiles;
* formularios;
* integración con API.

Las reglas de negocio críticas no deben depender exclusivamente del frontend.

---

# 5. Communication

La comunicación entre frontend y backend será mediante HTTP/REST.

```text
Browser
   │
   ▼
React
   │
   ▼
REST API
   │
   ▼
Laravel
   │
   ▼
PostgreSQL/PostGIS
```

No se implementará GraphQL inicialmente.

No se implementará comunicación realtime inicialmente.

---

# 6. Authentication

La autenticación inicial utilizará:

```text
Google OAuth
Facebook OAuth
```

Laravel Socialite será utilizado para integrar los proveedores OAuth.

La aplicación no utilizará contraseñas tradicionales en el MVP inicial.

La identidad externa estará relacionada con el usuario interno mediante:

```text
social_accounts
```

---

# 7. API Authentication

La autenticación de la API utilizará una estrategia compatible con Laravel y el tipo de cliente utilizado.

La implementación inicial deberá priorizar:

* seguridad;
* simplicidad;
* compatibilidad con React;
* facilidad de despliegue.

Laravel Sanctum será la solución inicial prevista.

El Backend Agent debe validar la configuración concreta de Sanctum con la arquitectura final antes de implementar autenticación.

---

# 8. Database

Base de datos:

```text
PostgreSQL
```

Extensión geográfica:

```text
PostGIS
```

PostgreSQL será la fuente principal de datos de aplicación.

No se utilizará una base de datos NoSQL en el MVP.

---

# 9. Geospatial Architecture

La geolocalización es una parte central del producto.

Las ubicaciones utilizarán:

```text
GEOGRAPHY(POINT, 4326)
```

Las consultas de proximidad deberán ejecutarse mediante PostGIS.

Ejemplo conceptual:

```text
User location
      │
      ▼
PostGIS spatial query
      │
      ▼
Nearby listings
```

No se deben recuperar grandes cantidades de publicaciones para calcular distancias en PHP.

---

# 10. Storage

Las imágenes no se almacenarán directamente como blobs dentro de PostgreSQL.

La arquitectura debe utilizar un sistema de almacenamiento de archivos independiente.

Durante desarrollo puede utilizarse almacenamiento local.

Para producción se deberá poder utilizar almacenamiento compatible con S3.

La aplicación debe almacenar en PostgreSQL únicamente los metadatos y referencias necesarios.

---

# 11. Maps

La interfaz utilizará:

```text
MapLibre GL JS
```

La lógica del marketplace no debe depender directamente de un proveedor concreto de mapas.

La configuración del proveedor de tiles deberá ser sustituible.

La elección definitiva del proveedor de tiles/geocoding debe considerar:

* precio;
* límites;
* licencia;
* cobertura;
* rendimiento;
* condiciones de producción.

---

# 12. Frontend State

Se distinguirán tres tipos de estado:

```text
Server State
UI State
Form State
```

El estado procedente de la API deberá gestionarse principalmente mediante:

```text
TanStack Query
```

El estado visual/local deberá permanecer en React cuando sea suficiente.

No introducir Redux u otro gestor global salvo que exista una necesidad demostrada.

---

# 13. Frontend Routing

React Router será utilizado para navegación.

Las rutas iniciales podrán incluir:

```text
/
 /login
 /onboarding
 /marketplace
 /listings/:id
 /providers/:id
 /profile
 /provider
 /provider/listings
```

Las rutas definitivas podrán evolucionar durante la implementación.

---

# 14. Backend Structure

La aplicación Laravel debe utilizar una estructura clara y convencional.

Conceptualmente:

```text
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Policies/
│   └── Services/
│
├── database/
│   ├── migrations/
│   ├── seeders/
│   └── factories/
│
├── routes/
│   └── api.php
│
└── tests/
```

No crear capas adicionales hasta que sean necesarias.

---

# 15. Frontend Structure

La aplicación React debe organizarse por funcionalidades cuando sea razonable.

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
│   ├── services/
│   ├── hooks/
│   ├── types/
│   └── routes/
│
└── tests/
```

La estructura puede modificarse si la implementación demuestra que otra organización es más sencilla.

---

# 16. Environments

El proyecto debe soportar al menos:

```text
local
production
```

Podrá añadirse:

```text
staging
```

cuando sea necesario.

Los secretos se gestionarán mediante variables de entorno.

Nunca almacenar credenciales reales en Git.

Debe existir:

```text
.env.example
```

---

# 17. Configuration

Las configuraciones externas deben mantenerse fuera del código cuando sea razonable.

Ejemplos:

```text
Database credentials
OAuth credentials
Storage credentials
Map provider configuration
Application URL
API configuration
```

---

# 18. Testing Architecture

El backend utilizará el sistema de testing de Laravel/PHPUnit o la configuración equivalente adoptada por Laravel.

El frontend utilizará el sistema de testing que se configure durante el bootstrap.

La infraestructura inicial debe permitir ejecutar tests localmente y posteriormente en CI.

---

# 19. CI

El proyecto debe disponer progresivamente de CI para comprobar:

Backend:

```text
Install dependencies
Run migrations/setup
Run tests
```

Frontend:

```text
Install dependencies
Lint
Run tests
Build
```

La CI no debe requerir servicios externos de pago para funcionar.

---

# 20. Deployment Philosophy

La primera versión debe poder desplegarse con infraestructura sencilla.

No crear:

```text
Kubernetes
Microservices
Service mesh
Message brokers
Complex orchestration
```

sin una necesidad demostrada.

La arquitectura inicial debe permitir crecer posteriormente sin obligarnos a introducir esa complejidad desde el primer día.

---

# 21. External Services

Los servicios externos deben estar aislados mediante configuración y, cuando sea razonable, interfaces internas.

Ejemplos:

```text
OAuth providers
Map tiles
Geocoding
Object storage
Email
```

No hacer que la lógica central del marketplace dependa innecesariamente de la implementación concreta de un proveedor.

---

# 22. API Contract

El backend será responsable de definir el contrato de la API.

El frontend consumirá ese contrato.

Cuando exista una funcionalidad nueva:

```text
Requirement
     ↓
Backend API contract
     ↓
Backend implementation
     ↓
Frontend integration
```

No asumir endpoints que todavía no existen.

---

# 23. Database Ownership

El Backend Agent es responsable de:

* esquema;
* migraciones;
* relaciones;
* índices;
* consultas;
* integridad de datos.

El Frontend Agent no modifica directamente la base de datos.

---

# 24. Security Boundary

La frontera de seguridad principal está en el backend.

El frontend puede:

* ocultar opciones;
* validar formularios para UX;
* controlar navegación.

Pero el backend debe:

* autenticar;
* autorizar;
* validar;
* proteger datos;
* aplicar reglas de negocio.

---

# 25. Scalability Strategy

La primera estrategia de escalabilidad será:

```text
Correct database design
        ↓
Proper indexes
        ↓
Pagination
        ↓
Efficient queries
        ↓
Caching when justified
        ↓
Horizontal scaling if needed
```

No introducir infraestructura de escalabilidad avanzada antes de que exista una necesidad real.

---

# 26. Cost Strategy

La arquitectura debe minimizar costes durante la validación del producto.

Prioridades:

1. desarrollo local;
2. servicios open source;
3. infraestructura sencilla;
4. proveedores económicos;
5. evitar servicios innecesarios;
6. evitar costes recurrentes prematuros.

Las decisiones de proveedores de producción se tomarán posteriormente basándose en precios y necesidades reales.

---

# 27. Architectural Constraints

Estas decisiones son parte de la arquitectura inicial:

```text
Laravel backend
React frontend
PostgreSQL
PostGIS
REST API
Monolith
OAuth
MapLibre
```

Cambiar una de ellas requiere revisar el impacto antes de hacerlo.

---

# 28. Future Evolution

La arquitectura debe permitir incorporar posteriormente:

* pagos;
* chat;
* reservas;
* reviews;
* delivery;
* recomendaciones;
* analítica;
* nuevas modalidades comerciales.

Pero no se deben implementar estas capacidades ahora únicamente para "prepararlas".

La arquitectura debe ser extensible sin convertirse prematuramente en una arquitectura compleja.

---

# 29. Initial Architecture Decision

La arquitectura inicial queda definida como:

```text
                    ┌───────────────┐
                    │    Browser    │
                    └───────┬───────┘
                            │
                            ▼
                 ┌──────────────────┐
                 │ React + TypeScript│
                 └────────┬─────────┘
                          │
                       REST API
                          │
                          ▼
                 ┌──────────────────┐
                 │     Laravel      │
                 │                  │
                 │ Auth             │
                 │ Users            │
                 │ Providers        │
                 │ Categories       │
                 │ Listings         │
                 │ Search           │
                 │ Geolocation      │
                 └────────┬─────────┘
                          │
                          ▼
                 ┌──────────────────┐
                 │ PostgreSQL       │
                 │ + PostGIS        │
                 └──────────────────┘

                 ┌──────────────────┐
                 │ Object Storage   │
                 │ Images           │
                 └──────────────────┘
```

Esta arquitectura es la base técnica del MVP.

Cualquier evolución significativa debe quedar documentada.
