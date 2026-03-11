describe('CRUD Perumahan Formal - Full Cycle', () => {
  const uniqueName = 'PERUMAHAN CYPRESS ' + Date.now();
  const updatedName = 'PERUMAHAN UPDATED ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on Perumahan Formal', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/perumahan-formal');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/perumahan-formal/create');
    
    cy.get('input[name="nama_perumahan"]').type(uniqueName);
    cy.get('input[name="pengembang"]').type('PT CYPRESS DEVELOPER');
    cy.get('input[name="tahun_pembangunan"]').type('2024');
    cy.get('input[name="luas_kawasan_ha"]').type('5.75');
    cy.get('input[name="longitude"]').type('120.2536');
    cy.get('input[name="latitude"]').type('-5.1245');
    cy.get('textarea[name="wkt"]').type('POINT(120.2536 -5.1245)');
    
    // Klik tombol simpan
    cy.contains('Simpan Data Perumahan').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.url().should('include', '/perumahan-formal');
    cy.contains('Data perumahan berhasil ditambahkan').should('be.visible');
    
    // Cari data di tabel
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    cy.url().should('include', '/perumahan-formal/detail/');
    
    // Klik tombol Edit di halaman detail
    cy.contains('Edit').click();
    
    // Pastikan input sudah terisi data lama sebelum di-clear
    cy.get('input[name="nama_perumahan"]').should('have.value', uniqueName);
    
    // Ubah Nama Perumahan
    cy.get('input[name="nama_perumahan"]').clear().type(updatedName);
    
    // Klik Perbarui Data
    cy.contains('button', 'Perbarui Data').click({ force: true });

    // Verifikasi Update (Gunakan pencarian yang lebih fleksibel tanpa titik di akhir)
    cy.contains('Data berhasil diperbarui', { timeout: 10000 }).should('be.visible');
    
    // Verifikasi di halaman index
    cy.visit('http://localhost:8080/perumahan-formal');
    cy.get('input[name="keyword"]').clear().type(updatedName + '{enter}');
    cy.contains(updatedName).should('exist');

    // 3. DELETE
    cy.contains(updatedName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Modal Konfirmasi SIBARUKI
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            // Jika tombol kustom tidak ketemu, fallback ke OK
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains('Data berhasil dihapus').should('be.visible');
    cy.contains(updatedName).should('not.exist');
  });
});
