describe('Bulk Delete ARSINUM - Full Simulation', () => {
  const uniqueId = Math.floor(Math.random() * 10000);
  const searchKeyword = 'BATCH' + uniqueId;
  
  const projects = [
    'ARSINUM_' + searchKeyword + '_1',
    'ARSINUM_' + searchKeyword + '_2',
    'ARSINUM_' + searchKeyword + '_3'
  ];

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should create 3 Arsinum records and then bulk delete them', () => {
    // --- 1. CREATE 3 RECORDS ---
    projects.forEach(projectName => {
        cy.visit('http://localhost:8080/arsinum');
        cy.contains('Tambah Data').click();
        
        cy.get('input[name="jenis_pekerjaan"]').clear().type(projectName);
        cy.get('input[name="volume"]').type('1 UNIT');
        cy.get('input[name="anggaran"]').type('100000000');
        cy.get('input[name="desa"]').type('DESA_TEST');
        cy.get('input[name="kecamatan"]').type('SINJAI');
        
        cy.contains('button', 'Simpan Data').click({ force: true });
        cy.contains('Data Arsinum berhasil ditambahkan', { timeout: 15000 }).should('be.visible');
    });

    // --- 2. BULK DELETE ---
    cy.visit('http://localhost:8080/arsinum');
    
    // Gunakan pencarian agar tabel hanya menampilkan data test ini
    cy.get('input[name="search"]').clear().type(searchKeyword + '{enter}');
    
    // Verifikasi data muncul di tabel sebelum dicentang
    // Kita gunakan cy.contains(text) yang mencari di seluruh halaman
    projects.forEach(name => {
        cy.contains(name, { timeout: 10000 }).should('exist');
    });

    // Pilih Semua (Gunakan CSS Selector yang lebih spesifik)
    cy.get('#select-all').should('exist').check({ force: true });

    // Verifikasi Floating Bar muncul
    cy.get('#bulk-action-bar', { timeout: 10000 }).should('be.visible');
    cy.get('#selected-count').should('contain', '3 TERPILIH');

    // Klik Hapus Terpilih
    cy.contains('button', 'Hapus Terpilih').click({ force: true });

    // Konfirmasi pada Modal
    cy.get('body').then(($body) => {
        if ($body.find('button:contains("Ya, Lanjutkan")').length > 0) {
            cy.contains('button', 'Ya, Lanjutkan').click({ force: true });
        } else {
            cy.get('#confirm-ok').click({ force: true });
        }
    });

    // Verifikasi Pesan Sukses Massal
    cy.contains(/data berhasil dihapus/i, { timeout: 15000 }).should('be.visible');

    // Final Check: Tabel harus kosong
    cy.get('input[name="search"]').clear().type(searchKeyword + '{enter}');
    cy.get('tbody').should('contain', 'Data tidak ditemukan');
  });
});
