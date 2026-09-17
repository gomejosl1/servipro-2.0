# DECISIONS.md

# Architecture & Product Decisions

Este documento registra decisiones importantes tomadas durante el desarrollo del proyecto.

El objetivo es evitar que los agentes vuelvan a discutir o cambiar decisiones ya establecidas sin motivo.

---

# Decision Log

## DEC-001 — Monolithic Architecture

**Status:** Accepted

### Decision

El MVP utilizará una arquitectura monolítica modular.

### Choice

```text
Laravel
+
React
+
PostgreSQL/PostGIS
```

### Reason

El proyecto se encuentra en fase de validación.

Una arquitectura monolítica:

* reduce costes;
* reduce infraestructura;
* facilita desarrollo;
* facilita debugging;
* permite iterar rápidamente.

No existe actualmente una necesidad demostrada de microservicios.

---

## DEC-002 — PostgreSQL + PostGIS

**Status:** Accepted

### Decision

La base de datos será PostgreSQL con PostGIS.

### Reason

La geolocalización es una característica fundamental del marketplace.

PostGIS permite realizar búsquedas eficientes por:

* distancia;
* radio;
* proximidad;
* coordenadas.

Esto evita implementar cálculos geográficos en PHP.

---

## DEC-003 — React + Laravel

**Status:** Accepted

### Decision

El frontend y backend estarán separados dentro del mismo repositorio:

```text
frontend/
backend/
```

### Reason

El proyecto necesita una experiencia frontend rica y una API claramente definida.

Esta separación permite que dos agentes trabajen de forma relativamente independiente.

---

## DEC-004 — OAuth Initial Authentication

**Status:** Accepted

### Decision

La autenticación inicial será mediante:

```text
Google
Facebook
```

### Reason

El objetivo es reducir la fricción de registro durante la validación del MVP.

No se implementarán contraseñas tradicionales inicialmente.

---

## DEC-005 — No Payments in MVP

**Status:** Accepted

### Decision

El MVP no tendrá pagos internos.

### Reason

El objetivo inicial es validar:

```text
Discovery
+
Supply
+
Demand
+
Contact
```

Los pagos introducen complejidad técnica, operativa y regulatoria que no es necesaria para validar el núcleo del marketplace.

---

## DEC-006 — MapLibre

**Status:** Accepted

### Decision

El frontend utilizará MapLibre GL JS.

### Reason

Permite mantener la capa de visualización del mapa relativamente desacoplada del proveedor de mapas.

El proveedor definitivo de tiles/geocoding se decidirá considerando costes, cobertura y condiciones de uso.

---

# Future Decisions

Las siguientes cuestiones permanecen deliberadamente abiertas:

```text
Production hosting
Production database provider
Object storage provider
Map tile provider
Geocoding provider
Email provider
Analytics
Monitoring
Chat architecture
Payment provider
```

No deben considerarse decisiones tomadas hasta que se documenten explícitamente.
