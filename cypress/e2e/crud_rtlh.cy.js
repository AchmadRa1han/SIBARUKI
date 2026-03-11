describe('CRUD RTLH - Full Cycle', () => {
  const uniqueName = 'PENERIMA TEST ' + Date.now();
  const updatedName = 'PENERIMA UPDATED ' + Date.now();
  const nik = '1234567890123456';

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
  });

  it('should perform full CRUD on RTLH', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/rtlh');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/rtlh/create');
    
    // Identitas
    cy.get('input[name="nama_kepala_keluarga"]').type(uniqueName);
    cy.get('input[name="nik"]').type(nik);
    cy.get('input[name="no_kk"]').type('6543210987654321');
    cy.get('select[name="jenis_kelamin"]').select('L');
    
    // Wilayah (Pilih desa pertama yang tersedia)
    cy.get('select[name="desa_id"]').select(1);
    cy.get('input[name="luas_rumah_m2"]').type('45');
    cy.get('input[name="luas_lahan_m2"]').type('100');
    
    // Titik Koordinat (Klik peta secara acak atau isi manual jika input tidak readonly)
    // Di script sebelumnya kita sudah buat agar bisa diisi manual
    cy.get('#lokasi_koordinat').type('POINT(120.2536 -5.1245)', { force: true });
    
    // Penilaian Teknis (Pilih opsi pertama untuk semua select kondisi)
    cy.get('select[name="st_pondasi"]').select(1);
    cy.get('select[name="st_kolom"]').select(1);
    cy.get('select[name="st_balok"]').select(1);
    cy.get('select[name="st_sloof"]').select(1);
    cy.get('select[name="mat_atap"]').select(1);
    cy.get('select[name="st_atap"]').select(1);
    cy.get('select[name="mat_dinding"]').select(1);
    cy.get('select[name="st_dinding"]').select(1);
    cy.get('select[name="mat_lantai"]').select(1);
    cy.get('select[name="st_lantai"]').select(1);

    // Submit
    cy.contains('Simpan Semua Data').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.url().should('include', '/rtlh');
    
    // Cari data yang baru dibuat untuk memastikan muncul di tabel
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    
    // Klik Tombol Edit di halaman detail
    cy.contains('Perbarui Data').click();
    
    // Ubah Nama
    cy.get('input[name="nama_kepala_keluarga"]').clear().type(updatedName);
    cy.contains('Perbarui Data Terpadu').click({ force: true });

    // Verifikasi Update
    cy.contains('Data RTLH berhasil diperbarui').should('exist');
    
    // Kembali ke list untuk verifikasi dan hapus
    cy.visit('http://localhost:8080/rtlh');
    cy.get('input[name="keyword"]').clear().type(updatedName + '{enter}');
    cy.contains(updatedName).should('exist');

    // 3. DELETE
    // Klik tombol hapus (ikon trash)
    cy.contains(updatedName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Custom Modal Konfirmasi
    // Tunggu modal muncul
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else if ($body.find('button:contains("Ya")').length > 0) {
            cy.contains('button', 'Ya').click({ force: true });
        } else {
            // Fallback jika menggunakan window.confirm
            cy.on('window:confirm', () => true);
        }
    });

    // Verifikasi Terhapus
    cy.contains(updatedName).should('not.exist');
  });
});
