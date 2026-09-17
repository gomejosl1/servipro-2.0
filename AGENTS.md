# AGENTS.md

# Agent Operating Rules

Este documento define las reglas operativas para todos los agentes de desarrollo que trabajen en este repositorio.

`MASTER_PROJECT.md` define **qué estamos construyendo**.

Este archivo define **cómo deben trabajar los agentes para construirlo**.

En caso de duda sobre el producto, consultar primero `MASTER_PROJECT.md`.

---

# 1. Jerarquía de documentación

Los agentes deben conocer estos documentos:

```text
MASTER_PROJECT.md
        │
        ├── Define producto y alcance
        │
        ▼
AGENTS.md
        │
        └── Define reglas de trabajo de agentes
```

Posteriormente podrán existir:

```text
docs/
├── PRODUCT.md
├── ARCHITECTURE.md
├── API.md
├── DATABASE.md
├── UX.md
├── ROADMAP.md
└── DECISIONS.md
```

Regla:

* `MASTER_PROJECT.md` = fuente principal sobre producto y alcance.
* `AGENTS.md` = reglas operativas para agentes.
* `docs/*` = documentación detallada.
* Código = implementación actual.

Si existe una contradicción importante entre documentación y código, el agente debe detectarla y comunicarla.

No debe corregir silenciosamente decisiones de arquitectura o producto.

---

# 2. Principios generales

Todos los agentes deben seguir estos principios.

## 2.1. Inspect before changing

Antes de modificar código:

1. leer la documentación relevante;
2. inspeccionar la estructura existente;
3. revisar código relacionado;
4. revisar migraciones existentes;
5. revisar tests existentes;
6. identificar dependencias con otras partes del sistema.

No asumir que una funcionalidad no existe simplemente porque no aparece en la descripción de la tarea.

---

## 2.2. No inventar requisitos

Un agente no debe inventar:

* funcionalidades;
* endpoints;
* campos;
* reglas de negocio;
* roles;
* permisos;
* integraciones;
* modelos de datos.

Si una decisión es necesaria pero no está definida, debe:

1. buscar si existe una decisión previa;
2. evaluar si puede implementar una solución local y reversible;
3. si la decisión afecta al producto o arquitectura, detenerse y solicitar definición.

---

## 2.3. No hacer scope creep

Una tarea debe implementar únicamente lo necesario para cumplir su objetivo.

No añadir durante una tarea:

* funcionalidades futuras;
* refactors no relacionados;
* nuevas abstracciones innecesarias;
* librerías por conveniencia;
* cambios visuales no solicitados;
* optimizaciones prematuras.

Si se detecta una mejora útil pero fuera del alcance:

```text
NO implementarla automáticamente.
```

Debe comunicarse como una recomendación separada.

---

# 3. Roles de los agentes

El proyecto utilizará inicialmente dos agentes principales.

---

# 3.1. Backend Agent

Responsable principalmente de:

* Laravel;
* PHP;
* PostgreSQL;
* PostGIS;
* migraciones;
* modelos;
* autenticación;
* autorización;
* API REST;
* validación;
* lógica de negocio;
* almacenamiento;
* tests backend.

Directorio principal:

```text
backend/
```

No debe modificar el frontend salvo que una tarea lo requiera explícitamente.

---

# 3.2. Frontend Agent

Responsable principalmente de:

* React;
* TypeScript;
* Vite;
* Tailwind;
* React Router;
* TanStack Query;
* MapLibre;
* componentes;
* páginas;
* navegación;
* formularios;
* experiencia de usuario;
* integración con API;
* tests frontend.

Directorio principal:

```text
frontend/
```

No debe modificar el backend salvo que una tarea lo requiera explícitamente.

---

# 3.3. Responsabilidad compartida

Ambos agentes son responsables de:

* mantener la calidad del código;
* respetar la documentación;
* mantener los tests;
* evitar cambios innecesarios;
* documentar decisiones importantes;
* informar problemas;
* respetar el contrato entre frontend y backend.

---

# 4. Flujo obligatorio de trabajo

Para cualquier tarea no trivial, seguir este proceso:

```text
1. Read
2. Inspect
3. Plan
4. Implement
5. Test
6. Review
7. Report
```

---

## 4.1. Read

Leer:

```text
MASTER_PROJECT.md
AGENTS.md
```

y cualquier documentación específica relacionada con la tarea.

Ejemplo:

Una tarea de API requiere consultar:

```text
MASTER_PROJECT.md
AGENTS.md
docs/API.md
```

si `docs/API.md` ya existe.

---

# 5. Inspect

Antes de implementar:

* revisar archivos existentes;
* buscar implementaciones relacionadas;
* revisar rutas;
* revisar modelos;
* revisar componentes;
* revisar tests;
* revisar configuración;
* revisar migraciones.

El agente debe reutilizar correctamente el código existente cuando sea apropiado.

No crear una segunda implementación de una funcionalidad existente.

---

# 6. Plan

Para tareas que impliquen múltiples archivos o decisiones técnicas, el agente debe establecer primero un plan breve.

El plan debe indicar:

```text
Objective
Files/components affected
Database changes
API changes
Frontend changes
Tests
Potential risks
```

El plan debe ser proporcional a la tarea.

No es necesario producir documentación extensa para un cambio trivial.

---

# 7. Implementación

Durante la implementación:

* mantener los cambios enfocados;
* respetar las convenciones existentes;
* evitar duplicación;
* evitar abstracciones innecesarias;
* mantener compatibilidad cuando sea posible;
* escribir código mantenible.

No realizar cambios masivos sin necesidad.

---

# 8. Cambios de arquitectura

Los agentes no deben cambiar silenciosamente:

* framework;
* arquitectura general;
* sistema de autenticación;
* base de datos;
* estrategia de almacenamiento;
* proveedor principal de infraestructura;
* sistema de mapas;
* estrategia de comunicación frontend/backend;
* estructura fundamental del proyecto.

Si una tarea parece requerir uno de estos cambios:

```text
STOP
```

Explicar:

1. problema;
2. solución propuesta;
3. alternativas;
4. impacto;
5. coste/complejidad.

Esperar aprobación cuando el cambio sea significativo.

---

# 9. Dependencias

Antes de añadir una dependencia:

1. comprobar si el proyecto ya dispone de una solución;
2. comprobar si Laravel/React/browser puede resolverlo directamente;
3. evaluar mantenimiento;
4. evaluar tamaño;
5. evaluar seguridad;
6. evaluar coste;
7. evaluar si realmente es necesaria.

No añadir una dependencia simplemente por ahorrar unas pocas líneas de código.

Toda dependencia importante debe ser justificable.

---

# 10. Backend Rules

## 10.1. Laravel

Seguir las convenciones de Laravel siempre que sean adecuadas.

Preferir:

* Form Requests para validación;
* Policies para autorización;
* API Resources para respuestas;
* Eloquent para acceso normal a datos;
* Services únicamente cuando aporten valor real;
* Jobs para procesos realmente asíncronos;
* Events/Listeners cuando exista una necesidad clara.

No construir capas de abstracción innecesarias.

---

# 11. Controllers

Los controllers deben mantenerse razonablemente pequeños.

Un controller debe encargarse principalmente de:

```text
Request
   ↓
Validation
   ↓
Authorization
   ↓
Application logic
   ↓
Response
```

La lógica compleja no debe acumularse dentro de los controllers.

---

# 12. Validación

Toda entrada procedente del cliente debe validarse en backend.

Nunca confiar únicamente en:

* validación de React;
* JavaScript;
* controles HTML;
* parámetros enviados por el frontend.

El backend es la autoridad final.

---

# 13. Autorización

Toda operación sensible debe verificar permisos en backend.

Ejemplo:

Un proveedor solo puede modificar una publicación que le pertenece.

No confiar en que React simplemente oculte el botón de edición.

El usuario debe poder llamar directamente al endpoint sin poder saltarse las reglas de autorización.

---

# 14. Base de datos

La base de datos principal será:

```text
PostgreSQL
```

con:

```text
PostGIS
```

Los cambios de esquema deben realizarse mediante migraciones.

No modificar manualmente la base de datos de forma que no pueda reproducirse mediante el proyecto.

---

# 15. PostGIS

Las operaciones geográficas deben realizarse preferentemente mediante PostgreSQL/PostGIS.

No cargar grandes cantidades de registros en PHP para calcular:

* distancia;
* radios;
* proximidad;
* bounding boxes;
* búsquedas geográficas.

Cuando exista una consulta geográfica, utilizar las capacidades de PostGIS.

---

# 16. Índices

Las tablas deben disponer de índices adecuados para las consultas reales.

Especial atención a:

* foreign keys;
* categorías;
* estados;
* búsquedas frecuentes;
* columnas geográficas;
* campos utilizados para filtros.

No añadir índices indiscriminadamente.

Los índices deben estar justificados por consultas o necesidades conocidas.

---

# 17. Migraciones

Cada cambio de esquema debe incluir una migración.

Las migraciones deben:

* ser reproducibles;
* ser claras;
* evitar pérdida accidental de datos;
* incluir índices necesarios;
* respetar relaciones;
* contemplar rollback cuando sea razonable.

Los cambios destructivos requieren especial cuidado.

---

# 18. API Rules

La API REST es el contrato entre backend y frontend.

El Backend Agent debe evitar cambiar silenciosamente:

* URLs;
* métodos HTTP;
* nombres de campos;
* tipos de datos;
* estructura de respuestas;
* códigos HTTP;
* reglas de autenticación.

Un cambio de contrato debe comunicarse.

---

# 19. API naming

Mantener convenciones consistentes.

Ejemplo:

```http
GET    /api/listings
POST   /api/listings
GET    /api/listings/{listing}
PUT    /api/listings/{listing}
DELETE /api/listings/{listing}
```

No crear convenciones diferentes para recursos equivalentes.

---

# 20. API responses

Las respuestas deben mantener una estructura consistente.

Ejemplo conceptual:

```json
{
  "data": {}
}
```

Para colecciones:

```json
{
  "data": [],
  "meta": {}
}
```

Para errores:

```json
{
  "message": "Validation failed.",
  "errors": {}
}
```

La estructura definitiva deberá mantenerse consistente en toda la API.

---

# 21. Frontend Rules

## 21.1. TypeScript

Utilizar TypeScript.

Evitar:

```typescript
any
```

salvo casos excepcionales y justificados.

Los datos procedentes de la API deben tener tipos claros.

---

# 22. Server state

Para estado procedente del backend utilizar:

```text
TanStack Query
```

No duplicar innecesariamente datos del servidor dentro de estados locales de React.

Separar conceptualmente:

```text
Server State
UI State
Form State
```

---

# 23. API client

El acceso a la API debe estar centralizado.

Evitar realizar requests directamente desde múltiples componentes con lógica duplicada.

Preferir una estructura como:

```text
services/
    api/
        auth
        listings
        categories
        providers
```

La estructura concreta puede adaptarse al proyecto.

---

# 24. Componentes

Los componentes deben tener una responsabilidad clara.

Evitar componentes gigantes que contengan:

* llamadas API;
* lógica de negocio;
* navegación;
* formularios;
* presentación;
* estado complejo;

todo en un único archivo.

Dividir cuando la complejidad lo justifique.

No dividir artificialmente componentes simples.

---

# 25. UX

Todas las pantallas que consuman API deben contemplar:

```text
Loading
Success
Empty
Error
```

Ejemplo:

```text
Loading listings...
No listings found.
Unable to load listings.
Listings loaded.
```

No dejar pantallas rotas o vacías cuando una request falla.

---

# 26. Responsive design

El marketplace debe funcionar correctamente en:

* móvil;
* tablet;
* escritorio.

El desarrollo debe seguir un enfoque mobile-first cuando sea razonable.

La interfaz principal debe tener especial cuidado con:

* mapa;
* resultados;
* filtros;
* navegación;
* formularios.

---

# 27. Accesibilidad

Las interfaces deben incluir como mínimo:

* labels adecuados;
* botones semánticos;
* navegación por teclado cuando sea aplicable;
* contraste razonable;
* mensajes de error comprensibles;
* estados de foco.

No utilizar elementos visuales como sustitutos de controles semánticos.

---

# 28. Mapas

La interfaz utilizará:

```text
MapLibre GL JS
```

El frontend no debe asumir que el proveedor de tiles será permanente.

La configuración del mapa debe ser suficientemente desacoplada para poder cambiar el proveedor posteriormente.

---

# 29. Comunicación entre agentes

El Backend Agent y Frontend Agent deben trabajar con una separación clara.

```text
Backend Agent
     │
     │ API Contract
     ▼
Frontend Agent
```

El backend define:

* endpoint;
* método;
* parámetros;
* validación;
* respuesta;
* errores;
* autorización.

El frontend implementa la experiencia que consume ese contrato.

---

# 30. Cambios coordinados

Cuando una tarea requiera backend y frontend:

### Paso 1

Definir primero el contrato.

### Paso 2

Implementar backend.

### Paso 3

Probar endpoint.

### Paso 4

Integrar frontend.

### Paso 5

Ejecutar pruebas de integración relevantes.

Si el frontend necesita avanzar antes de que el backend esté disponible, puede utilizar datos mock temporales, pero deben eliminarse o aislarse antes de finalizar la funcionalidad.

---

# 31. Archivos compartidos

Los agentes deben evitar modificar simultáneamente los mismos archivos.

Especialmente:

* configuración raíz;
* documentación;
* package manifests;
* Docker configuration;
* CI;
* archivos de entorno.

Si una modificación compartida es necesaria, debe coordinarse.

---

# 32. Git

No realizar commits directamente sobre:

```text
main
```

salvo que el flujo del repositorio lo indique explícitamente.

Preferir:

```text
feature/backend-*
feature/frontend-*
fix/*
chore/*
```

Ejemplos:

```text
feature/backend-google-auth
feature/frontend-onboarding
fix/backend-listing-validation
```

---

# 33. Commits

Los commits deben ser pequeños y tener una intención clara.

Formato recomendado:

```text
feat: add listing creation endpoint
fix: validate listing ownership
test: add listing authorization tests
refactor: simplify listing service
docs: update API documentation
chore: update dependencies
```

Evitar commits como:

```text
update
changes
fix stuff
misc
```

---

# 34. Pull Requests

Cada Pull Request debe explicar:

```text
## What
Qué se implementó.

## Why
Por qué era necesario.

## Changes
Cambios principales.

## Tests
Pruebas ejecutadas.

## Database
Cambios de base de datos.

## API
Cambios del contrato.

## Risks
Riesgos conocidos.

## Follow-up
Trabajo pendiente, si existe.
```

---

# 35. Testing Backend

Antes de considerar terminada una tarea backend, ejecutar las pruebas relevantes.

Como mínimo, cuando estén configuradas:

```bash
php artisan test
```

Para tareas específicas, ejecutar también las pruebas relacionadas.

Las funcionalidades críticas deben tener tests automatizados.

---

# 36. Testing Frontend

Ejecutar los checks disponibles en el proyecto.

Ejemplos:

```bash
npm run lint
npm run test
npm run build
```

No ejecutar comandos que no existan en el proyecto.

Primero inspeccionar `package.json`.

---

# 37. Definition of Done

Una tarea se considera terminada cuando:

* cumple los requisitos;
* respeta `MASTER_PROJECT.md`;
* no introduce scope creep;
* el código está integrado correctamente;
* los tests relevantes pasan;
* lint/format pasan cuando corresponda;
* no existen errores conocidos introducidos por la tarea;
* las migraciones están incluidas si son necesarias;
* los cambios de API están documentados;
* no existen secretos en el código;
* no existen cambios accidentales;
* se informa de cualquier limitación.

---

# 38. Cambios que requieren aprobación

El agente debe detenerse y solicitar aprobación antes de introducir cambios importantes en:

## Arquitectura

* microservicios;
* cambio de framework;
* cambio de base de datos;
* cambio de estrategia de autenticación;
* cambio importante de infraestructura.

## Seguridad

* autenticación;
* autorización;
* OAuth;
* almacenamiento de credenciales;
* manejo de datos sensibles.

## Costes

* nuevo servicio de pago;
* infraestructura con coste recurrente;
* proveedor externo imprescindible;
* aumento significativo de infraestructura.

## Producto

* nuevas funcionalidades importantes;
* cambios en reglas de negocio;
* cambios en roles;
* cambios en modelo de usuario;
* pagos;
* wallet;
* crypto;
* delivery complejo;
* funcionalidades fuera del MVP.

## Base de datos

* eliminación de columnas;
* eliminación de tablas;
* migraciones destructivas;
* transformaciones potencialmente irreversibles;
* cambios masivos de datos.

---

# 39. Seguridad de secretos

Nunca introducir en Git:

```text
API keys
OAuth secrets
passwords
tokens
private keys
production credentials
```

Utilizar variables de entorno.

Los archivos sensibles deben estar correctamente incluidos en `.gitignore`.

Debe existir un:

```text
.env.example
```

sin secretos reales.

---

# 40. Logs

No registrar información sensible innecesariamente.

Evitar logs que contengan:

* tokens;
* contraseñas;
* secretos OAuth;
* credenciales;
* información personal innecesaria.

Los logs deben ser útiles para diagnosticar problemas.

---

# 41. Datos personales

Tratar los datos personales como información sensible desde el punto de vista de diseño.

Evitar:

* exponer información innecesaria en APIs;
* devolver campos privados en perfiles públicos;
* registrar PII innecesariamente;
* permitir acceso a datos de otros usuarios sin autorización.

La privacidad debe considerarse desde el diseño de cada funcionalidad.

---

# 42. Archivos subidos

Los archivos enviados por usuarios deben validarse.

Como mínimo:

* tipo;
* tamaño;
* extensión cuando corresponda;
* contenido cuando sea necesario;
* permisos de acceso.

No confiar únicamente en la extensión del archivo.

---

# 43. Rendimiento

No optimizar prematuramente.

Primero implementar correctamente.

Optimizar cuando exista evidencia.

Especial atención a:

* N+1 queries;
* consultas geográficas;
* paginación;
* imágenes;
* requests innecesarias;
* payloads excesivos.

Las listas de publicaciones deben utilizar paginación.

---

# 44. Scope del MVP

Los agentes deben recordar que el MVP se concentra en:

```text
Authentication
     ↓
Onboarding
     ↓
Provider
     ↓
Listings
     ↓
Geolocation
     ↓
Search
     ↓
Map
     ↓
Contact
```

No implementar automáticamente:

```text
Payments
Crypto
Wallet
Chat realtime
AI
Advanced delivery
Subscriptions
```

si no existe una instrucción explícita.

---

# 45. Decisiones técnicas

Cuando exista una decisión técnica relevante que pueda afectar futuras funcionalidades, debe documentarse.

Ejemplo:

```text
Decision:
Use PostgreSQL + PostGIS for geospatial queries.

Reason:
The marketplace depends heavily on distance-based search.

Alternatives:
Application-level distance calculations.

Why rejected:
Poor scalability and unnecessary data transfer.
```

Estas decisiones deberán terminar posteriormente en:

```text
docs/DECISIONS.md
```

---

# 46. Manejo de incertidumbre

Si el agente no sabe qué decisión tomar:

No inventar.

Utilizar este orden:

```text
1. Search existing documentation
2. Search existing code
3. Search previous decisions
4. Identify the smallest reversible solution
5. Ask for clarification if the decision is important
```

---

# 47. No ocultar problemas

Si durante una tarea se descubre:

* un bug existente;
* una inconsistencia;
* un problema de seguridad;
* una deuda técnica;
* un problema de arquitectura;

el agente debe comunicarlo.

No ocultarlo simplemente para que la tarea aparezca como terminada.

Si no pertenece al alcance:

```text
Report it as follow-up.
```

---

# 48. Cambios no relacionados

Si una tarea requiere modificar un archivo que contiene cambios previos no relacionados:

No sobrescribirlos.

Primero revisar el estado del repositorio.

Preservar cambios existentes.

---

# 49. No borrar trabajo existente

No eliminar código, migraciones, componentes o documentación existente únicamente para simplificar la implementación.

Si algo debe eliminarse:

1. justificarlo;
2. comprobar dependencias;
3. verificar tests;
4. realizar el cambio de forma segura.

---

# 50. Comunicación final del agente

Al terminar una tarea, el agente debe entregar un resumen con esta estructura:

```text
## Task
Descripción breve.

## Implemented
Qué se implementó.

## Files Changed
Archivos modificados.

## Database
Migraciones o cambios de esquema.

## API
Endpoints nuevos o modificados.

## Tests
Tests ejecutados y resultado.

## Decisions
Decisiones técnicas relevantes.

## Known Issues
Problemas conocidos.

## Next Steps
Trabajo recomendado posteriormente.
```

El resumen debe ser factual y breve.

---

# 51. Regla especial para cambios de API

Cuando el Backend Agent cambie una API, debe informar explícitamente:

```text
Endpoint:
HTTP Method:

Request:
...

Response:
...

Errors:
...

Authentication:
...

Authorization:
...
```

El Frontend Agent debe consumir exactamente ese contrato.

No debe inventar una respuesta diferente.

---

# 52. Regla especial para cambios de base de datos

Cuando una tarea modifique la base de datos, el agente debe informar:

```text
Tables affected:
Columns added:
Columns changed:
Indexes:
Foreign keys:
Migration:
Rollback considerations:
```

---

# 53. Regla especial para tareas grandes

Si una tarea parece demasiado grande para una única implementación, dividirla.

Ejemplo:

```text
Google Authentication
```

puede dividirse en:

```text
1. Database structure
2. OAuth configuration
3. Backend authentication
4. Frontend login
5. Callback/session handling
6. Tests
```

No intentar implementar una funcionalidad grande mediante cambios monolíticos difíciles de revisar.

---

# 54. Prioridad de decisiones

Cuando existan varias soluciones razonables, priorizar:

```text
1. Correctness
2. Security
3. Simplicity
4. Maintainability
5. Cost
6. Performance
7. Future extensibility
```

No sacrificar seguridad o corrección únicamente para reducir unas pocas líneas de código.

---

# 55. Regla contra la sobreingeniería

No implementar una solución para un problema que todavía no existe.

Ejemplo:

No crear:

* arquitectura de microservicios;
* event sourcing;
* CQRS complejo;
* sistemas de plugins;
* abstracciones de múltiples proveedores;

si el MVP no lo necesita.

La arquitectura debe evolucionar con el producto.

---

# 56. Regla de reversibilidad

Cuando existan dos soluciones técnicamente válidas, preferir inicialmente la que:

* sea más sencilla;
* tenga menor coste;
* sea fácil de reemplazar;
* no bloquee futuras decisiones.

Esto es especialmente importante para:

* proveedores externos;
* mapas;
* almacenamiento;
* servicios de comunicación;
* infraestructura.

---

# 57. Definition of Agent Behavior

Un agente debe comportarse como un miembro técnico del equipo, no como un generador automático de código.

Debe:

```text
Understand
Inspect
Plan
Implement
Test
Review
Report
```

No debe:

```text
Guess
Over-engineer
Expand scope
Hide problems
Break contracts
Change architecture silently
```

---

# 58. Regla final

Antes de cada cambio importante, el agente debe poder responder:

> ¿Este cambio ayuda directamente a cumplir la tarea actual y respeta el producto definido en `MASTER_PROJECT.md`?

Si la respuesta es no:

```text
No implementarlo.
```

El objetivo de los agentes no es producir la mayor cantidad de código posible.

El objetivo es construir el producto de forma:

* incremental;
* segura;
* sencilla;
* verificable;
* mantenible;
* económica.

---

# END OF AGENTS.md
