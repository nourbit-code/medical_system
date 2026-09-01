# CarePoint Clinic Management System

نظام بسيط لإدارة عيادة طبية مبني باستخدام Laravel وPHP وBlade وMySQL وHTML وCSS وBootstrap 5.

## الأدوار

- `admin`: إدارة الحسابات والأطباء والمرضى والمواعيد.
- `doctor`: إدارة المواعيد المتاحة، مواعيده، المرضى، والـEMR.
- `patient`: حجز مواعيد الأطباء المتاحة وعرض الزيارات والسجل الطبي الخاص به.

## بيانات Admin الافتراضية

```text
Email: admin@carepoint.test
Password: Set this only in your local environment
```

يتم إنشاء الحساب من `database/seeders/DatabaseSeeder.php`.

## System Flow

```text
Login / Register
        ↓
Dashboard حسب Role
        ↓
Doctor creates available slots
        ↓
Patient chooses Doctor ثم Slot
        ↓
Appointment is created and the slot becomes booked
        ↓
Doctor starts the appointment
        ↓
Doctor saves diagnosis, treatment, and prescription
        ↓
Appointment becomes completed
        ↓
EMR appears in appointment details and patient history
```

## Requirements and XAMPP Setup

- XAMPP with Apache and MySQL
- PHP and Composer
- MySQL database named `medical_system`

```bash
cd C:\xampp\htdocs\medical_system
composer install
copy .env.example .env
php artisan key:generate
```

Create a database named `medical_system` in phpMyAdmin and set these values in `.env`:

```env
APP_NAME="CarePoint Clinic"
APP_URL=http://127.0.0.1:8000
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medical_system
DB_USERNAME=root
DB_PASSWORD=
```

Then run:

```bash
php artisan migrate --seed
php artisan serve
```

Open `http://127.0.0.1:8000`.

## Main Logic

`AuthController` handles authentication. `Authenticate` requires login, and `RoleMiddleware` limits routes to `admin`, `doctor`, or `patient`.

The doctor enters a date, start time, end time, and duration. For example, `10:00` to `12:00` with `30` minutes creates `10:00`, `10:30`, `11:00`, and `11:30`. A patient first chooses a doctor, then sees only that doctor's future unbooked slots. Booking sets `is_booked` to `true`.

The doctor starts an appointment, writes the EMR, and saves diagnosis, treatment, and electronic prescription items. Saving the EMR changes the appointment status to `completed`. The EMR is shown inside appointment details and patient history; the separate EMR Library page is not used.

## Database Tables

- `users`: account name, email, password, and role.
- `patients`: patient profile and contact data.
- `doctors`: doctor profile and specialization.
- `doctor_availabilities`: doctor date/time slots and booking state.
- `appointments`: patient, doctor, slot, date, time, reason, and status.
- `medical_records`: diagnosis, symptoms, notes, treatment, and prescription.

Appointment statuses are `pending`, `confirmed`, `in_progress`, `completed`, and `cancelled`.

Foreign keys use safe delete behavior. Patients and doctors with appointments cannot be deleted. A medical record is deleted automatically when its appointment is deleted.

## Eloquent Relationships

```text
User hasOne Doctor and hasOne Patient
Patient hasMany Appointments
Doctor hasMany Appointments and hasMany DoctorAvailability
Appointment belongsTo Patient, Doctor, and DoctorAvailability
Appointment hasOne MedicalRecord
MedicalRecord belongsTo Appointment
```

The `age` value is calculated from `date_of_birth` by an Eloquent accessor and is not stored separately.

## Controllers

- `AuthController.php`: login, register, and logout.
- `DashboardController.php`: loads the correct dashboard and database statistics.
- `UserController.php`: admin user management and current account editing.
- `PatientController.php`: patient CRUD, search, sorting, and doctor patient history.
- `DoctorController.php`: doctor CRUD, search, sorting, and doctor details.
- `AppointmentController.php`: appointment CRUD, access checks, booking, and starting visits.
- `AvailabilityController.php`: creates time periods and separates them into slots.
- `MedicalRecordController.php`: creates and updates EMR and electronic prescriptions.

## Important Models

- `User.php`: account data, role, and profile relationships.
- `Patient.php`: patient data, appointments, and age accessor.
- `Doctor.php`: doctor data, appointments, slots, and age accessor.
- `Appointment.php`: appointment relationships and status.
- `DoctorAvailability.php`: available slots and booking relationship.
- `MedicalRecord.php`: diagnosis and treatment record for one appointment.

## Routes and Permissions

`routes/web.php` contains public authentication routes and protected application routes. Admin routes manage `users`, `patients`, and `doctors`. Doctor routes manage `availability` and doctor patient history. All roles can access their permitted appointment routes, while only doctors can create EMRs.

## Views and Reusable Components

```text
resources/views/
├── layouts/app.blade.php
├── components/navbar.blade.php
├── components/flash-messages.blade.php
├── components/stat-card.blade.php
├── components/status-badge.blade.php
├── auth/
├── dashboard/
├── users/
├── patients/
├── doctors/
├── doctors/patients/
├── appointments/
├── availability/
├── medical_records/
└── account/
```

`layouts/app.blade.php` is the shared layout. `navbar.blade.php` contains the responsive sidebar. `flash-messages.blade.php` displays session messages. `stat-card.blade.php` displays dashboard statistics. `status-badge.blade.php` displays colored appointment statuses.

Page folders contain the standard `index`, `create`, `edit`, `show`, and `form` Blade files where needed. `appointments/show.blade.php` displays appointment data and its EMR. `doctors/patients/show.blade.php` displays the patient profile and past visits.

## CSS and JavaScript

- `public/css/app.css`: CarePoint colors, sidebar, cards, forms, tables, dashboards, and responsive styling.
- `public/js/app.js`: simple browser interactions such as the mobile sidebar.
- `resources/js/app.js`: Laravel frontend source entry point.
- `resources/js/bootstrap.js`: Laravel JavaScript bootstrap file.

Bootstrap 5 is loaded through CDN. The project does not use React, Vue, Inertia, Livewire, APIs, WebSockets, or extra application packages.

## Database and Configuration Files

- `database/migrations/`: creates and updates all tables in order.
- `database/seeders/DatabaseSeeder.php`: creates the default admin.
- `database/factories/UserFactory.php`: creates test users.
- `config/database.php`: reads MySQL settings from `.env`.
- `config/auth.php`: configures the `User` authentication model.
- `config/app.php`: application name, locale, and timezone.
- `config/session.php`: login session configuration.
- `config/view.php`: Blade configuration.
- `config/hashing.php`: password hashing configuration.
- `config/logging.php`: application log configuration.
- Other files in `config/` are standard Laravel configuration files for cache, mail, queue, filesystems, services, CORS, broadcasting, and Sanctum.

## Other Standard Laravel Files

- `artisan`: Laravel command entry point.
- `bootstrap/app.php`: starts the application.
- `public/index.php`: entry point for browser requests.
- `public/.htaccess`: Apache rewrite rules for XAMPP.
- `storage/`: logs, sessions, cache, and compiled Blade views.
- `composer.json`: PHP dependencies.
- `composer.lock`: exact dependency versions.
- `package.json` and `webpack.mix.js`: frontend asset metadata.
- `phpunit.xml` and `tests/`: testing configuration and examples.
- `.env.example`: safe environment template.
- `.env`: local secrets; it must not be committed.
- `.gitignore`: excluded files and folders.
- `README.md`: project documentation.

## Useful Commands

```bash
php artisan route:list
php artisan migrate:status
php artisan view:cache
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

To recreate the local database and admin account during development:

```bash
php artisan migrate:fresh --seed
```

This deletes existing local database data.

## GitHub

[nourbit-code/medical_system](https://github.com/nourbit-code/medical_system)

## License

Educational Laravel project for learning clinic management, routing, authentication, migrations, Eloquent, controllers, validation, and Blade.

