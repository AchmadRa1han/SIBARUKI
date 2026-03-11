describe('CRUD Wilayah Kumuh - Full Cycle', () => {
  const uniqueKawasan = 'KAWASAN TEST CYPRESS ' + Date.now();
  const updatedKawasan = 'KAWASAN UPDATED CYPRESS ' + Date.now();
  const validDesaId = '7307070005'; // Balangnipa (Sinjai Utara)

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
    cy.get('input[name="desa_id"]').type(validDesaId);
    
    // Gunakan invoke untuk mengisi textarea readonly (WKT)
    const wktData = 'POLYGON((120.2536 -5.1245, 120.2546 -5.1245, 120.2546 -5.1255, 120.2536 -5.1255, 120.2536 -5.1245))';
    cy.get('textarea[name="WKT"]').invoke('val', wktData);
    
    cy.contains('button', 'Simpan Wilayah').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.url().should('include', '/wilayah-kumuh');
    cy.contains('Data berhasil ditambahkan').should('be.visible');
    
    // Cari data di tabel
    cy.get('input[name="keyword"]').clear().type(uniqueKawasan + '{enter}');
    cy.contains(uniqueKawasan).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueKawasan).closest('tr').find('a[href*="/detail/"]').click();
    cy.url().should('include', '/wilayah-kumuh/detail/');
    
    // Gunakan scrollIntoView jika elemen terpotong (clipped) oleh overflow:hidden
    cy.contains(uniqueKawasan).scrollIntoView().should('be.visible');
    
    // Klik Edit
    cy.contains('Edit').click({ force: true });
    
    // Ubah Nama Kawasan
    cy.get('input[name="Kawasan"]').clear().type(updatedKawasan);
    cy.contains('button', 'Perbarui Data Wilayah').click({ force: true });

    // Verifikasi Update
    cy.contains('Data berhasil diperbarui').should('be.visible');
    
    // Kembali ke list untuk verifikasi dan hapus
    cy.visit('http://localhost:8080/wilayah-kumuh');
    cy.get('input[name="keyword"]').clear().type(updatedKawasan + '{enter}');
    cy.contains(updatedKawasan).should('exist');

    // 3. DELETE
    cy.contains(updatedKawasan).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
    // Menangani Modal Konfirmasi
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Terhapus
    cy.contains('Data berhasil dihapus').should('be.visible');
    cy.contains(updatedKawasan).should('not.exist');
  });
});
