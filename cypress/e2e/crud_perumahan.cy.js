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
    
    cy.contains('Simpan Data Perumahan').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="search"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    cy.contains('Edit').click();
    
    cy.get('input[name="nama_perumahan"]').clear().type(updatedName);
    cy.contains('button', 'Perbarui Data').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/perumahan-formal');
    cy.get('input[name="search"]').clear().type(updatedName + '{enter}');
    
    // Klik hapus
    cy.contains(updatedName).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Modal Konfirmasi
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains(/dipindahkan ke Recycle Bin/i, { timeout: 15000 }).should('be.visible');
    cy.contains(updatedName).should('not.exist');
  });
});
