# Hekimport - Healthcare Appointment Management System

![Hekimport Logo](data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🩺</text></svg>)

## 🇬🇧 English

Hekimport is a modern healthcare appointment management system built with Laravel 11. The application provides a streamlined way for healthcare facilities to manage doctors, patients, and appointments.

### Features

- **User Authentication**: Secure login and registration system
- **Dashboard**: Overview of system statistics and upcoming appointments
- **Doctor Management**: Add, edit, view, and delete doctor profiles with specialties
- **Patient Management**: Comprehensive patient records including medical history
- **Appointment System**: Schedule, track, and manage patient appointments
- **Responsive Design**: Works on desktop and mobile devices

### Requirements

- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/hekimport.git
cd hekimport
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install NPM dependencies:
```bash
npm install
```

4. Copy environment file and configure database:
```bash
cp .env.example .env
# Edit .env file to configure your database connection
```

5. Generate application key:
```bash
php artisan key:generate
```

6. Run the migrations:
```bash
php artisan migrate
```

7. Compile assets:
```bash
npm run dev
```

8. Start the server:
```bash
php artisan serve
```

The application will be available at http://127.0.0.1:8000

### Usage

- Navigate to the homepage
- Register a new account or login with existing credentials
- Use the dashboard to access different modules:
  - Doctors: Manage healthcare providers
  - Patients: Create and update patient records
  - Appointments: Schedule and track patient appointments

### License

This project is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🇹🇷 Türkçe

Hekimport, Laravel 11 ile geliştirilmiş modern bir sağlık randevu yönetim sistemidir. Uygulama, sağlık kuruluşlarının doktor, hasta ve randevuları verimli bir şekilde yönetmesine olanak tanır.

### Özellikler

- **Kullanıcı Kimlik Doğrulama**: Güvenli giriş ve kayıt sistemi
- **Dashboard**: Sistem istatistikleri ve yaklaşan randevuların genel görünümü
- **Doktor Yönetimi**: Uzmanlık alanlarıyla birlikte doktor profillerini ekleme, düzenleme, görüntüleme ve silme
- **Hasta Yönetimi**: Tıbbi geçmiş dahil kapsamlı hasta kayıtları
- **Randevu Sistemi**: Hasta randevularını planlama, takip etme ve yönetme
- **Duyarlı Tasarım**: Masaüstü ve mobil cihazlarda çalışır

### Gereksinimler

- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Node.js & NPM

### Kurulum

1. Depoyu klonlayın:
```bash
git clone https://github.com/kullaniciadi/hekimport.git
cd hekimport
```

2. PHP bağımlılıklarını yükleyin:
```bash
composer install
```

3. NPM bağımlılıklarını yükleyin:
```bash
npm install
```

4. Çevre dosyasını kopyalayın ve veritabanını yapılandırın:
```bash
cp .env.example .env
# Veritabanı bağlantınızı yapılandırmak için .env dosyasını düzenleyin
```

5. Uygulama anahtarını oluşturun:
```bash
php artisan key:generate
```

6. Migrasyonları çalıştırın:
```bash
php artisan migrate
```

7. Varlıkları derleyin:
```bash
npm run dev
```

8. Sunucuyu başlatın:
```bash
php artisan serve
```

Uygulama http://127.0.0.1:8000 adresinde kullanılabilir olacaktır.

### Kullanım

- Ana sayfaya gidin
- Yeni bir hesap oluşturun veya mevcut kimlik bilgilerinizle giriş yapın
- Farklı modüllere erişmek için dashboard'u kullanın:
  - Doktorlar: Sağlık sağlayıcılarını yönetin
  - Hastalar: Hasta kayıtları oluşturun ve güncelleyin
  - Randevular: Hasta randevularını planlayın ve takip edin

### Lisans

Bu proje [MIT lisansı](LICENSE) altında lisanslanmış açık kaynaklı bir yazılımdır.