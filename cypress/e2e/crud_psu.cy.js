describe('CRUD PSU - Full Cycle', () => {
  const uniqueName = 'JALAN TEST CYPRESS ' + Date.now();
  const updatedName = 'JALAN UPDATED CYPRESS ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123'); // Menggunakan password dari crud_rtlh.cy.js
    cy.get('button[type="submit"]').click();
  });

  it('should perform full CRUD on PSU Jaringan Jalan', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/psu');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/psu/create');
    
    cy.get('input[name="nama_jalan"]').type(uniqueName);
    cy.get('input[name="jalan"]').type('1500.50');
    cy.get('textarea[name="wkt"]').type('LINESTRING (528144.532 9433433.003, 528244.532 9433533.003)');
    
    // Submit menggunakan teks tombol yang ada di view
    cy.contains('Simpan Data').click({ force: true });

    // Verifikasi Berhasil Simpan & Redirect
    cy.url().should('include', '/psu');
    cy.contains('Data berhasil ditambahkan').should('exist');
    
    // Cari data untuk memastikan muncul di tabel
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    // Klik icon mata (detail) di baris yang sesuai
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    
    // Di halaman detail, klik tombol Edit
    cy.contains('Edit').click();
    
    // Ubah Nama Jalan
    cy.get('input[name="nama_jalan"]').clear().type(updatedName);
    
    // Simpan Perubahan
    cy.contains('Simpan Perubahan').click({ force: true });

    // Verifikasi Update
    cy.contains('Data berhasil diperbarui').should('exist');
    
    // Kembali ke list untuk verifikasi dan hapus
    cy.visit('http://localhost:8080/psu');
    cy.get('input[name="keyword"]').clear().type(updatedName + '{enter}');
    cy.contains(updatedName).should('exist');

    // 3. DELETE
    // Klik tombol hapus (ikon trash-2)
    cy.contains(updatedName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Custom Modal Konfirmasi (Pattern dari crud_rtlh.cy.js)
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else if ($body.find('button:contains("Ya")').length > 0) {
            cy.contains('button', 'Ya').click({ force: true });
        } else {
            // Fallback
            cy.on('window:confirm', () => true);
        }
    });

    // Verifikasi Terhapus
    cy.contains('Data berhasil dihapus').should('exist');
    cy.contains(updatedName).should('not.exist');
  });
});
