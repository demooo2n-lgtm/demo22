-- Ejecutar en Supabase > SQL Editor.
-- Este proyecto autentica desde public.admins, no desde auth.users.
-- Si el correo ya existe, actualiza su nombre y contraseña.

create extension if not exists pgcrypto;

insert into public.admins (name, email, password_hash)
values (
  'Leo Galeano',
  'leo2009galeano@gmail.com',
  crypt('pikaboss12', gen_salt('bf', 12))
)
on conflict (email) do update
set
  name = excluded.name,
  password_hash = excluded.password_hash;
