describe('CRUD Wilayah Kumuh - Full Cycle', () => {
  const uniqueKawasan = 'KAWASAN TEST CYPRESS ' + Date.now();
  const updatedKawasan = 'KAWASAN UPDATED CYPRESS ' + Date.now();
  const validDesaId = '7307070005'; // Balangnipa

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on Wilayah Kumuh', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/wilayah-kumuh');
    cy.contains('Tambah Kawasan').click();
    cy.url().should('include', '/wilayah-kumuh/create');
    
    cy.get('input[name="Kawasan"]').type(uniqueKawasan);
    cy.get('input[name="Kecamatan"]').type('SINJAI UTARA');
    cy.get('input[name="Kelurahan"]').type('BALANGNIPA');
    cy.get('input[name="Luas_kumuh"]').type('12.5');
    cy.get('input[name="skor_kumuh"]').type('65.5');
    
    const wktData = 'POLYGON((120.2536 -5.1245, 120.2546 -5.1245, 120.2546 -5.1255, 120.2536 -5.1255, 120.2536 -5.1245))';
    cy.get('textarea[name="WKT"]').invoke('val', wktData);
    
    cy.contains('button', 'Simpan Wilayah').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="keyword"]').clear().type(uniqueKawasan + '{enter}');
    cy.contains(uniqueKawasan).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueKawasan).closest('tr').find('a[href*="/detail/"]').click();
    cy.contains(uniqueKawasan).scrollIntoView().should('be.visible');
    
    cy.contains('Edit Lokasi').click({ force: true });
    cy.get('input[name="Kawasan"]').clear().type(updatedKawasan);
    cy.contains('button', 'Perbarui Data Wilayah').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/wilayah-kumuh');
    cy.get('input[name="keyword"]').clear().type(updatedKawasan + '{enter}');
    
    // Klik hapus
    cy.contains(updatedKawasan).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
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
    cy.contains(updatedKawasan).should('not.exist');
  });
});
