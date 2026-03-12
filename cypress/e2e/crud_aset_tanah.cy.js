describe('CRUD Aset Tanah Pemda - Full Cycle', () => {
  const uniqueCert = 'CERT-' + Date.now();
  const uniqueOwner = 'PEMDA SINJAI TEST ' + Date.now();
  const updatedOwner = 'PEMDA SINJAI UPDATED ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on Aset Tanah', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/aset-tanah');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/aset-tanah/create');
    
    cy.get('input[name="no_sertifikat"]').type(uniqueCert);
    cy.get('input[name="nama_pemilik"]').type(uniqueOwner);
    cy.get('input[name="luas_m2"]').type('5000');
    cy.get('input[name="kecamatan"]').type('SINJAI UTARA');
    cy.get('input[name="desa_kelurahan"]').type('BALANGNIPA');
    
    cy.contains('button', 'Simpan Aset').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="search"]').clear().type(uniqueCert + '{enter}');
    cy.contains(uniqueCert).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueCert).closest('tr').find('a[href*="/detail/"]').click();
    cy.contains('Edit').click({ force: true });
    
    cy.get('input[name="nama_pemilik"]').clear().type(updatedOwner);
    cy.contains('button', 'Perbarui Data').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/aset-tanah');
    cy.get('input[name="search"]').clear().type(uniqueCert + '{enter}');
    
    // Klik hapus
    cy.contains(uniqueCert).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
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
    cy.contains(uniqueCert).should('not.exist');
  });
});
