# Walkthrough - Update Modul Project Management (Revisi v2)

**Tanggal:** 23-07-2026  
**Tujuan:** Menyesuaikan modul projects agar sesuai konsep Excel "Development Program"

---

## Perubahan Utama (v2)

### 1. Multi-User BA & Programmer
- BA dan Programmer sekarang bisa **lebih dari 1 orang** (multi-select)
- Disimpan ke tabel baru `pm_project_roles` (many-to-many)
- Di form create: menggunakan Select2 multi-select

### 2. Modul Tidak Auto-Generate
- Modul **tidak** otomatis ter-generate saat halaman dibuka
- User harus klik **"Create Modul"** dulu → input nama modul → baru 12 tahapan muncul
- Jika BA/Programmer > 1 orang, PIC per tahapan harus **dipilih manual**
- Jika BA/Programmer hanya 1, PIC otomatis terisi

### 3. Detail Project = Per Modul + Tahapan Sequential
- Detail project menampilkan per modul → 12 tahapan berurutan
- Tahapan **sequential** (tidak bisa bypass):
  - Tahapan 1 = `active` (bisa diisi)
  - Tahapan 2-12 = `locked` (terkunci)
  - Setelah tahapan 1 di-finish → tahapan 2 jadi `active`, dst.
- Tiap tahapan punya tombol **"Isi Task"** dan **"Finish"**
- Setelah finish, tombol hilang dan hanya bisa **"View"**

---

## Struktur Database Baru

### Tabel `pm_project_roles`
Relasi many-to-many user ↔ project per role (ba, programmer, qa, pm)

### Tabel `pm_modules`
Modul per project (module_name, module_order, status)

### Tabel `pm_module_tahapan`
12 tahapan fix per modul. Field penting:
- `tahapan_order` (1-12)
- `pic_user_id` (PIC yang dipilih)
- `plan_manhour`, `plan_due_date`
- `actual_manhour`, `actual_finish_date`
- `status`: locked | active | finish

### Tabel `pm_tahapan_tasks`
Log pekerjaan harian per tahapan (tanggal, aktivitas, manhour, file)

---

## Flow Sesuai Konsep Excel

### Create Project
1. Isi header (Client, Project, Target date, BA[], Programmer[], PM, QA)
2. Klik "Create Modul" → SweetAlert input nama
3. Tabel 12 tahapan muncul, PIC dipilih per tahapan (jika > 1 orang)
4. Isi manhour & due date
5. Save → Data tersimpan, plan terkunci (`is_plan_locked = 1`)

### Detail / Update Project  
1. Tampil per modul → per tahapan
2. Hanya tahapan **active** yang bisa diisi task
3. Klik "Isi Task" → Modal input (tanggal, aktivitas, manhour, file)
4. Klik "Finish" → Tahapan selesai, tahapan berikutnya terbuka
5. Setelah semua 12 tahapan finish → tombol "Modul Finish" muncul

---

## File yang Perlu Dijalankan
```
application/modules/projects/sql/update_pm_tasks_structure.sql
```

## Library yang Digunakan
- **SweetAlert2** — Semua dialog (global di header)
- **Flatpickr** — Date picker (include per halaman)
- **Select2** — Multi-select BA & Programmer (global di footer)
- **DataTables** — Table list project
