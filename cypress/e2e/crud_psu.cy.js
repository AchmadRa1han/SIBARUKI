describe('CRUD PSU - Full Cycle', () => {
  const uniqueName = 'JALAN TEST CYPRESS ' + Date.now();
  const updatedName = 'JALAN UPDATED CYPRESS ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on PSU Jaringan Jalan', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/psu');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/psu/create');
    
    cy.get('input[name="nama_jalan"]').type(uniqueName);
    cy.get('input[name="jalan"]').type('1500.50');
    cy.get('textarea[name="wkt"]').type('LINESTRING (528144.532 9433433.003, 528244.532 9433533.003)');
    
    cy.contains('Simpan Data').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="keyword"]').clear().type(uniqueName + '{enter}');
    cy.contains(uniqueName).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueName).closest('tr').find('a[href*="/detail/"]').click();
    cy.contains('Edit').click();
    
    cy.get('input[name="nama_jalan"]').clear().type(updatedName);
    cy.contains('Simpan Perubahan').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/psu');
    cy.get('input[name="keyword"]').clear().type(updatedName + '{enter}');
    
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
