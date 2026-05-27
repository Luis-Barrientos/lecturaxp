# 🚀 Guía de Setup Inicial - LecturaXP

Después del primer deploy a Railway, ejecuta estos comandos para configurar la aplicación:

## 1️⃣ Ejecutar Migraciones

Ya se ejecutan automáticamente en el deploy, pero si necesitas hacerlo manualmente:

```bash
php artisan migrate
```

## 2️⃣ Cargar Logros (Achievements)

Los logros deben insertarse manualmente la primera vez:

```bash
php artisan db:seed --class=AchievementSeeder
```

O todos los seeders:

```bash
php artisan db:seed
```

## 3️⃣ Promover Usuario a Admin

Si necesitas convertir un usuario en administrador:

```bash
php artisan user:make-admin tu-email@example.com
```

**Ejemplo:**
```bash
php artisan user:make-admin test@example.com
```

## 📋 Verificación

Para verificar que todo está correcto:

- ✅ Accede a `/admin` - Deberías ver el panel de administración
- ✅ Accede a `/logros` - Deberías ver los logros disponibles
- ✅ En el navbar aparece el botón "⚙ Admin" (solo para admins)

## 🔧 Comandos Útiles

### Hacer admin a un usuario
```bash
php artisan user:make-admin email@example.com
```

### Ver usuarios
```bash
php artisan tinker
App\Models\User::all();
exit
```

### Resetear base de datos (⚠️ Cuidado, borra todo)
```bash
php artisan migrate:refresh --seed
```

---

**Creado:** 27 de Mayo 2026  
**Proyecto:** LecturaXP
