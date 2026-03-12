describe('CRUD ARSINUM - Full Cycle', () => {
  const uniqueProject = 'ARSINUM PROYEK CYPRESS ' + Date.now();
  const updatedProject = 'ARSINUM PROYEK UPDATED ' + Date.now();

  beforeEach(() => {
    cy.visit('http://localhost:8080/login');
    cy.get('input[name="username"]').type('admin');
    cy.get('input[name="password"]').type('password123');
    cy.get('button[type="submit"]').click();
    cy.url().should('include', '/dashboard');
  });

  it('should perform full CRUD on ARSINUM', () => {
    // 1. CREATE
    cy.visit('http://localhost:8080/arsinum');
    cy.contains('Tambah Data').click();
    cy.url().should('include', '/arsinum/create');
    
    cy.get('input[name="jenis_pekerjaan"]').type(uniqueProject);
    cy.get('input[name="volume"]').type('1 UNIT');
    cy.get('input[name="anggaran"]').type('250000000');
    cy.get('input[name="desa"]').type('DESA LAPPA');
    cy.get('input[name="kecamatan"]').type('SINJAI UTARA');
    
    cy.contains('button', 'Simpan Data').click({ force: true });

    // Verifikasi Berhasil Simpan
    cy.contains(/berhasil ditambahkan/i, { timeout: 15000 }).should('be.visible');
    
    // Cari data
    cy.get('input[name="search"]').clear().type(uniqueProject + '{enter}');
    cy.contains(uniqueProject).should('exist');

    // 2. READ & UPDATE
    cy.contains(uniqueProject).closest('tr').find('a[href*="/detail/"]').click();
    cy.contains('Edit').click({ force: true });
    
    cy.get('input[name="jenis_pekerjaan"]').clear().type(updatedProject);
    cy.contains('button', 'Simpan Perubahan').click({ force: true });

    // Verifikasi Update
    cy.contains(/berhasil diperbarui/i, { timeout: 15000 }).should('be.visible');
    
    // 3. DELETE
    cy.visit('http://localhost:8080/arsinum');
    cy.get('input[name="search"]').clear().type(updatedProject + '{enter}');
    
    // Klik hapus
    cy.contains(updatedProject).closest('tr').find('button').filter(':has([data-lucide="trash-2"])').click({ force: true });
    
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
    cy.contains(updatedProject).should('not.exist');
  });
});
