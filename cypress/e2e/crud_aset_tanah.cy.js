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
    cy.get('input[name="tgl_terbit"]').type('2024-01-01');
    cy.get('input[name="kecamatan"]').type('SINJAI UTARA');
    cy.get('input[name="desa_kelurahan"]').type('BALANGNIPA');
    cy.get('input[name="koordinat"]').type('-5.123, 120.456');
    cy.get('textarea[name="lokasi"]').type('Jalan Persatuan Raya No. 1');
    
    cy.contains('button', 'Simpan Aset').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.url().should('include', '/aset-tanah');
    cy.contains('Data aset berhasil ditambahkan').should('be.visible');
    
    // Cari data di tabel
    cy.get('input[name="search"]').clear().type(uniqueCert + '{enter}');
    cy.contains(uniqueCert).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueCert).closest('tr').find('a[href*="/detail/"]').click();
    cy.url().should('include', '/aset-tanah/detail/');
    
    // Verifikasi detail terlihat
    cy.contains(uniqueCert).scrollIntoView().should('be.visible');
    cy.contains(uniqueOwner).should('be.visible');
    
    // Klik Edit
    cy.contains('Edit').click({ force: true });
    
    // Ubah Nama Pemilik
    cy.get('input[name="nama_pemilik"]').clear().type(updatedOwner);
    cy.contains('button', 'Perbarui Data').click({ force: true });

    // Verifikasi Update
    cy.contains('Data aset berhasil diperbarui').should('be.visible');
    
    // Kembali ke list untuk verifikasi dan hapus
    cy.visit('http://localhost:8080/aset-tanah');
    cy.get('input[name="search"]').clear().type(uniqueCert + '{enter}');
    cy.contains(updatedOwner).should('exist');

    // 3. DELETE
    cy.contains(uniqueCert).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Modal Konfirmasi SIBARUKI
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains('Data aset berhasil dihapus').should('be.visible');
    cy.contains(uniqueCert).should('not.exist');
  });
});
